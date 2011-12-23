<?php

namespace Hypebeast\WordpressBundle\Extensions;

/**
 * Twig extension for accessing Wordpress template function in Twig
 * through a global object in Twig.
 *
 * @author Ka-Yue Yeung <kayuey@gmail.com>
 */
class WordpressTwigExtension extends \Twig_Extension 
{
    public function getName()
    {
        return 'wordpress';
    }

    public function getGlobals()
    {
        return array(
            'wp' => $this
        );
    }

    public function __call($function, $arguments) {
        $function = $this->camelcaseToUnderscore($function);

        if (!function_exists($function)) {
            trigger_error("Call to unexist Wordpress function \"{$function}\"", E_USER_WARNING);
            return null;
        }

        return call_user_func_array($function, $arguments);
    }

    /** 
     * Convert function name from camolcasing to underscoring.
     *
     * @param String $name The function name.
     */
    private function camelcaseToUnderscore($name) {
        return preg_replace('/([a-z])([A-Z])/', '$1_$2', $name);
    }
}