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
     * Path to the WordPress installation to use.
     *
     * @var string
     */
    public $wordpressPath;

    /**
     * WordPress site's domain.
     *
     * @var string
     */
    private $domain;

    /**
     * HTTP Host
     *
     * @var string
     */
    private $httpHost;

    /**
     * Constructor
     *
     * @param string $wordpressPath path to the WordPress installation to use.
     * @param string $domain WordPress site's domain.
     */
    public function __construct($wordpressPath, $shortInit = false, $domain = null)
    {
        $this->wordpressPath = $wordpressPath;
        $this->domain        = $domain;
        $this->httpHost      = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;

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
        // No need to load Wordpress again if it is already loaded.
        if( $this->isWordpressAlreadyLoaded($bootstrap) ) {
            return;
        }

        // Modify HTTP_HOST to fix WordPress multi-site's redirection bug.
        if($this->domain) {
            $_SERVER['HTTP_HOST'] = $this->domain;
        }

        $bootstrap = $this->wordpressPath . DIRECTORY_SEPARATOR . $bootstrap;

        if (!file_exists($bootstrap)) {
            throw new FileNotFoundException($bootstrap);
        }

        // Work around WordPress's `$wp_rewrite` global. Fixes #2.
        // Need $wpdb for ms-settings.php.
        global $wp_rewrite, $wpdb;

        $returnValue = require_once $bootstrap;

        // Stop most of WordPress classes and functions from being loaded.
        if(SHORTINIT) {
            // Load the L10n library.
            require(ABSPATH.WPINC.'/l10n.php');

            // Load most of WordPress.
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

        // Work around WordPress not explicitly globalising variables. See #5
        foreach (get_defined_vars() as $name => $value) {
            if ($name == 'bootstrap' or $name == 'returnValue') continue;
            $GLOBALS[$name] = $value;
        }

        // Change back the HTTP_HOST to the original one.
        if($this->domain) {
            $_SERVER['HTTP_HOST'] = $this->httpHost;
        }

        return $returnValue;
    }

    /**
     * Check to see if Wordpress is already loaded.
     */
    private function isWordpressAlreadyLoaded($bootstrap)
    {
        // ABSPATH and WPINC must be already defined if Wordpress is loaded
        if(!defined('ABSPATH') || !defined('WPINC')) {
            return false;
        }

        // Extra checking.
        return in_array($bootstrap, get_included_files());
    }
}