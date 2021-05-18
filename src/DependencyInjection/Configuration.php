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
            ->end()
        ;

        return $treeBuilder;
    }
}
