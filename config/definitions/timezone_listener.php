<?php declare(strict_types=1);

namespace Symfony\Component\Config\Definition\Configurator;

return function (DefinitionConfigurator $definition): void {
    $definition
        ->rootNode()
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
        ->end()
    ;
};
