TODO
====

1. Able to configure settings in YAML configuration file.
2. Dependency Injection (service.xml)



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

3. Add the following services to your services.xml

        <service id="wordpress.security.authentication.provider" 
                 class="Hypebeast\WordpressBundle\Security\Authentication\Provider\WordpressProvider" 
                 public="false">
            <argument /> <!-- User Provider -->
        </service>

        <service id="wordpress.security.authentication.listener" 
                 class="Hypebeast\WordpressBundle\Security\Firewall\WordpressListener" 
                 public="false">
            <tag name="monolog.logger" channel="security" />
            <argument type="service" id="security.context" />
            <argument type="service" id="security.authentication.manager" />
            <argument type="service" id="logger" on-invalid="null" />
        </service>