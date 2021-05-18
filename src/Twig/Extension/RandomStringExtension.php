<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RandomStringExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('random_string', [$this, 'getRandomString']),
        ];
    }

    /**
     * Generate and return a secure pseudo-random string using the random_int function.
     *
     * @param int    $length   [optional] The desired length of the random string. Defaults to 32.
     * @param string $keyspace [optional] The keyspace of the random string. Defaults to [A-z0-9]
     *
     * @return string The generated string
     */
    public function getRandomString(int $length = 32, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
    {
        if ($length < 1) {
            throw new \RangeException('Length must be a positive integer');
        }

        $max = mb_strlen($keyspace, '8bit') - 1;

        $string = '';
        for ($i = 0; $i < $length; ++$i) {
            $string .= $keyspace[random_int(0, $max)];
        }

        return $string;
    }
}
