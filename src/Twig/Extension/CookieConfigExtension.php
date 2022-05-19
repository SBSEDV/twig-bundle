<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CookieConfigExtension extends AbstractExtension
{
    private ?array $decoded = null;

    public function __construct(
        private string $cookieName
    ) {
    }

    /**
     * {@inheritdoc}
     */
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
            if (!isset($_COOKIE[$this->cookieName])) {
                return null;
            }

            $this->decoded = \json_decode($_COOKIE[$this->cookieName], true) ?? []; // @phpstan-ignore-line
        }

        return $this->decoded[$key] ?? null; // @phpstan-ignore-line
    }
}
