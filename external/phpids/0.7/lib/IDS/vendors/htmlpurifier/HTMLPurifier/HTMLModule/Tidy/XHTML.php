<?php

class HTMLPurifier_HTMLModule_Tidy_XHTML extends HTMLPurifier_HTMLModule_Tidy
{

    public $name = 'Tidy_XHTML';
    public $defaultLevel = 'medium';

    public function makeFixes() {
        $r = array();
        $r['@lang'] = new HTMLPurifier_AttrTransform_Lang();
        return $r;
    }

}

// vim: et sw=4 sts=4
