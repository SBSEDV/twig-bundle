<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\TwigBundle\Twig\Extension\CallStaticExtension;
use SBSEDV\Bundle\TwigBundle\Twig\Extension\CookieConfigExtension;
use SBSEDV\Bundle\TwigBundle\Twig\Extension\InstanceOfExtension;
use SBSEDV\Bundle\TwigBundle\Twig\Extension\ParameterBagExtension;
use SBSEDV\Bundle\TwigBundle\Twig\Extension\PhpFilterExtension;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->defaults()
            ->tag('twig.extension')

        ->set(CallStaticExtension::class)

        ->set(PhpFilterExtension::class)

        ->set(InstanceOfExtension::class)

        ->set(ParameterBagExtension::class)
            ->args([
                '$parameterBag' => service(ParameterBagInterface::class),
            ])

        ->set(CookieConfigExtension::class)
            ->args([
                '$requestStack' => service(RequestStack::class),
                '$cookieName' => abstract_arg('Configured name of the cookie'),
            ])
            ->tag('kernel.reset', ['method' => 'reset'])
    ;
};
