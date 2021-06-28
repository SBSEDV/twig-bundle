<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\DependencyInjection;

use SBSEDV\Bundle\TwigBundle\EventListener\TimezoneListener;
use SBSEDV\Bundle\TwigBundle\Twig\Extension\CookieConfigExtension;
use SBSEDV\Bundle\TwigBundle\Twig\Extension\JsRoutingExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelEvents;

class SBSEDVTwigExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $cookieConfig = $container->getDefinition(CookieConfigExtension::class);
        $cookieConfig->replaceArgument('$config', $config['cookie_config']);

        if ($config['event_listeners']['TimezoneListener']['enable'] === true) {
            $container->getDefinition(TimezoneListener::class)
                ->addTag('kernel.event_listener', [
                    'event' => KernelEvents::REQUEST,
                    'method' => 'onKernelRequest',
                    'priority' => $config['event_listeners']['TimezoneListener']['priority'],
                ])
                ->replaceArgument('$cookieName', $config['event_listeners']['TimezoneListener']['cookie_name'])
                ->replaceArgument('$headerName', $config['event_listeners']['TimezoneListener']['header_name'])
                ->replaceArgument('$sessionName', $config['event_listeners']['TimezoneListener']['session_name']);
        }

        if (array_key_exists('FOSJsRoutingBundle', $container->getParameter('kernel.bundles'))) {
            $container->setDefinition(JsRoutingExtension::class, new Definition(JsRoutingExtension::class));

            $container->getDefinition(JsRoutingExtension::class)
                ->setArgument('$exposedRoutesExtractor', new Reference('fos_js_routing.extractor'))
                ->setArgument('$serializer', new Reference('fos_js_routing.serializer'))
                ->setArgument('$requestStack', new Reference(RequestStack::class))
                ->setArgument('$debug', $container->getParameter('kernel.debug'))
                ->addTag('twig.extension');
        }
    }
}
