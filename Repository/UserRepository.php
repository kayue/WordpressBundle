<?php
namespace Hypebeast\WordpressBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * Join User Metas to User object when User Provider find user by username. 
     * Since we know up front we will need to access both objects, we can avoid the second query
     * query by issuing a join in the original query. 
     *
     * @param array $criteria
     * @return object
     */
    public function findOneBy(array $criteria)
    {
        if(array_key_exists('username',$criteria)) {
            return $this->_em
                        ->createQuery('SELECT user, meta 
                                       FROM HypebeastWordpressBundle:User user
                                       JOIN user.metas meta WHERE user.username = :username')
                        ->setParameter('username', $criteria['username'])
                        ->getSingleResult();
        }
        
        return parent::findOneBy($criteria);
    }
}