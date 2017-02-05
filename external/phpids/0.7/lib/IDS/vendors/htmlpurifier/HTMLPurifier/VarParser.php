<?php

/**
 * Parses string representations into their corresponding native PHP
 * variable type. The base implementation does a simple type-check.
 */
class HTMLPurifier_VarParser
{

    const STRING    = 1;
    const ISTRING   = 2;
    const TEXT      = 3;
    const ITEXT     = 4;
    const INT       = 5;
    const FLOAT     = 6;
    const BOOL      = 7;
    const LOOKUP    = 8;
    const ALIST     = 9;
    const HASH      = 10;
    const MIXED     = 11;

    /**
     * Lookup table of allowed types. Mainly for backwards compatibility, but
     * also convenient for transforming string type names to the integer constants.
     */
    static public $types = array(
        'string'    => self::STRING,
        'istring'   => self::ISTRING,
        'text'      => self::TEXT,
        'itext'     => self::ITEXT,
        'int'       => self::INT,
        'float'     => self::FLOAT,
        'bool'      => self::BOOL,
        'lookup'    => self::LOOKUP,
        'list'      => self::ALIST,
        'hash'      => self::HASH,
        'mixed'     => self::MIXED
    );

    /**
     * Lookup table of types that are string, and can have aliases or
     * allowed value lists.
     */
    static public $stringTypes = array(
        self::STRING    => true,
        self::ISTRING   => true,
        self::TEXT      => true,
        self::ITEXT     => true,
    );

    /**
     * Validate a variable according to type. Throws
     * HTMLPurifier_VarParserException if invalid.
     * It may return NULL as a valid type if $allow_null is true.
     *
     * @param $var Variable to validate
     * @param $type Type of variable, see HTMLPurifier_VarParser->types
     * @param $allow_null Whether or not to permit null as a value
     * @return Validated and type-coerced variable
     */
    final public function parse($var, $type, $allow_null = false) {
        if (is_string($type)) {
            if (!isset(HTMLPurifier_VarParser::$types[$type])) {
                throw new HTMLPurifier_VarParserException("Invalid type '$type'");
            } else {
                $type = HTMLPurifier_VarParser::$types[$type];
            }
        }
        $var = $this->parseImplementation($var, $type, $allow_null);
        if ($allow_null && $var === null) return null;
        // These are basic checks, to make sure nothing horribly wrong
        // happened in our implementations.
        switch ($type) {
            case (self::STRING):
            case (self::ISTRING):
            case (self::TEXT):
            case (self::ITEXT):
                if (!is_string($var)) break;
                if ($type == self::ISTRING || $type == self::ITEXT) $var = strtolower($var);
                return $var;
            case (self::INT):
                if (!is_int($var)) break;
                return $var;
            case (self::FLOAT):
                if (!is_float($var)) break;
                return $var;
            case (self::BOOL):
                if (!is_bool($var)) break;
                return $var;
            case (self::LOOKUP):
            case (self::ALIST):
            case (self::HASH):
                if (!is_array($var)) break;
                if ($type === self::LOOKUP) {
                    foreach ($var as $k) if ($k !== true) $this->error('Lookup table contains value other than true');
                } elseif ($type === self::ALIST) {
                    $keys = array_keys($var);
                    if (array_keys($keys) !== $keys) $this->error('Indices for list are not uniform');
                }
                return $var;
            case (self::MIXED):
                return $var;
            default:
                $this->errorInconsistent(get_class($this), $type);
        }
        $this->errorGeneric($var, $type);
    }

    /**
     * Actually implements the parsing. Base implementation is to not
     * do anything to $var. Subclasses should overload this!
     */
    protected function parseImplementation($var, $type, $allow_null) {
        return $var;
    }

    /**
     * Throws an exception.
     */
    protected function error($msg) {
        throw new HTMLPurifier_VarParserException($msg);
    }

    /**
     * Throws an inconsistency exception.
     * @note This should not ever be called. It would be called if we
     *       extend the allowed values of HTMLPurifier_VarParser without
     *       updating subclasses.
     */
    protected function errorInconsistent($class, $type) {
        throw new HTMLPurifier_Exception("Inconsistency in $class: ".HTMLPurifier_VarParser::getTypeName($type)." not implemented");
    }

    /**
     * Generic error for if a type didn't work.
     */
    protected function errorGeneric($var, $type) {
        $vtype = gettype($var);
        $this->error("Expected type ".HTMLPurifier_VarParser::getTypeName($type).", got $vtype");
    }

    static public function getTypeName($type) {
        static $lookup;
        if (!$lookup) {
            // Lazy load the alternative lookup table
            $lookup = array_flip(HTMLPurifier_VarParser::$types);
        }
        if (!isset($lookup[$type])) return 'unknown';
        return $lookup[$type];
    }

}

// vim: et sw=4 sts=4
