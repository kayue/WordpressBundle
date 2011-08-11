<?php
namespace Hypebeast\WordpressBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class WordpressUserToken extends AbstractToken
{
    public $expiration;
    public $hmac;

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