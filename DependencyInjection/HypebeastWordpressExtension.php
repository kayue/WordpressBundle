<?php
namespace Hypebeast\WordpressBundle\DependencyInjection;
 
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
 
/*
 * This is called automatically as long as it is in a folder called DependencyInjection in the root 
 * of the bundle and is named BundleNameExtension. 
 */
class HypebeastWordpressExtension extends Extension
{
 
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');
    }
 
    public function getAlias()
    {
        return 'hypebeast_wordpress';
    }
 
 
}