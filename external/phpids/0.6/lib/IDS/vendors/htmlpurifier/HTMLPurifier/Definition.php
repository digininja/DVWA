<?php

/**
 * Super-class for definition datatype objects, implements serialization
 * functions for the class.
 */
abstract class HTMLPurifier_Definition
{
    
    /**
     * Has setup() been called yet?
     */
    public $setup = false;
    
    /**
     * What type of definition is it?
     */
    public $type;
    
    /**
     * Sets up the definition object into the final form, something
     * not done by the constructor
     * @param $config HTMLPurifier_Config instance
     */
    abstract protected function doSetup($config);
    
    /**
     * Setup function that aborts if already setup
     * @param $config HTMLPurifier_Config instance
     */
    public function setup($config) {
        if ($this->setup) return;
        $this->setup = true;
        $this->doSetup($config);
    }
    
}

