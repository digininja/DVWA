<?php

/**
 * Defines allowed child nodes and validates tokens against it.
 */
abstract class HTMLPurifier_ChildDef
{
    /**
     * Type of child definition, usually right-most part of class name lowercase.
     * Used occasionally in terms of context.
     */
    public $type;
    
    /**
     * Bool that indicates whether or not an empty array of children is okay
     * 
     * This is necessary for redundant checking when changes affecting
     * a child node may cause a parent node to now be disallowed.
     */
    public $allow_empty;
    
    /**
     * Lookup array of all elements that this definition could possibly allow
     */
    public $elements = array();
    
    /**
     * Get lookup of tag names that should not close this element automatically.
     * All other elements will do so.
     */
    public function getNonAutoCloseElements($config) {
        return $this->elements;
    }
    
    /**
     * Validates nodes according to definition and returns modification.
     * 
     * @param $tokens_of_children Array of HTMLPurifier_Token
     * @param $config HTMLPurifier_Config object
     * @param $context HTMLPurifier_Context object
     * @return bool true to leave nodes as is
     * @return bool false to remove parent node
     * @return array of replacement child tokens
     */
    abstract public function validateChildren($tokens_of_children, $config, $context);
}


