<?php

/**
 * Contains the RoleUtilities class, part of the Symfony Wordpress Bundle
 *
 * @package    Hypebeast\WordpressBundle
 * @subpackage Utilities
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 */

namespace Hypebeast\WordpressBundle\Utilities;

/**
 * RoleUtilities provides utility methods for manipulating roles
 *
 * @package    Hypebeast\WordpressBundle
 * @subpackage Utilities
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 */
abstract class RoleUtilities
{

    /**
     * Normalises role names to upper case with the specified prefix and suffix
     *
     * @param array $roles the role names to normalise
     * @param type $prefix defaults to 'ROLE_WP_'
     * @param type $suffix defaults to nothing ('')
     * 
     * @return string[] the transformed role names
     */
    static public function normalise_role_names(array $roles, $prefix = 'ROLE_WP_', $suffix = '')
    {
        return array_map(
                function ($role) use ($prefix, $suffix)
                {
                    return strtoupper($prefix . $role . $suffix);
                },
                $roles
        );
    }

}