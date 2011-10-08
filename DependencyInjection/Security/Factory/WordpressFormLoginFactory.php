<?php
namespace Hypebeast\WordpressBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class WordpressFormLoginFactory extends FormLoginFactory
{
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $providerId = 'wordpress.security.authentication.provider.login.' . $id;
        $container->setDefinition($providerId,
                    new DefinitionDecorator('wordpress.security.authentication.provider.login'));

        return $providerId;
    }

    public function getKey()
    {
        return 'wordpress-form-login';
    }
}