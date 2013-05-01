<?php

/**
 * Writes default type for all objects. Currently only supports flash.
 */
class HTMLPurifier_AttrTransform_SafeObject extends HTMLPurifier_AttrTransform
{
    public $name = "SafeObject";

    function transform($attr, $config, $context) {
        if (!isset($attr['type'])) $attr['type'] = 'application/x-shockwave-flash';
        return $attr;
    }
}
