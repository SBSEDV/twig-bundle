<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\DependencyInjection;

use SBSEDV\Bundle\TwigBundle\Twig\Extension\CookieConfigExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SBSEDVTwigExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $cookieConfig = $container->getDefinition(CookieConfigExtension::class);
        $cookieConfig->replaceArgument('$config', $config['cookie_config']);
    }
}
