<?php

/**
 * Processes an entire attribute array for corrections needing multiple values.
 * 
 * Occasionally, a certain attribute will need to be removed and popped onto
 * another value.  Instead of creating a complex return syntax for
 * HTMLPurifier_AttrDef, we just pass the whole attribute array to a
 * specialized object and have that do the special work.  That is the
 * family of HTMLPurifier_AttrTransform.
 * 
 * An attribute transformation can be assigned to run before or after
 * HTMLPurifier_AttrDef validation.  See HTMLPurifier_HTMLDefinition for
 * more details.
 */

abstract class HTMLPurifier_AttrTransform
{
    
    /**
     * Abstract: makes changes to the attributes dependent on multiple values.
     * 
     * @param $attr Assoc array of attributes, usually from
     *              HTMLPurifier_Token_Tag::$attr
     * @param $config Mandatory HTMLPurifier_Config object.
     * @param $context Mandatory HTMLPurifier_Context object
     * @returns Processed attribute array.
     */
    abstract public function transform($attr, $config, $context);
    
    /**
     * Prepends CSS properties to the style attribute, creating the
     * attribute if it doesn't exist.
     * @param $attr Attribute array to process (passed by reference)
     * @param $css CSS to prepend
     */
    public function prependCSS(&$attr, $css) {
        $attr['style'] = isset($attr['style']) ? $attr['style'] : '';
        $attr['style'] = $css . $attr['style'];
    }
    
    /**
     * Retrieves and removes an attribute
     * @param $attr Attribute array to process (passed by reference)
     * @param $key Key of attribute to confiscate
     */
    public function confiscateAttr(&$attr, $key) {
        if (!isset($attr[$key])) return null;
        $value = $attr[$key];
        unset($attr[$key]);
        return $value;
    }
    
}

