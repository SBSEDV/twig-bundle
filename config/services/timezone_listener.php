<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\TwigBundle\EventListener\TimezoneEventListener;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set(TimezoneEventListener::class)
            ->args([
                '$cookieName' => abstract_arg('Name of the cookie to look for'),
                '$headerName' => abstract_arg('Name of the header to look for'),
                '$sessionName' => abstract_arg('Name of the session attribute to look for'),
                '$twig' => service('twig'),
            ])
            ->tag('kernel.event_subscriber')
    ;
};
