<?php

/**
 * Validates a Percentage as defined by the CSS spec.
 */
class HTMLPurifier_AttrDef_CSS_Percentage extends HTMLPurifier_AttrDef
{

    /**
     * Instance of HTMLPurifier_AttrDef_CSS_Number to defer number validation
     */
    protected $number_def;

    /**
     * @param Bool indicating whether to forbid negative values
     */
    public function __construct($non_negative = false) {
        $this->number_def = new HTMLPurifier_AttrDef_CSS_Number($non_negative);
    }

    public function validate($string, $config, $context) {

        $string = $this->parseCDATA($string);

        if ($string === '') return false;
        $length = strlen($string);
        if ($length === 1) return false;
        if ($string[$length - 1] !== '%') return false;

        $number = substr($string, 0, $length - 1);
        $number = $this->number_def->validate($number, $config, $context);

        if ($number === false) return false;
        return "$number%";

    }

}

// vim: et sw=4 sts=4
