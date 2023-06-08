<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Twig\Extension;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\ResetInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CookieConfigExtension extends AbstractExtension implements ResetInterface
{
    private ?array $decoded = null;

    public function __construct(
        private RequestStack $requestStack,
        private string $cookieName
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cookie_config', $this->getConfigValue(...)),
        ];
    }

    /**
     * Get a value from the cookie config.
     *
     * @param string $key The config key.
     *
     * @return mixed The config value.
     */
    public function getConfigValue(string $key): mixed
    {
        if (null === $this->decoded) {
            $request = $this->requestStack->getCurrentRequest();

            if (null !== $request && $request->cookies->has($this->cookieName)) {
                $cookie = (string) $request->cookies->get($this->cookieName);

                $this->decoded = \json_decode($cookie, true) ?? []; // @phpstan-ignore-line
            } else {
                $this->decoded = [];
            }
        }

        return $this->decoded[$key] ?? null; // @phpstan-ignore-line
    }

    public function reset(): void
    {
        $this->decoded = null;
    }
}
