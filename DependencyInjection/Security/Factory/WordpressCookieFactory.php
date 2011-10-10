<?php
namespace Hypebeast\WordpressBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class WordpressCookieFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'wordpress.security.authentication.provider.cookie.' . $id;
        $container->setDefinition($providerId,
                    new DefinitionDecorator('wordpress.security.authentication.provider.cookie'));

        $listenerId = 'wordpress.security.authentication.listener.cookie.' . $id;
        $container->setDefinition($listenerId,
                    new DefinitionDecorator('wordpress.security.authentication.listener.cookie'))
                ->addArgument($config['redirect_to_wordpress_on_failure']);

        # If the application does logouts, add our handler to log the user out of Wordpress, too
        if ($container->hasDefinition('security.logout_listener.'.$id)) {
            $logoutListener = $container->getDefinition('security.logout_listener.'.$id);
            $addHandlerArguments = array(new Reference('wordpress.security.http.logout.' . $id));
            
            # Don't add the handler again if it has already been added by another factory
            if (!in_array(array('addHandler', $addHandlerArguments),
                    $logoutListener->getMethodCalls())) {
                
                $container->setDefinition('wordpress.security.http.logout.' . $id,
                            new DefinitionDecorator('wordpress.security.http.logout'));
                $logoutListener->addMethodCall('addHandler', $addHandlerArguments);
            }
        }

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'remember_me';
    }

    public function getKey()
    {
        return 'wordpress-cookie';
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->booleanNode('redirect_to_wordpress_on_failure')->defaultValue(true)->end()
            ->end()
        ;
    }
}