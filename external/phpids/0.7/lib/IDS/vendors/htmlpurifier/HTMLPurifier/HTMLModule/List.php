<?php

/**
 * XHTML 1.1 List Module, defines list-oriented elements. Core Module.
 */
class HTMLPurifier_HTMLModule_List extends HTMLPurifier_HTMLModule
{

    public $name = 'List';

    // According to the abstract schema, the List content set is a fully formed
    // one or more expr, but it invariably occurs in an optional declaration
    // so we're not going to do that subtlety. It might cause trouble
    // if a user defines "List" and expects that multiple lists are
    // allowed to be specified, but then again, that's not very intuitive.
    // Furthermore, the actual XML Schema may disagree. Regardless,
    // we don't have support for such nested expressions without using
    // the incredibly inefficient and draconic Custom ChildDef.

    public $content_sets = array('Flow' => 'List');

    public function setup($config) {
        $ol = $this->addElement('ol', 'List', 'Required: li', 'Common');
        $ol->wrap = "li";
        $ul = $this->addElement('ul', 'List', 'Required: li', 'Common');
        $ul->wrap = "li";
        $this->addElement('dl', 'List', 'Required: dt | dd', 'Common');

        $this->addElement('li', false, 'Flow', 'Common');

        $this->addElement('dd', false, 'Flow', 'Common');
        $this->addElement('dt', false, 'Inline', 'Common');
    }

}

// vim: et sw=4 sts=4
