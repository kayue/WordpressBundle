<?php
namespace Hypebeast\WordpressBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class WordpressCookieToken extends AbstractToken
{

    public function __construct(array $roles = array()) {
        parent::__construct($roles);
        parent::setAuthenticated(count($roles) > 0);
    }

    public function setAuthenticated($isAuthenticated)
    {
        if ($isAuthenticated) {
            throw new \LogicException(
                    'Cannot set this token to trusted after instantiation.');
        }

        parent::setAuthenticated(false);
    }

    public function getCredentials()
    {
        return '';
    }
}