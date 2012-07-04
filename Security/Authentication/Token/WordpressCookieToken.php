<?php
namespace Hypebeast\WordpressBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Hypebeast\WordpressBundle\Security\User\WordpressUser;

class WordpressCookieToken extends AbstractToken
{
    public function __construct(WordpressUser $user = null) {
        if ($user instanceof WordpressUser) {
            parent::__construct($user->getRoles());
            $this->setUser($user);
            parent::setAuthenticated(true);

        } else {
            parent::__construct();
        }
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