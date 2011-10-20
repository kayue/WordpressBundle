Requirements
============

* WordPress 3.3.0 [revision 18993](https://core.trac.wordpress.org/changeset/18993) or higher
* Symfony 2.0.x

Usage 
=====

Imagine you are in a Controller:

    class DemoController extends Controller
    {
        /**
         * @Route("/hello/{name}", name="_demo_hello")
         * @Template()
         */
        public function helloAction($name)
        {
            // retrieve the current user
            $user = $this->get('security.context')->getToken()->getUser();
            
            // retrieve user #2
            $user = new \WP_User(2);

            return array('username' => $user->user_login);
        }

        // ...
    }

Installation
============

1. Make sure WordPress's cookies are accessible from your Symfony 2 application. To confirm this, 
   open up Symfony's profiler and look for `wordpress_test_cookie` inside the request tab.  
   If you can't find the test cookie in request tab, please try to redefine the cookie path or 
   domain used by WordPress by editing `wp-config.php`.  
   For more information, please [read the WordPress Codex](http://codex.wordpress.org/Editing_wp-config.php)

        // wordpress/wp-config.php

        define('COOKIEPATH', '/' );
        define('COOKIE_DOMAIN', '.yourdomain.com');

2. Register the namespace `Hypebeast` to your project's autoloader bootstrap script:

        // app/autoload.php

        $loader->registerNamespaces(array(
              // ...
              'Hypebeast'    => __DIR__.'/../vendor/bundles',
              // ...
        ));

3. Add this bundle to your application's kernel:

        // app/AppKernel.php

        public function registerBundles()
        {
            return array(
                // ...
                new Hypebeast\WordpressBundle\HypebeastWordpressBundle(),
                // ...
            );
        }

4. Configure the WordPress service in your YAML configuration.
        
        # app/config/config.yml
        
        hypebeast_wordpress:
            wordpress_path: /path/to/your/wordpress

5. Add WordPress factory and firewall to your `security.yml`. Below is a sample configuration. All 
of the options for the wordpress_* authentication methods are optional and are displayed with their 
default values. You can omit them if you use the defaults, e.g. `wordpress_cookie: ~` and 
`wordpress_form_login: ~`

        # app/config/security.yml
        
        security:
            
            # ...
            
            factories:
                - "%kernel.root_dir%/../vendor/bundles/Hypebeast/WordpressBundle/Resources/config/security_factories.xml"

            # There is no user provider, so this is a dummy entry to satisfy the security bundle
            providers:
                wordpress: ~
            
            firewalls:
                secured_area:
                    pattern:    ^/demo/secured/
                    # Set to true if using WordPress's log out rather than Symfony's
                    # stateless:  true
                    wordpress_cookie:
                        # Set to false if you want to use a login form within your Symfony app to 
                        # collect the user's WordPress credentials (see below) or any other
                        # authentication provider. Otherwise, the user will be redirected to your 
                        # WordPress login if they need to authenticate
                        redirect_to_wordpress_on_failure: true

                    # Because this is based on form_login, it accepts all its parameters as well
                    # See the http://symfony.com/doc/2.0/cookbook/security/form_login.html for more 
                    # details. Omit this if using WordPress's built-in login, as above
                    wordpress_form_login:
                        # This is the name of the POST parameter that can be used to indicate 
                        # whether the user should be remembered via WordPress's remember-me cookie
                        remember_me_parameter: _remember_me

                    # You want your users to be able to log out, right? See Symfony docs for options
                    logout: ~

                    # anonymous:  ~
                    
                # ...

Caveats
=======

* Because Symfony tracks the user's authentication state independently of WordPress, if the 
  stateless is not set to true (see above) and the user logs out in WordPress, they will not be 
  logged out of Symfony until they specifically do, or they end their session. To prevent this, you 
  should use either Symfony's or WordPress's logout methods exclusively.
* WordPress assumes it will be run in the global scope, so some of its code doesn't even bother 
  explicitly globalising variables. The required version of WordPress core marginally improves this 
  situation (enough to allow us to integrate with it), but beware that other parts of WordPress or 
  plugins may still have related issues.
* There is currently no user provider (use the API abstraction, see example above)
* Authentication errors from WordPress are passed through unchanged and, since WordPress uses HTML 
  in its errors, the user may see HTML tags