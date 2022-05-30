<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle;

use SBSEDV\Bundle\TwigBundle\EventListener\TimezoneEventListener;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class SBSEDVTwigBundle extends AbstractBundle
{
    /**
     * {@inheritdoc}
     */
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definitions/timezone_listener.php');
    }

    /**
     * {@inheritdoc}
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services/extensions.php');

        if ($config['timezone_listener']['enabled']) {
            $container->import('../config/services/timezone_listener.php');

            $container->services()->get(TimezoneEventListener::class)
                ->arg('$cookieName', $config['timezone_listener']['cookie_name'])
                ->arg('$headerName', $config['timezone_listener']['header_name'])
                ->arg('$sessionName', $config['timezone_listener']['session_name'])
            ;
        }
    }
}
