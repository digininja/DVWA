<?php

/**
 * Registry object that contains information about the current context.
 * @warning Is a bit buggy when variables are set to null: it thinks
 *          they don't exist! So use false instead, please.
 * @note Since the variables Context deals with may not be objects,
 *       references are very important here! Do not remove!
 */
class HTMLPurifier_Context
{

    /**
     * Private array that stores the references.
     */
    private $_storage = array();

    /**
     * Registers a variable into the context.
     * @param $name String name
     * @param $ref Reference to variable to be registered
     */
    public function register($name, &$ref) {
        if (isset($this->_storage[$name])) {
            trigger_error("Name $name produces collision, cannot re-register",
                          E_USER_ERROR);
            return;
        }
        $this->_storage[$name] =& $ref;
    }

    /**
     * Retrieves a variable reference from the context.
     * @param $name String name
     * @param $ignore_error Boolean whether or not to ignore error
     */
    public function &get($name, $ignore_error = false) {
        if (!isset($this->_storage[$name])) {
            if (!$ignore_error) {
                trigger_error("Attempted to retrieve non-existent variable $name",
                              E_USER_ERROR);
            }
            $var = null; // so we can return by reference
            return $var;
        }
        return $this->_storage[$name];
    }

    /**
     * Destorys a variable in the context.
     * @param $name String name
     */
    public function destroy($name) {
        if (!isset($this->_storage[$name])) {
            trigger_error("Attempted to destroy non-existent variable $name",
                          E_USER_ERROR);
            return;
        }
        unset($this->_storage[$name]);
    }

    /**
     * Checks whether or not the variable exists.
     * @param $name String name
     */
    public function exists($name) {
        return isset($this->_storage[$name]);
    }

    /**
     * Loads a series of variables from an associative array
     * @param $context_array Assoc array of variables to load
     */
    public function loadArray($context_array) {
        foreach ($context_array as $key => $discard) {
            $this->register($key, $context_array[$key]);
        }
    }

}

// vim: et sw=4 sts=4
