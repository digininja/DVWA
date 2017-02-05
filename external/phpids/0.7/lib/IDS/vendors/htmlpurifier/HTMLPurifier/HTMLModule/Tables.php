<?php

/**
 * XHTML 1.1 Tables Module, fully defines accessible table elements.
 */
class HTMLPurifier_HTMLModule_Tables extends HTMLPurifier_HTMLModule
{

    public $name = 'Tables';

    public function setup($config) {

        $this->addElement('caption', false, 'Inline', 'Common');

        $this->addElement('table', 'Block',
            new HTMLPurifier_ChildDef_Table(),  'Common',
            array(
                'border' => 'Pixels',
                'cellpadding' => 'Length',
                'cellspacing' => 'Length',
                'frame' => 'Enum#void,above,below,hsides,lhs,rhs,vsides,box,border',
                'rules' => 'Enum#none,groups,rows,cols,all',
                'summary' => 'Text',
                'width' => 'Length'
            )
        );

        // common attributes
        $cell_align = array(
            'align' => 'Enum#left,center,right,justify,char',
            'charoff' => 'Length',
            'valign' => 'Enum#top,middle,bottom,baseline',
        );

        $cell_t = array_merge(
            array(
                'abbr'    => 'Text',
                'colspan' => 'Number',
                'rowspan' => 'Number',
            ),
            $cell_align
        );
        $this->addElement('td', false, 'Flow', 'Common', $cell_t);
        $this->addElement('th', false, 'Flow', 'Common', $cell_t);

        $this->addElement('tr', false, 'Required: td | th', 'Common', $cell_align);

        $cell_col = array_merge(
            array(
                'span'  => 'Number',
                'width' => 'MultiLength',
            ),
            $cell_align
        );
        $this->addElement('col',      false, 'Empty',         'Common', $cell_col);
        $this->addElement('colgroup', false, 'Optional: col', 'Common', $cell_col);

        $this->addElement('tbody', false, 'Required: tr', 'Common', $cell_align);
        $this->addElement('thead', false, 'Required: tr', 'Common', $cell_align);
        $this->addElement('tfoot', false, 'Required: tr', 'Common', $cell_align);

    }

}

// vim: et sw=4 sts=4
