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

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
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
    private function validateCookie(UserInterface $user, TokenInterface $token) 
    {
        $passwordFrag = substr($user->getPassword(), 8, 4);                
        $loggedInKey = 'L2M!?VgvboTS{Q*.j2(<]OBg3l)R~%owdrA5{06y=6HVfc+zsKh5BrTwe{wF&kWi';
        $loggedInSalt = 'Pl&0({w@~{G=r+SReoAtnlAY3U!*-&+V3y-Ib6~W-(HUUdz8-2-F+Qt;eb|N,~o+';
        
        // from wp_salt()
        $salt = $loggedInKey . $loggedInSalt;

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
        return true;
        return $token instanceof WordpressUserToken;
    }
}