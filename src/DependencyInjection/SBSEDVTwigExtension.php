<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\DependencyInjection;

use SBSEDV\Bundle\TwigBundle\EventListener\TimezoneListener;
use SBSEDV\Bundle\TwigBundle\Twig\Extension\CookieConfigExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\KernelEvents;

class SBSEDVTwigExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $cookieConfig = $container->getDefinition(CookieConfigExtension::class);
        $cookieConfig->replaceArgument('$cookieName', $config['cookie_config']['cookie_name']);

        $container->setParameter('sbsedv_twig.iam_login.background_url', $config['iam_login']['background_url']);
        $container->setParameter('sbsedv_twig.iam_login.stylesheets', array_values($config['iam_login']['stylesheets'] ?? []));

        $this->handleTimeZoneListener($container, $config);
    }

    private function handleTimeZoneListener(ContainerBuilder $container, array $config): void
    {
        if (@$config['timezone_listener']['enabled'] !== true) {
            return;
        }

        $container->getDefinition(TimezoneListener::class)
            ->addTag('kernel.event_listener', [
                'event' => KernelEvents::REQUEST,
                'method' => 'onKernelRequest',
                'priority' => $config['timezone_listener']['priority'],
            ])
            ->replaceArgument('$cookieName', $config['timezone_listener']['cookie_name'])
            ->replaceArgument('$headerName', $config['timezone_listener']['header_name'])
            ->replaceArgument('$sessionName', $config['timezone_listener']['session_name']);
    }
}
