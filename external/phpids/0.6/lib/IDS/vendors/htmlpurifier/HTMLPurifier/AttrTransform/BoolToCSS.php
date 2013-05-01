<?php

/**
 * Pre-transform that changes converts a boolean attribute to fixed CSS
 */
class HTMLPurifier_AttrTransform_BoolToCSS extends HTMLPurifier_AttrTransform {
    
    /**
     * Name of boolean attribute that is trigger
     */
    protected $attr;
    
    /**
     * CSS declarations to add to style, needs trailing semicolon
     */
    protected $css;
    
    /**
     * @param $attr string attribute name to convert from
     * @param $css string CSS declarations to add to style (needs semicolon)
     */
    public function __construct($attr, $css) {
        $this->attr = $attr;
        $this->css  = $css;
    }
    
    public function transform($attr, $config, $context) {
        if (!isset($attr[$this->attr])) return $attr;
        unset($attr[$this->attr]);
        $this->prependCSS($attr, $this->css);
        return $attr;
    }
    
}

