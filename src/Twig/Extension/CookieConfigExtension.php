<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CookieConfigExtension extends AbstractExtension
{
    protected ?array $decoded = null;

    public function __construct(
        protected array $config
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('cookie_config', [$this, 'getConfigValue']),
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
            if (!isset($_COOKIE[$this->config['cookie_name']])) {
                return null;
            }

            $this->decoded = json_decode($_COOKIE[$this->config['cookie_name']], true) ?? [];
        }

        return $this->decoded[$key] ?? null;
    }
}
