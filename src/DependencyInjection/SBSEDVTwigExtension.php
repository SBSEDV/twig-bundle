<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\DependencyInjection;

use SBSEDV\Bundle\TwigBundle\EventListener\TimezoneListener;
use SBSEDV\Bundle\TwigBundle\Twig\Extension\CookieConfigExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class SBSEDVTwigExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $cookieConfig = $container->getDefinition(CookieConfigExtension::class);
        $cookieConfig->replaceArgument('$cookieName', $config['cookie_config']['cookie_name']);

        $this->confiugureTimezoneListener($container, $config);
    }

    /**
     * Configure the "SBSEDV\Bundle\TwigBundle\EventListener\TimezoneListener" service.
     */
    private function confiugureTimezoneListener(ContainerBuilder $container, array $config): void
    {
        if (@$config['timezone_listener']['enabled'] !== true) {
            return;
        }

        $definition = new Definition(TimezoneListener::class);
        $definition->setArgument('$cookieName', $config['timezone_listener']['cookie_name']);
        $definition->setArgument('$headerName', $config['timezone_listener']['header_name']);
        $definition->setArgument('$sessionName', $config['timezone_listener']['session_name']);
        $definition->setArgument('$twig', new Reference('twig'));
        $definition->addTag('kernel.event_subscriber');

        $container->setDefinition(TimezoneListener::class, $definition);
    }
}
