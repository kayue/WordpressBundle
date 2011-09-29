<?php
namespace Hypebeast\WordpressBundle\Security\Firewall;

use Hypebeast\WordpressBundle\Security\Authentication\Token\WordpressUserToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Http\HttpUtils;

class WordpressListener implements ListenerInterface
{
    protected $wordpressUrl;
    protected $context;
    protected $authenticationManager;
    protected $logger;

    public function __construct($wordpressUrl, SecurityContextInterface $context, AuthenticationManagerInterface $authenticationManager, LoggerInterface $logger = null)
    {
        $this->wordpressUrl = $wordpressUrl;
        $this->securityContext = $context;
        $this->authenticationManager = $authenticationManager;
        $this->logger  = $logger;
    }

    /**
     * Handles Wordpress user authentication.
     *
     * @param GetResponseEvent $event A GetResponseEvent instance
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        
        try {
            if (null === $returnValue = $this->attemptAuthentication($request)) {
                return;
            }

            if ($returnValue instanceof TokenInterface) {
                $this->securityContext->setToken($returnValue);
            } else {
                throw new \RuntimeException('attemptAuthentication() must either return an implementation of TokenInterface, or null.');
            }
        } catch (AuthenticationException $e) {
            $this->securityContext->setToken(null);
        }
    }

    /**
     * Performs authentication.
     *
     * @param  Request $request A Request instance
     *
     * @return TokenInterface The authenticated token, or null if full authentication is not possible
     *
     * @throws AuthenticationException if the authentication fails
     */
    private function attemptAuthentication(Request $request) 
    {
        $wordpressLoggedInCookie = "wordpress_logged_in_".md5($this->wordpressUrl);

        if(null === $identity = $request->cookies->get($wordpressLoggedInCookie)) {
            if (null !== $this->logger) {
                $this->logger->debug(sprintf('Wordpress identity cookie "%s" not found.', $wordpressLoggedInCookie));
            }

            return null;
        }

        list($username, $expiration, $hmac) = explode('|', $identity);

        $token = new WordpressUserToken();
        $token->setUser($username);
        $token->setExpiration($expiration);
        $token->setHmac($hmac);

        // Authentication manager uses a list of AuthenticationProviderInterface instances 
        // to authenticate a Token.
        return $this->authenticationManager->authenticate($token);
    }
}
