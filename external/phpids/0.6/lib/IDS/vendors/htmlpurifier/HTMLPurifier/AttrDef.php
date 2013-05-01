<?php

/**
 * Base class for all validating attribute definitions.
 * 
 * This family of classes forms the core for not only HTML attribute validation,
 * but also any sort of string that needs to be validated or cleaned (which
 * means CSS properties and composite definitions are defined here too).  
 * Besides defining (through code) what precisely makes the string valid,
 * subclasses are also responsible for cleaning the code if possible.
 */

abstract class HTMLPurifier_AttrDef
{
    
    /**
     * Tells us whether or not an HTML attribute is minimized. Has no
     * meaning in other contexts.
     */
    public $minimized = false;
    
    /**
     * Tells us whether or not an HTML attribute is required. Has no
     * meaning in other contexts
     */
    public $required = false;
    
    /**
     * Validates and cleans passed string according to a definition.
     * 
     * @param $string String to be validated and cleaned.
     * @param $config Mandatory HTMLPurifier_Config object.
     * @param $context Mandatory HTMLPurifier_AttrContext object.
     */
    abstract public function validate($string, $config, $context);
    
    /**
     * Convenience method that parses a string as if it were CDATA.
     * 
     * This method process a string in the manner specified at
     * <http://www.w3.org/TR/html4/types.html#h-6.2> by removing
     * leading and trailing whitespace, ignoring line feeds, and replacing
     * carriage returns and tabs with spaces.  While most useful for HTML
     * attributes specified as CDATA, it can also be applied to most CSS
     * values.
     * 
     * @note This method is not entirely standards compliant, as trim() removes
     *       more types of whitespace than specified in the spec. In practice,
     *       this is rarely a problem, as those extra characters usually have
     *       already been removed by HTMLPurifier_Encoder.
     * 
     * @warning This processing is inconsistent with XML's whitespace handling
     *          as specified by section 3.3.3 and referenced XHTML 1.0 section
     *          4.7.  However, note that we are NOT necessarily
     *          parsing XML, thus, this behavior may still be correct. We
     *          assume that newlines have been normalized.
     */
    public function parseCDATA($string) {
        $string = trim($string);
        $string = str_replace(array("\n", "\t", "\r"), ' ', $string);
        return $string;
    }
    
    /**
     * Factory method for creating this class from a string.
     * @param $string String construction info
     * @return Created AttrDef object corresponding to $string
     */
    public function make($string) {
        // default implementation, return a flyweight of this object.
        // If $string has an effect on the returned object (i.e. you
        // need to overload this method), it is best
        // to clone or instantiate new copies. (Instantiation is safer.)
        return $this;
    }
    
    /**
     * Removes spaces from rgb(0, 0, 0) so that shorthand CSS properties work
     * properly. THIS IS A HACK!
     */
    protected function mungeRgb($string) {
        return preg_replace('/rgb\((\d+)\s*,\s*(\d+)\s*,\s*(\d+)\)/', 'rgb(\1,\2,\3)', $string);
    }
    
}

