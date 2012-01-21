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

        // Since a lot of Wordpress plugins are poorly written, we recommend you
        // turn of PHP's runtime notice when debugging.
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