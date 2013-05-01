<?php

/**
 * Concrete comment token class. Generally will be ignored.
 */
class HTMLPurifier_Token_Comment extends HTMLPurifier_Token
{
    public $data; /**< Character data within comment. */
    /**
     * Transparent constructor.
     * 
     * @param $data String comment data.
     */
    public function __construct($data, $line = null, $col = null) {
        $this->data = $data;
        $this->line = $line;
        $this->col  = $col;
    }
}

