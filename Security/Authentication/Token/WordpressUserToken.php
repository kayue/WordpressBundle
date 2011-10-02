<?php
namespace Hypebeast\WordpressBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;

class WordpressUserToken extends AbstractToken
{
    public $expiration;
    public $hmac;

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

    public function setExpiration($expiration)
    {
        $this->expiration = $expiration;
    }

    public function getExpiration()
    {
        return $this->expiration;
    }

    public function setHmac($hmac)
    {
        $this->hmac = $hmac;
    }

    public function getHmac()
    {
        return $this->hmac;
    }

    public function getCredentials()
    {
        return '';
    }
}