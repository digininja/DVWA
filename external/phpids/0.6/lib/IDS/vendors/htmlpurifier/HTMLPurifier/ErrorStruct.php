<?php

/**
 * Records errors for particular segments of an HTML document such as tokens,
 * attributes or CSS properties. They can contain error structs (which apply
 * to components of what they represent), but their main purpose is to hold
 * errors applying to whatever struct is being used.
 */
class HTMLPurifier_ErrorStruct
{
    
    /**
     * Possible values for $children first-key. Note that top-level structures
     * are automatically token-level.
     */
    const TOKEN     = 0;
    const ATTR      = 1;
    const CSSPROP   = 2;
    
    /**
     * Type of this struct.
     */
    public $type;
    
    /**
     * Value of the struct we are recording errors for. There are various
     * values for this:
     *  - TOKEN: Instance of HTMLPurifier_Token
     *  - ATTR: array('attr-name', 'value')
     *  - CSSPROP: array('prop-name', 'value')
     */
    public $value;
    
    /**
     * Errors registered for this structure.
     */
    public $errors = array();
    
    /**
     * Child ErrorStructs that are from this structure. For example, a TOKEN
     * ErrorStruct would contain ATTR ErrorStructs. This is a multi-dimensional
     * array in structure: [TYPE]['identifier']
     */
    public $children = array();
    
    public function getChild($type, $id) {
        if (!isset($this->children[$type][$id])) {
            $this->children[$type][$id] = new HTMLPurifier_ErrorStruct();
            $this->children[$type][$id]->type = $type;
        }
        return $this->children[$type][$id];
    }
    
    public function addError($severity, $message) {
        $this->errors[] = array($severity, $message);
    }
    
}
