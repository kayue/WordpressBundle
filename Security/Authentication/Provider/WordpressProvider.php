<?php
namespace Hypebeast\WordpressBundle\Security\Authentication\Provider;

use Hypebeast\WordpressBundle\Security\Authentication\Token\WordpressUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class WordpressProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $loggedInKey;
    private $loggedInSalt;

    public function __construct(UserProviderInterface $userProvider, $loggedInKey, $loggedInSalt)
    {
        $this->userProvider = $userProvider;
        $this->loggedInKey = $loggedInKey;
        $this->loggedInSalt = $loggedInSalt;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());
        
        if ($user && $this->validateCookie($user, $token)) {
            $authenticatedToken = new WordpressUserToken($user->getRoles());
            $authenticatedToken->setUser($user);
            $authenticatedToken->setAuthenticated(true);
            return $authenticatedToken;
        }

        throw new AuthenticationException('The Wordpress authentication failed.');
    }

    // validate Wordpress auth cookie
    private function validateCookie(UserInterface $user, WordpressUserToken $token) 
    {
        // echo $this->site_url;
        $passwordFrag = substr($user->getPassword(), 8, 4);                
        
        // from wp_salt()
        $salt = $this->loggedInKey . $this->loggedInSalt;

        // from wp_hash()
        $key = hash_hmac('md5', $user->getUsername().$passwordFrag.'|'.$token->getExpiration(), $salt);
        $hash = hash_hmac('md5', $user->getUsername() . '|' . $token->getExpiration(), $key);

        return $token->getHmac() === $hash;
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return Boolean true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof WordpressUserToken;
    }
}