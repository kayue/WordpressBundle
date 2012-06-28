<?php

/**
 * Prefixing WordPress table
 *
 * Provide a Table Prefix option for the bundle's entities.
 */

namespace Hypebeast\WordpressBundle\Subscriber;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\EventSubscriber;

class TablePrefixSubscriber implements EventSubscriber
{
    protected $prefix = '';

    public function __construct($prefix)
    {
        $this->prefix = (string) $prefix;
    }

    public function getSubscribedEvents()
    {
        return array('loadClassMetadata');
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        // do not apply prefix if not a WordpressBundle Entitiy
        if($args->getClassMetadata()->namespace !== "Hypebeast\WordpressBundle\Entity") {
            return;
        }

        $classMetadata = $args->getClassMetadata();
        $prefix = $this->getTablePrefix($args);

        $classMetadata->setTableName($prefix . $classMetadata->getTableName());

        foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
            if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY) {
                $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $prefix . $mappedTableName;
            }
        }
    }

    private function getTablePrefix(LoadClassMetadataEventArgs $args)
    {
        $prefix = $this->prefix;

        // append blog id to prefix, if needed.
        if( method_exists($args->getEntityManager()->getMetadataFactory(), 'getBlogId') &&
            $args->getClassMetadata()->name !== "Hypebeast\WordpressBundle\Entity\User" &&
            $args->getClassMetadata()->name !== "Hypebeast\WordpressBundle\Entity\UserMeta") {
            $prefix .= $args->getEntityManager()->getMetadataFactory()->getBlogId().'_';
        }

        return $prefix;
    }
}