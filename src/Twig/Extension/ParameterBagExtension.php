<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ParameterBagExtension extends AbstractExtension
{
    public function __construct(
        private ParameterBagInterface $parameterBag
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('has_parameter', $this->parameterBag->has(...)),
            new TwigFunction('parameter', $this->parameterBag->get(...)),
        ];
    }
}
