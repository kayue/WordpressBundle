<?php

/**
 * Contains the ApiLoader class
 *
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 * @copyright  Copyright 2011 Miquel Rodríguez Telep / Michael Rodríguez-Torrent
 * @package    Hypebeast\WordpressBundle
 * @subpackage Wordpress
 * @version    0.1
 */

namespace Hypebeast\WordpressBundle\Wordpress;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * ApiLoader loads the Wordpress API functions and configuration
 *
 * @package    Hypebeast\WordpressBundle
 * @subpackage Wordpress
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 * @copyright  Copyright 2011 Miquel Rodríguez Telep / Michael Rodríguez-Torrent
 * @version    0.1
 */
class ApiLoader
{
    /**
     * Path to the Wordpress installation to use
     *
     * @var string
     */
    public $wordpressPath;

    /**
     * Constructor
     *
     * @param string $wordpressPath path to the Wordpress installation to use
     */
    public function __construct($wordpressPath, $shortInit = false)
    {
        $this->wordpressPath = $wordpressPath;

        if(!defined('SHORTINIT')) {
            define('SHORTINIT', $shortInit);
        }
    }
    
    /**
     * Loads a Wordpress configuration and API using the specified bootstrap
     * NB: Wordpress uses a number of variables in the global scope!
     *
     * @param string $bootstrap The filename of the Wordpress bootstrap to use
     * 
     * @throws FileNotFoundException if the bootstrap can't be found
     */
    public function load($bootstrap='wp-load.php')
    {
        if( $this->isWordpressAlreadyLoaded() ) {
            return;
        }

        $bootstrap = $this->wordpressPath . DIRECTORY_SEPARATOR . $bootstrap;

        if (!file_exists($bootstrap)) {
            throw new FileNotFoundException($bootstrap);
        }
        
        $returnValue = require_once $bootstrap;

        // Stop most of WordPress classes and functions from being loaded.
        if(SHORTINIT) {
            require(ABSPATH.WPINC.'/formatting.php');
            require(ABSPATH.WPINC.'/capabilities.php');
            require(ABSPATH.WPINC.'/user.php');
            require(ABSPATH.WPINC.'/meta.php');
            require(ABSPATH.WPINC.'/pluggable.php');
            wp_cookie_constants();

            // If not logged in, load functions like wp_login_url() to 
            // generate login link.
            if(!is_user_logged_in()) {
                require(ABSPATH.WPINC.'/general-template.php');
                require(ABSPATH.WPINC.'/link-template.php');
            }
        }

        foreach (get_defined_vars() as $name => $value) {
            if ($name == 'bootstrap' or $name == 'returnValue') continue;
            $GLOBALS[$name] = $value;
        }

        return $returnValue;
    }

    private function isWordpressAlreadyLoaded()
    {
        $includedFiles = get_included_files();

        return in_array(ABSPATH.WPINC.'/formatting.php', $includedFiles) &&
               in_array(ABSPATH.WPINC.'/capabilities.php', $includedFiles) && 
               in_array(ABSPATH.WPINC.'/user.php', $includedFiles) && 
               in_array(ABSPATH.WPINC.'/meta.php', $includedFiles) && 
               in_array(ABSPATH.WPINC.'/pluggable.php', $includedFiles) &&
               in_array(ABSPATH.WPINC.'/general-template.php', $includedFiles) && 
               in_array(ABSPATH.WPINC.'/link-template.php', $includedFiles);
    }
}