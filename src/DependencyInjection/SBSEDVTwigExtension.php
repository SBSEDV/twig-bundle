<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\DependencyInjection;

use SBSEDV\Bundle\TwigBundle\EventListener\TimezoneEventListener;
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

        $container
            ->getDefinition(CookieConfigExtension::class)
            ->replaceArgument('$cookieName', $config['cookie_config']['cookie_name'])
        ;

        $this->confiugureTimezoneEventListener($container, $config);
    }

    /**
     * Configure the "SBSEDV\Bundle\TwigBundle\EventListener\TimezoneEventListener" service.
     */
    private function confiugureTimezoneEventListener(ContainerBuilder $container, array $config): void
    {
        if (@$config['timezone_listener']['enabled'] !== true) {
            return;
        }

        $container
            ->setDefinition(TimezoneEventListener::class, new Definition(TimezoneEventListener::class))
            ->setArguments([
                '$cookieName' => $config['timezone_listener']['cookie_name'],
                '$headerName' => $config['timezone_listener']['header_name'],
                '$sessionName' => $config['timezone_listener']['session_name'],
                '$twig' => new Reference('twig'),
            ])
            ->addTag('kernel.event_subscriber')
        ;
    }
}
