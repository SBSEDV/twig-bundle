<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SBSEDV\Bundle\TwigBundle\EventListener\LocalizationEventListener;
use Symfony\Contracts\Translation\TranslatorInterface;

return function (ContainerConfigurator $container): void {
    $container->services()
        ->set(LocalizationEventListener::class)
            ->args([
                '$twig' => service('twig'),
                '$translator' => service(TranslatorInterface::class),
            ])
            ->tag('kernel.event_subscriber')
    ;
};
