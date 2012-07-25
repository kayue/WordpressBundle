<?php

namespace Hypebeast\WordpressBundle\Extensions;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Hypebeast\WordpressBundle\Entity\Post;

/**
 * Twig extension for accessing Wordpress template function in Twig
 * through a global object in Twig.
 *
 * @author Ka-Yue Yeung <kayuey@gmail.com>
 */
class WordpressTwigExtension extends \Twig_Extension
{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'wordpress';
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return array(
            'wp' => &$this
        );
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return array(
            'wpautop' => new \Twig_Filter_Method(&$this, 'wpautop'),
        );
    }

    public function __call($function, $arguments)
    {
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
    private function camelcaseToUnderscore($name)
    {
        return preg_replace('/([a-z])([A-Z])/', '$1_$2', $name);
    }

    /**
     * Replaces double line-breaks with paragraph elements.
     *
     * A group of regex replaces used to identify text formatted with newlines and
     * replace double line-breaks with HTML paragraph tags. The remaining
     * line-breaks after conversion become <<br />> tags, unless $br is set to '0'
     * or 'false'.
     *
     * @param string $pee The text which has to be formatted.
     * @param bool $br Optional. If set, this will convert all remaining line-breaks after paragraphing. Default true.
     * @return string Text which has been converted into correct paragraph tags.
     */
    public function wpautop($pee, $br = true)
    {
        $pre_tags = array();

        if ( trim($pee) === '' )
            return '';

        $pee = $pee . "\n"; // just to make things a little easier, pad the end

        if ( strpos($pee, '<pre') !== false ) {
            $pee_parts = explode( '</pre>', $pee );
            $last_pee = array_pop($pee_parts);
            $pee = '';
            $i = 0;

            foreach ( $pee_parts as $pee_part ) {
                $start = strpos($pee_part, '<pre');

                // Malformed html?
                if ( $start === false ) {
                    $pee .= $pee_part;
                    continue;
                }

                $name = "<pre wp-pre-tag-$i></pre>";
                $pre_tags[$name] = substr( $pee_part, $start ) . '</pre>';

                $pee .= substr( $pee_part, 0, $start ) . $name;
                $i++;
            }

            $pee .= $last_pee;
        }

        $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
        // Space things out a little
        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|option|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';
        $pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
        $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
        $pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines
        if ( strpos($pee, '<object') !== false ) {
            $pee = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $pee); // no pee inside object/embed
            $pee = preg_replace('|\s*</embed>\s*|', '</embed>', $pee);
        }
        $pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
        // make paragraphs, including one at the end
        $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
        $pee = '';
        foreach ( $pees as $tinkle )
            $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
        $pee = preg_replace('|<p>\s*</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
        $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
        $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
        $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
        $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
        if ( $br ) {
            $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', function($matches) {
                // newline preservation help function for wpautop
                return str_replace("\n", "<WPPreserveNewline />", $matches[0]);
            }, $pee);
            $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
            $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
        }
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
        $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
        $pee = preg_replace( "|\n</p>$|", '</p>', $pee );

        if ( !empty($pre_tags) )
            $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);

        return $pee;
    }

    public function getThumbnail(Post $post, $size = 'thumbnail', $keepRatio = false)
    {
        if($size === 'thumbnail') {
            $size = array(300, 200);
        }

        $metas = $post->getMetasByKey('_thumbnail_id');

        // return null if no thumbnail set
        if($metas->isEmpty()) {
            return null;
        }

        $thumbnail = $this->doctrine->getRepository('HypebeastWordpressBundle:Post')->find($metas->first()->getValue());
        $basename = $this->basename($thumbnail->getGuid());
        $nearestSize = $this->getNearestSize($thumbnail, $size, $keepRatio);

        return str_replace($basename, $nearestSize['file'], $thumbnail->getGuid());
    }

    /**
     * i18n friendly version of basename()
     *
     * @param string $path A path.
     * @param string $suffix If the filename ends in suffix this will also be cut off.
     * @return string
     */
    private function basename($path, $suffix = '') {
        return urldecode(basename( str_replace( '%2F', '/', urlencode( $path ) ), $suffix ) );
    }

    private function getNearestSize($attachment, $target = array(300, 200), $keepRatio = false)
    {
        // TODO: Check attachment is an image
        $allSizes = $this->getAllSizes($attachment);
        $nearest = null;
        $nearestKey = null;

        list($x1, $y1) = $target;

        do {
            foreach ($allSizes as $key => $size) {
                $x2 = $size['width'];
                $y2 = $size['height'];

                $distance = sqrt(pow($x1 - $x2, 2) + pow($y1 - $y2, 2));

                if(!$nearest || $distance < $nearest) {
                    if (!$keepRatio || (abs($y2 / $x2 - $y1 / $x1) < 0.00001)){
                        $nearest = $distance;
                        $nearestKey = $key;
                    }
                }
            }
            $keepRatio = false;
        } while ($nearestKey !== null);

        return $allSizes[$nearestKey];
    }

    private function getAllSizes($attachment)
    {
        $metadata = $attachment->getMetasByKey('_wp_attachment_metadata');

        if($metadata->isEmpty()) {
            return null;
        }

        $metadata = $metadata->first()->getValue();

        $originalSize = array(
            'width'  => $metadata['width'],
            'height' => $metadata['height'],
            'file'   => $this->basename($attachment->getGuid()),
        );

        $allSizes = $metadata['sizes'];
        $allSizes['original'] = $originalSize;

        return $allSizes;
    }
}