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
                ->scalarNode('wordpress_url')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('logged_in_key')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('logged_in_salt')->isRequired()->cannotBeEmpty()->end()
            ->end()
        ;

        return $builder;
    }
}