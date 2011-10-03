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
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy;

class WordpressListener extends AbstractAuthenticationListener
{
    protected $wordpressUrl;
    protected $context;
    protected $authenticationManager;
    protected $logger;

    public function __construct($wordpressUrl, SecurityContextInterface $securityContext,
            AuthenticationManagerInterface $authenticationManager, HttpUtils $httpUtils,
            LoggerInterface $logger=null)
    {
        parent::__construct($securityContext, $authenticationManager,
                new SessionAuthenticationStrategy(SessionAuthenticationStrategy::NONE), $httpUtils,
                'wordpress'
        );
        
        $this->wordpressUrl = $wordpressUrl;
        $this->logger = $logger;
    }

    protected function attemptAuthentication(Request $request) 
    {
        $this->options['failure_path']
                = $this->wordpressUrl . '/wp-login.php?redirect_to=' . $request->getUri();
        
        $wordpressLoggedInCookie = 'wordpress_logged_in_' . md5($this->wordpressUrl);
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
