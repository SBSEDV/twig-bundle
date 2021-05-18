<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ParameterExtension extends AbstractExtension
{
    public function __construct(
        protected ParameterBagInterface $parameters
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('parameter', fn (string $name) => $this->parameters->get($name)),
        ];
    }
}
