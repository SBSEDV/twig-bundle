<?php declare(strict_types=1);

namespace Symfony\Component\Config\Definition\Configurator;

return function (DefinitionConfigurator $definition): void {
    $definition // @phpstan-ignore method.notFound
        ->rootNode()
            ->children()
                ->arrayNode('cookie_config_extension')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('cookie_name')
                            ->cannotBeEmpty()
                            ->defaultValue('cookieconfig')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
    ;
};
