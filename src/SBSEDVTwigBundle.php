<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle;

use SBSEDV\Bundle\TwigBundle\EventListener\TimezoneEventListener;
use SBSEDV\Bundle\TwigBundle\Twig\Extension\CookieConfigExtension;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Contracts\Translation\TranslatorInterface;

class SBSEDVTwigBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definitions/*.php');
    }

    /**
     * @param array{
     *      timezone_listener: array{
     *          enabled: bool,
     *          cookie_name: string,
     *          header_name: string,
     *          session_name: string
     *      },
     *      localization_listener: array{
     *          enabled: bool
     *      },
     *      cookie_config_extension: array{
     *          cookie_name: string
     *      }
     * } $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services/extensions.php');

        if ($config['timezone_listener']['enabled']) {
            $container->import('../config/services/timezone_event_listener.php');

            $container->services()->get(TimezoneEventListener::class)
                ->arg('$cookieName', $config['timezone_listener']['cookie_name'])
                ->arg('$headerName', $config['timezone_listener']['header_name'])
                ->arg('$sessionName', $config['timezone_listener']['session_name'])
            ;
        }

        if ($builder->hasDefinition(TranslatorInterface::class) && $config['localization_listener']['enabled']) {
            $container->import('../config/services/localization_event_listener.php');
        }

        $builder->getDefinition(CookieConfigExtension::class)
            ->replaceArgument('$cookieName', $config['cookie_config_extension']['cookie_name'])
        ;
    }
}
