<?php
namespace Hypebeast\WordpressBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
 
/*
 * This is called automatically as long as it is in a folder called DependencyInjection in the root 
 * of the bundle and is named BundleNameExtension. 
 */
class HypebeastWordpressExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        // add Authentication Provider and Firewall Listener service to services 
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        // set parameters
        $alias = $this->getAlias();
        $processor     = new Processor();
        $configuration = new Configuration();
        $configs = $processor->processConfiguration($configuration, $configs);

        foreach ($configs as $key => $value) {
            $container->setParameter("{$alias}.{$key}", $value);
        }
    }
 
    public function getAlias()
    {
        return 'hypebeast_wordpress';
    }
}