<?php

namespace Mabe\RateLimitBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mabe_rate_limit');

        $rootNode
            ->children()
                ->scalarNode('enabled')->defaultValue(true)->end()
                ->arrayNode('redis')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('host')->defaultValue('127.0.0.1')->end()
                        ->scalarNode('port')->defaultValue('6379')->end()
                        ->scalarNode('database')->defaultValue('1')->end()
                    ->end()
                ->end()
                ->arrayNode('paths')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('path')->defaultNull()->end()
                            ->integerNode('limit')->defaultNull()->end()
                            ->integerNode('period')->defaultNull()->end()
                            ->scalarNode('identifier')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
