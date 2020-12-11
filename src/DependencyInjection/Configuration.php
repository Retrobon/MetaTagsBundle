<?php

namespace retrobon\MetaTagsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('meta_tags');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('tags')
                    ->prototype('scalar')->end()
                ->end()
                ->booleanNode('og')->end()
                ->booleanNode('auto_url')->end()
                ->booleanNode('tw')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
