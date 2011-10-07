<?php
namespace Hypebeast\WordpressBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class WordpressFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.wordpress.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('wordpress.security.authentication.provider'))
        ;

        $listenerId = 'security.authentication.listener.wordpress.'.$id;
        $container->setDefinition($listenerId, new DefinitionDecorator('wordpress.security.authentication.listener'));
        
        if (isset($config['redirect_to_wordpress_on_failure'])) {
            $container->getDefinition($listenerId)
                    ->addArgument($config['redirect_to_wordpress_on_failure']);
        }

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'wordpress';
    }

    public function addConfiguration(NodeDefinition $node)
    {}
}