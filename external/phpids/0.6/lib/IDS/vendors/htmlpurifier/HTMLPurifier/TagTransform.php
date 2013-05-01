<?php

/**
 * Defines a mutation of an obsolete tag into a valid tag.
 */
abstract class HTMLPurifier_TagTransform
{
    
    /**
     * Tag name to transform the tag to.
     */
    public $transform_to;
    
    /**
     * Transforms the obsolete tag into the valid tag.
     * @param $tag Tag to be transformed.
     * @param $config Mandatory HTMLPurifier_Config object
     * @param $context Mandatory HTMLPurifier_Context object
     */
    abstract public function transform($tag, $config, $context);
    
    /**
     * Prepends CSS properties to the style attribute, creating the
     * attribute if it doesn't exist.
     * @warning Copied over from AttrTransform, be sure to keep in sync
     * @param $attr Attribute array to process (passed by reference)
     * @param $css CSS to prepend
     */
    protected function prependCSS(&$attr, $css) {
        $attr['style'] = isset($attr['style']) ? $attr['style'] : '';
        $attr['style'] = $css . $attr['style'];
    }
    
}

