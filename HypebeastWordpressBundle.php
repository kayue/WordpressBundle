<?php

namespace Hypebeast\WordpressBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Hypebeast\WordpressBundle\Types\WordPressIdType;

class HypebeastWordpressBundle extends Bundle
{
    public function boot()
    {
        parent::boot();

        // add a custom database type for WordPress's ID columns
        if (!Type::hasType(WordPressIdType::NAME)) {
            Type::addType(WordPressIdType::NAME, 'Hypebeast\WordpressBundle\Types\WordPressIdType');
        }
    }
}