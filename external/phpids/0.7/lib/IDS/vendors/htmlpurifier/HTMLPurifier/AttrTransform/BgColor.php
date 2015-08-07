<?php

/**
 * Pre-transform that changes deprecated bgcolor attribute to CSS.
 */
class HTMLPurifier_AttrTransform_BgColor extends HTMLPurifier_AttrTransform {

    public function transform($attr, $config, $context) {

        if (!isset($attr['bgcolor'])) return $attr;

        $bgcolor = $this->confiscateAttr($attr, 'bgcolor');
        // some validation should happen here

        $this->prependCSS($attr, "background-color:$bgcolor;");

        return $attr;

    }

}

// vim: et sw=4 sts=4
