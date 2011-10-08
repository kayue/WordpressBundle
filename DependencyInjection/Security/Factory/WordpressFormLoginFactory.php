<?php
namespace Hypebeast\WordpressBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class WordpressFormLoginFactory extends FormLoginFactory
{
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $providerId = 'wordpress.security.authentication.provider.login.' . $id;
        $container->setDefinition($providerId,
                    new DefinitionDecorator('wordpress.security.authentication.provider.login'))
                ->replaceArgument(1, $config['remember_me_parameter']);

        return $providerId;
    }
    
    protected function isRememberMeAware($config)
    {   
        return false;
    }

    public function addConfiguration(NodeDefinition $node)
    {
        parent::addConfiguration($node);
        
        $node
            ->children()
                ->scalarNode('remember_me_parameter')
                    ->defaultValue('_remember_me')->cannotBeEmpty()
                ->end()
            ->end()
        ;
    }

    public function getKey()
    {
        return 'wordpress-form-login';
    }
}