<?php

/**
 * Concrete text token class.
 *
 * Text tokens comprise of regular parsed character data (PCDATA) and raw
 * character data (from the CDATA sections). Internally, their
 * data is parsed with all entities expanded. Surprisingly, the text token
 * does have a "tag name" called #PCDATA, which is how the DTD represents it
 * in permissible child nodes.
 */
class HTMLPurifier_Token_Text extends HTMLPurifier_Token
{

    public $name = '#PCDATA'; /**< PCDATA tag name compatible with DTD. */
    public $data; /**< Parsed character data of text. */
    public $is_whitespace; /**< Bool indicating if node is whitespace. */

    /**
     * Constructor, accepts data and determines if it is whitespace.
     *
     * @param $data String parsed character data.
     */
    public function __construct($data, $line = null, $col = null) {
        $this->data = $data;
        $this->is_whitespace = ctype_space($data);
        $this->line = $line;
        $this->col  = $col;
    }

}

// vim: et sw=4 sts=4
