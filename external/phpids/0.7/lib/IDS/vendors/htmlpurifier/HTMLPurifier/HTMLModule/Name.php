<?php

class HTMLPurifier_HTMLModule_Name extends HTMLPurifier_HTMLModule
{

    public $name = 'Name';

    public function setup($config) {
        $elements = array('a', 'applet', 'form', 'frame', 'iframe', 'img', 'map');
        foreach ($elements as $name) {
            $element = $this->addBlankElement($name);
            $element->attr['name'] = 'CDATA';
            if (!$config->get('HTML.Attr.Name.UseCDATA')) {
                $element->attr_transform_post['NameSync'] = new HTMLPurifier_AttrTransform_NameSync();
            }
        }
    }

}

// vim: et sw=4 sts=4
