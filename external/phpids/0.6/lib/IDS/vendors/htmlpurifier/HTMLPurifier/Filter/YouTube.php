<?php

class HTMLPurifier_Filter_YouTube extends HTMLPurifier_Filter
{
    
    public $name = 'YouTube';
    
    public function preFilter($html, $config, $context) {
        $pre_regex = '#<object[^>]+>.+?'.
            'http://www.youtube.com/v/([A-Za-z0-9\-_]+).+?</object>#s';
        $pre_replace = '<span class="youtube-embed">\1</span>';
        return preg_replace($pre_regex, $pre_replace, $html);
    }
    
    public function postFilter($html, $config, $context) {
        $post_regex = '#<span class="youtube-embed">([A-Za-z0-9\-_]+)</span>#';
        $post_replace = '<object width="425" height="350" '.
            'data="http://www.youtube.com/v/\1">'.
            '<param name="movie" value="http://www.youtube.com/v/\1"></param>'.
            '<param name="wmode" value="transparent"></param>'.
            '<!--[if IE]>'.
            '<embed src="http://www.youtube.com/v/\1"'.
            'type="application/x-shockwave-flash"'.
            'wmode="transparent" width="425" height="350" />'.
            '<![endif]-->'.
            '</object>';
        return preg_replace($post_regex, $post_replace, $html);
    }
    
}

