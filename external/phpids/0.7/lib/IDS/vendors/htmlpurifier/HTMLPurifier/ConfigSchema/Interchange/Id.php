<?php

/**
 * Represents a directive ID in the interchange format.
 */
class HTMLPurifier_ConfigSchema_Interchange_Id
{

    public $key;

    public function __construct($key) {
        $this->key = $key;
    }

    /**
     * @warning This is NOT magic, to ensure that people don't abuse SPL and
     *          cause problems for PHP 5.0 support.
     */
    public function toString() {
        return $this->key;
    }

    public function getRootNamespace() {
        return substr($this->key, 0, strpos($this->key, "."));
    }

    public function getDirective() {
        return substr($this->key, strpos($this->key, ".") + 1);
    }

    public static function make($id) {
        return new HTMLPurifier_ConfigSchema_Interchange_Id($id);
    }

}

// vim: et sw=4 sts=4
