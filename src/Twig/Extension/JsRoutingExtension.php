<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Twig\Extension;

use FOS\JsRoutingBundle\Extractor\ExposedRoutesExtractorInterface;
use FOS\JsRoutingBundle\Response\RoutesResponse;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class JsRoutingExtension extends AbstractExtension
{
    public function __construct(
        private ExposedRoutesExtractorInterface $exposedRoutesExtractor,
        private RequestStack $requestStack,
        private $serializer,
        private bool $debug = false
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('js_routing_data', [$this, 'getRoutes']),
        ];
    }

    public function getRoutes(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        $cache = new ConfigCache($this->exposedRoutesExtractor->getCachePath($request->getLocale()), $this->debug);

        if (!$cache->isFresh() || $this->debug) {
            $exposedRoutes = $this->exposedRoutesExtractor->getRoutes();
            $serializedRoutes = $this->serializer->serialize($exposedRoutes, 'json');
            $cache->write($serializedRoutes, $this->exposedRoutesExtractor->getResources());
        } else {
            $path = method_exists($cache, 'getPath') ? $cache->getPath() : (string) $cache;
            $serializedRoutes = file_get_contents($path);
            $exposedRoutes = $this->serializer->deserialize(
                $serializedRoutes,
                'Symfony\Component\Routing\RouteCollection',
                'json'
            );
        }

        $routesResponse = new RoutesResponse(
            $this->exposedRoutesExtractor->getBaseUrl(),
            $exposedRoutes,
            $this->exposedRoutesExtractor->getPrefix($request->getLocale()),
            $this->exposedRoutesExtractor->getHost(),
            $this->exposedRoutesExtractor->getPort(),
            $this->exposedRoutesExtractor->getScheme(),
            $request->getLocale(),
            $request->query->has('domain') ? explode(',', $request->query->get('domain')) : []
        );

        return $this->serializer->serialize($routesResponse, 'json');
    }
}
