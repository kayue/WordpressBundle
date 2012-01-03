<?php

namespace Hypebeast\WordpressBundle\Wordpress;

class Helper
{
    /**
     * An abstraction layer for the Wordpress API
     *
     * @var ApiAbstraction
     */
    protected $api;

    /**
     * Constructor
     *
     * @param ApiAbstraction $api 
     */
    public function __construct(ApiAbstraction $api)
    {
        $this->api = $api;
    }

    /**
     * Set the title of the page.
     *
     * @param String $title 
     */
    public function setTitle($title)
    {
        $this->api->add_filter('wp_title', function($title, $sep, $seplocation) use ($title) {
            $t_sep = '%WP_TITILE_SEP%'; // Temporary separator, for accurate flipping, if necessary

            if ( !empty($title) )
                $prefix = " $sep ";

            // Determines position of the separator and direction of the breadcrumb
            if ( 'right' == $seplocation ) { // sep on right, so reverse the order
                $title_array = explode( $t_sep, $title );
                $title_array = array_reverse( $title_array );
                $title = implode( " $sep ", $title_array ) . $prefix;
            } else {
                $title_array = explode( $t_sep, $title );
                $title = $prefix . implode( " $sep ", $title_array );
            }

            return $title;
        });
    }

    /**
     * Set body class of the page.
     *
     * @param String $class
     */
    public function setBodyClass($class)
    {
        $this->api->add_filter('body_class', function($classes) use ($class) {
            $classes[] = $class;
            return $classes;
        });
    }
}