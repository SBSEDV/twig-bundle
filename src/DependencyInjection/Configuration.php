<?php declare(strict_types=1);

namespace SBSEDV\Bundle\TwigBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('sbsedv_twig');

        $treeBuilder->getRootNode()
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
                ->arrayNode('TimezoneListener')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')
                            ->defaultValue(true)
                        ->end()
                        ->integerNode('priority')
                            ->defaultValue(100)
                        ->end()
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
                ->end() // TimeZoneListener
            ->end()
        ;

        return $treeBuilder;
    }
}
