To-do
=====

1. Wordpress user meta model.
2. Complete getRoles() method in User model.

Installation
============

1. Register the namespace `Hypebeast` to your project's autoloader bootstrap script:

        // app/autoload.php

        $loader->registerNamespaces(array(
              // ...
              'Hypebeast'    => __DIR__.'/../vendor/bundles',
              // ...
        ));

2. Add this bundle to your application's kernel:

        // app/AppKernel.php

        public function registerBundles()
        {
            return array(
                // ...
                new Hypebeast\WordpressBundle\HypebeastWordpressBundle(),
                // ...
            );
        }

3. Configure the Wordpress service in your YAML configuration. Logged in Key and Salt can be found in `wp-config.php`.
        
        # app/config/config.yml
        
        hypebeast_wordpress:
            wordpress_url: 'http://127.0.0.1/wordpress'
            logged_in_key: '4f^PtWnv8T2{O#;Ms8z2-`:/PU^=c4~$?]iAZ-n`%=`>P*d):$)@l(GyvV,Cg3y!'
            logged_in_salt: '`!`DUD!E1>IiJQs;$Ax=>2$e@m+oIr),j bY}X!o$>i#>xx#Z7#UrB.);2|&T+4*'

4. Add Wordpress Factories, Firewalls and User Provider to your `security.yml`:

        # app/config/security.yml
        
        security:
            
            providers:
                wordpress:
                    entity: { class: Hypebeast\WordpressBundle\Entity\User, property: username }
            
            factories:
                - "%kernel.root_dir%/../vendor/bundles/Hypebeast/WordpressBundle/Resources/config/security_factories.xml"
            
            firewalls:
                hello:
                    pattern:    ^/demo/
                    wordpress:  true
                    anonymous:  ~
                    stateless:  true