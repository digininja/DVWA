<?php

// must be called POST validation

/**
 * Adds rel="nofollow" to all outbound links.  This transform is
 * only attached if Attr.Nofollow is TRUE.
 */
class HTMLPurifier_AttrTransform_Nofollow extends HTMLPurifier_AttrTransform
{
    private $parser;

    public function __construct() {
        $this->parser = new HTMLPurifier_URIParser();
    }

    public function transform($attr, $config, $context) {

        if (!isset($attr['href'])) {
            return $attr;
        }

        // XXX Kind of inefficient
        $url = $this->parser->parse($attr['href']);
        $scheme = $url->getSchemeObj($config, $context);

        if (!is_null($url->host) && $scheme !== false && $scheme->browsable) {
            if (isset($attr['rel'])) {
                $attr['rel'] .= ' nofollow';
            } else {
                $attr['rel'] = 'nofollow';
            }
        }

        return $attr;

    }

}

// vim: et sw=4 sts=4
