<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\TwigBundle\EventListener\LocalizationEventListener;
use SBSEDV\Bundle\TwigBundle\EventListener\TimezoneEventListener;
use Symfony\Contracts\Translation\TranslatorInterface;

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

        ->set(LocalizationEventListener::class)
            ->args([
                '$twig' => service('twig'),
                '$translator' => service(TranslatorInterface::class)->nullOnInvalid(),
            ])
            ->tag('kernel.event_subscriber')
    ;
};
