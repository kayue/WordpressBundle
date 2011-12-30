<?php
namespace Hypebeast\WordpressBundle\DependencyInjection;
 
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration for the bundle
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Build and return a config tree.
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
 
        $builder->root('hypebeast_wordpress')
            ->children()
                ->scalarNode('wordpress_path')->isRequired()->cannotBeEmpty()->end()
                ->booleanNode('short_init')->defaultValue(false)->end()
                ->booleanNode('debug')->defaultValue(false)->end()
            ->end()
        ;

        return $builder;
    }
}