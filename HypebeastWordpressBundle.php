<?php

namespace Hypebeast\WordpressBundle;

use Doctrine\DBAL\Types\Type;
use Hypebeast\WordpressBundle\Types\WordPressIdType;
use Hypebeast\WordpressBundle\Types\WordPressMetaType;
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
}