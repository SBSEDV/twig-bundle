<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sbsedv_twig');
        $rootNode = $treeBuilder->getRootNode();

        $this->addIamLoginSection($rootNode);
        $this->addCookieConfigSection($rootNode);
        $this->addTimeZoneListenerSection($rootNode);

        return $treeBuilder;
    }

    private function addIamLoginSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('iam_login')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('background_url')
                            ->cannotBeEmpty()
                            ->defaultValue('https://static.sbsedv.de/img/banner/login-background.jpg')
                        ->end()
                        ->arrayNode('stylesheets')
                            ->requiresAtLeastOneElement()
                            ->prototype('scalar')->end()
                            ->defaultValue(['fonts.scss', 'app.scss'])
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addCookieConfigSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('cookie_config')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('cookie_name')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue('cookieconfig')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addTimeZoneListenerSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('timezone_listener')
                    ->treatFalseLike(['enabled' => false])
                    ->treatTrueLike(['enabled' => true])
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->scalarNode('cookie_name')
                            ->cannotBeEmpty()
                            ->defaultValue('timezone')
                        ->end()
                        ->scalarNode('header_name')
                            ->cannotBeEmpty()
                            ->defaultValue('X-Timezone')
                        ->end()
                        ->scalarNode('session_name')
                            ->cannotBeEmpty()
                            ->defaultValue('timezone')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
