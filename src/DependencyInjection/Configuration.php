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
                ->arrayNode('tags')->prototype('scalar')->end()->end()
                ->arrayNode('og')->prototype('scalar')->end()->end()
                ->arrayNode('tw')->prototype('scalar')->end()->end()
                ->booleanNode('auto_url')->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
