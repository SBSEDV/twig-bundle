<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class InstanceOfExtension extends AbstractExtension
{
    public function getTests(): array
    {
        return [
            new TwigTest('instanceof', $this->isInstanceOf(...)),
        ];
    }

    /**
     * Check if an obect is an instance of the given class.
     *
     * @param mixed  $value The object to test.
     * @param string $class The class to test for.
     */
    public function isInstanceOf(mixed $value, string $class): bool
    {
        return $value instanceof $class;
    }
}
