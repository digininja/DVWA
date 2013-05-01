<?php

/**
 * Validates name/value pairs in param tags to be used in safe objects. This
 * will only allow name values it recognizes, and pre-fill certain attributes
 * with required values.
 * 
 * @note
 *      This class only supports Flash. In the future, Quicktime support
 *      may be added.
 * 
 * @warning
 *      This class expects an injector to add the necessary parameters tags.
 */
class HTMLPurifier_AttrTransform_SafeParam extends HTMLPurifier_AttrTransform 
{
    public $name = "SafeParam";
    private $uri;
    
    public function __construct() {
        $this->uri = new HTMLPurifier_AttrDef_URI(true); // embedded
    }
    
    public function transform($attr, $config, $context) {
        // If we add support for other objects, we'll need to alter the
        // transforms.
        switch ($attr['name']) {
            // application/x-shockwave-flash
            // Keep this synchronized with Injector/SafeObject.php
            case 'allowScriptAccess':
                $attr['value'] = 'never';
                break;
            case 'allowNetworking':
                $attr['value'] = 'internal';
                break;
            case 'wmode':
                $attr['value'] = 'window';
                break;
            case 'movie':
                $attr['value'] = $this->uri->validate($attr['value'], $config, $context);
                break;
            // add other cases to support other param name/value pairs
            default:
                $attr['name'] = $attr['value'] = null;
        }
        return $attr;
    }
}
