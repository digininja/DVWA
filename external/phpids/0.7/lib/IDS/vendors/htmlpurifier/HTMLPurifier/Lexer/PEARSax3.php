<?php

/**
 * Proof-of-concept lexer that uses the PEAR package XML_HTMLSax3 to parse HTML.
 *
 * PEAR, not suprisingly, also has a SAX parser for HTML.  I don't know
 * very much about implementation, but it's fairly well written.  However, that
 * abstraction comes at a price: performance. You need to have it installed,
 * and if the API changes, it might break our adapter. Not sure whether or not
 * it's UTF-8 aware, but it has some entity parsing trouble (in all areas,
 * text and attributes).
 *
 * Quite personally, I don't recommend using the PEAR class, and the defaults
 * don't use it. The unit tests do perform the tests on the SAX parser too, but
 * whatever it does for poorly formed HTML is up to it.
 *
 * @todo Generalize so that XML_HTMLSax is also supported.
 *
 * @warning Entity-resolution inside attributes is broken.
 */

class HTMLPurifier_Lexer_PEARSax3 extends HTMLPurifier_Lexer
{

    /**
     * Internal accumulator array for SAX parsers.
     */
    protected $tokens = array();
    protected $last_token_was_empty;

    private $parent_handler;
    private $stack = array();

    public function tokenizeHTML($string, $config, $context) {

        $this->tokens = array();
        $this->last_token_was_empty = false;

        $string = $this->normalize($string, $config, $context);

        $this->parent_handler = set_error_handler(array($this, 'muteStrictErrorHandler'));

        $parser = new XML_HTMLSax3();
        $parser->set_object($this);
        $parser->set_element_handler('openHandler','closeHandler');
        $parser->set_data_handler('dataHandler');
        $parser->set_escape_handler('escapeHandler');

        // doesn't seem to work correctly for attributes
        $parser->set_option('XML_OPTION_ENTITIES_PARSED', 1);

        $parser->parse($string);

        restore_error_handler();

        return $this->tokens;

    }

    /**
     * Open tag event handler, interface is defined by PEAR package.
     */
    public function openHandler(&$parser, $name, $attrs, $closed) {
        // entities are not resolved in attrs
        foreach ($attrs as $key => $attr) {
            $attrs[$key] = $this->parseData($attr);
        }
        if ($closed) {
            $this->tokens[] = new HTMLPurifier_Token_Empty($name, $attrs);
            $this->last_token_was_empty = true;
        } else {
            $this->tokens[] = new HTMLPurifier_Token_Start($name, $attrs);
        }
        $this->stack[] = $name;
        return true;
    }

    /**
     * Close tag event handler, interface is defined by PEAR package.
     */
    public function closeHandler(&$parser, $name) {
        // HTMLSax3 seems to always send empty tags an extra close tag
        // check and ignore if you see it:
        // [TESTME] to make sure it doesn't overreach
        if ($this->last_token_was_empty) {
            $this->last_token_was_empty = false;
            return true;
        }
        $this->tokens[] = new HTMLPurifier_Token_End($name);
        if (!empty($this->stack)) array_pop($this->stack);
        return true;
    }

    /**
     * Data event handler, interface is defined by PEAR package.
     */
    public function dataHandler(&$parser, $data) {
        $this->last_token_was_empty = false;
        $this->tokens[] = new HTMLPurifier_Token_Text($data);
        return true;
    }

    /**
     * Escaped text handler, interface is defined by PEAR package.
     */
    public function escapeHandler(&$parser, $data) {
        if (strpos($data, '--') === 0) {
            // remove trailing and leading double-dashes
            $data = substr($data, 2);
            if (strlen($data) >= 2 && substr($data, -2) == "--") {
                $data = substr($data, 0, -2);
            }
            if (isset($this->stack[sizeof($this->stack) - 1]) &&
                $this->stack[sizeof($this->stack) - 1] == "style") {
                $this->tokens[] = new HTMLPurifier_Token_Text($data);
            } else {
                $this->tokens[] = new HTMLPurifier_Token_Comment($data);
            }
            $this->last_token_was_empty = false;
        }
        // CDATA is handled elsewhere, but if it was handled here:
        //if (strpos($data, '[CDATA[') === 0) {
        //    $this->tokens[] = new HTMLPurifier_Token_Text(
        //        substr($data, 7, strlen($data) - 9) );
        //}
        return true;
    }

    /**
     * An error handler that mutes strict errors
     */
    public function muteStrictErrorHandler($errno, $errstr, $errfile=null, $errline=null, $errcontext=null) {
        if ($errno == E_STRICT) return;
        return call_user_func($this->parent_handler, $errno, $errstr, $errfile, $errline, $errcontext);
    }

}

// vim: et sw=4 sts=4
