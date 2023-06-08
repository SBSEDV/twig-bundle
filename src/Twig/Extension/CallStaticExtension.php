<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CallStaticExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('call_static', $this->callStatic(...)),
        ];
    }

    /**
     * Forward a static call to the given class and method.
     *
     * @param string $class  The class name.
     * @param string $method The class method to call.
     * @param mixed  $args   [optional] Arguments passed to the method.
     *
     * @return mixed The return value of the called method.
     */
    public function callStatic(string $class, string $method, mixed ...$args): mixed
    {
        if (!\class_exists($class) || !\method_exists($class, $method)) {
            throw new \InvalidArgumentException(\sprintf('Can not call static method %s on Class %s.', $method, $class));
        }

        return \forward_static_call_array([$class, $method], $args); // @phpstan-ignore-line
    }
}
