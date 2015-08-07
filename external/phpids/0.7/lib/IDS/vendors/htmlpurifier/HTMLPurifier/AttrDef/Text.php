<?php

/**
 * Validates arbitrary text according to the HTML spec.
 */
class HTMLPurifier_AttrDef_Text extends HTMLPurifier_AttrDef
{

    public function validate($string, $config, $context) {
        return $this->parseCDATA($string);
    }

}

// vim: et sw=4 sts=4
