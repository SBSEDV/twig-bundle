<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class InstanceOfExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [
            new TwigTest('instanceof', [$this, 'isInstanceOf']),
        ];
    }

    /**
     * Check if an obect is an instance of the given class.
     *
     * @param object $value The object to test.
     * @param string $class The class to test for.
     */
    public function isInstanceOf(object $value, string $class): bool
    {
        return $value instanceof $class;
    }
}
