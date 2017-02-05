<?php

/**
 * Composite strategy that runs multiple strategies on tokens.
 */
abstract class HTMLPurifier_Strategy_Composite extends HTMLPurifier_Strategy
{

    /**
     * List of strategies to run tokens through.
     */
    protected $strategies = array();

    abstract public function __construct();

    public function execute($tokens, $config, $context) {
        foreach ($this->strategies as $strategy) {
            $tokens = $strategy->execute($tokens, $config, $context);
        }
        return $tokens;
    }

}

// vim: et sw=4 sts=4
