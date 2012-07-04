<?php
namespace Hypebeast\WordpressBundle\Security\Authentication\Token;

use Hypebeast\WordpressBundle\Security\User\WordpressUser;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\User\UserInterface;

class WordpressCookieToken extends AbstractToken
{
    public function __construct(UserInterface $user = null) {
        if ($user instanceof UserInterface) {
            parent::__construct($user->getRoles());
            parent::setAuthenticated(true);

            $this->setUser($user);
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