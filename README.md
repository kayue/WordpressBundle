TODO
====

1. Able to configure settings in YAML configuration file.
2. Dependency Injection (service.xml)
3. Wordpress User Provider


Installation
============

1. Register the namespace `Hypebeast` to your project's autoloader bootstrap script:

        //app/autoload.php
        $loader->registerNamespaces(array(
              // ...
              'Hypebeast'    => __DIR__.'/../vendor/bundles',
              // ...
        ));

2. Add this bundle to your application's kernel:

        //app/AppKernel.php
        public function registerBundles()
        {
            return array(
                // ...
                new Hypebeast\WordpressBundle\HypebeastWordpressBundle(),
                // ...
            );
        }
3. Add Wordpress Factories and Firewalls to your security.yml:

        factories:
            - "%kernel.root_dir%/../vendor/bundles/Hypebeast/WordpressBundle/Resources/config/security_factories.xml"
        
        firewalls:
            hello:
                pattern:    ^/demo/
                wordpress:  true
                anonymous: ~
                stateless:  true