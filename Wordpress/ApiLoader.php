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
    public $wordpress_path;

    /**
     * Constructor
     *
     * @param string $wordpress_path path to the Wordpress installation to use
     */
    public function __construct($wordpress_path)
    {
        $this->wordpress_path = $wordpress_path;
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
        $bootstrap = $this->wordpress_path . DIRECTORY_SEPARATOR . $bootstrap;
        if (!file_exists($bootstrap)) {
            throw new FileNotFoundException($bootstrap);
        }
        
        // Stop most of WordPress classes and functions from being loaded.
        define('SHORTINIT', true);

        $returnValue = require_once $bootstrap;

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

        foreach (get_defined_vars() as $name => $value) {
            if ($name == 'bootstrap' or $name == 'returnValue') continue;
            $GLOBALS[$name] = $value;
        }
        
        return $returnValue;
    }

}
