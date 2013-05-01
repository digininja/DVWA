<?php

/**
 * XHTML 1.1 Presentation Module, defines simple presentation-related
 * markup. Text Extension Module.
 * @note The official XML Schema and DTD specs further divide this into
 *       two modules:
 *          - Block Presentation (hr)
 *          - Inline Presentation (b, big, i, small, sub, sup, tt)
 *       We have chosen not to heed this distinction, as content_sets
 *       provides satisfactory disambiguation.
 */
class HTMLPurifier_HTMLModule_Presentation extends HTMLPurifier_HTMLModule
{
    
    public $name = 'Presentation';
    
    public function setup($config) {
        $this->addElement('b',      'Inline', 'Inline', 'Common');
        $this->addElement('big',    'Inline', 'Inline', 'Common');
        $this->addElement('hr',     'Block',  'Empty',  'Common');
        $this->addElement('i',      'Inline', 'Inline', 'Common');
        $this->addElement('small',  'Inline', 'Inline', 'Common');
        $this->addElement('sub',    'Inline', 'Inline', 'Common');
        $this->addElement('sup',    'Inline', 'Inline', 'Common');
        $this->addElement('tt',     'Inline', 'Inline', 'Common');
    }
    
}

