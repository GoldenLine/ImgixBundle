<?php

namespace GoldenLine\ImgixBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('goldenline_imgix');

        $rootNode
            ->children()
            ->scalarNode('default_source')
                ->defaultValue('default')
            ->end()
                ->append($this->getSourcesNode())
            ->end()
        ;

        return $treeBuilder;
    }

    private function getSourcesNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('sources');

        $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->arrayNode('domains')
                        ->isRequired()
                        ->requiresAtLeastOneElement()
                        ->prototype('scalar')
                        ->end()
                    ->end()
                    ->scalarNode('sign_key')
                        ->defaultValue('')
                    ->end()
                    ->scalarNode('shard_strategy')
                        ->defaultValue('crc')
                        ->validate()
                        ->ifNotInArray(array('crc', 'cycle'))
                            ->thenInvalid('Invalid shard strategy "%s"')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
