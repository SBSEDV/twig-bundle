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

        $this->addCookieConfigSection($rootNode);
        $this->addTimeZoneEventListenerSection($rootNode);

        return $treeBuilder;
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

    private function addTimeZoneEventListenerSection(ArrayNodeDefinition $rootNode): void
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
