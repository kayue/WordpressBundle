<?php

namespace Hypebeast\WordpressBundle;

use Doctrine\DBAL\Types\Type;
use Hypebeast\WordpressBundle\DependencyInjection\Security\Factory\WordpressCookieFactory;
use Hypebeast\WordpressBundle\DependencyInjection\Security\Factory\WordpressFormLoginFactory;
use Hypebeast\WordpressBundle\Types\WordPressIdType;
use Hypebeast\WordpressBundle\Types\WordPressMetaType;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HypebeastWordpressBundle extends Bundle
{
    public function boot()
    {
        parent::boot();

        // add a custom database type for WordPress's ID columns
        if (!Type::hasType(WordPressIdType::NAME)) {
            Type::addType(WordPressIdType::NAME, 'Hypebeast\WordpressBundle\Types\WordPressIdType');
        }

        if (!Type::hasType(WordPressMetaType::NAME)) {
            Type::addType(WordPressMetaType::NAME, 'Hypebeast\WordpressBundle\Types\WordPressMetaType');
        }
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new WordpressCookieFactory());
        $extension->addSecurityListenerFactory(new WordpressFormLoginFactory());
    }
}