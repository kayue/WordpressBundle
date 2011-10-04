<?php

/**
 * Contains the ApiAbstraction class
 *
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 * @copyright  Copyright 2011 Miquel Rodríguez Telep / Michael Rodríguez-Torrent
 * @package    Hypebeast\WordpressBundle
 * @subpackage Wordpress
 * @version    0.1
 */

namespace Hypebeast\WordpressBundle\Wordpress;

/**
 * ApiAbstraction provides an abstraction layer for Wordpress API calls
 *
 * @package    Hypebeast\WordpressBundle
 * @subpackage Wordpress
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 * @copyright  Copyright 2011 Miquel Rodríguez Telep / Michael Rodríguez-Torrent
 * @version    0.1
 */
class ApiAbstraction
{

    /**
     * Constructor. Loads the Wordpress API through ApiLoader
     *
     * @param ApiLoader $apiLoader 
     */
    public function __construct(ApiLoader $apiLoader)
    {
        $apiLoader->load();
    }
    
    public function __call($function, $arguments)
    {
        return call_user_func_array($function, $arguments);
    }

}