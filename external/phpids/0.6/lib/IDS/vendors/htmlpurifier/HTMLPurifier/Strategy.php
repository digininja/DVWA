<?php

/**
 * Supertype for classes that define a strategy for modifying/purifying tokens.
 * 
 * While HTMLPurifier's core purpose is fixing HTML into something proper, 
 * strategies provide plug points for extra configuration or even extra
 * features, such as custom tags, custom parsing of text, etc.
 */

 
abstract class HTMLPurifier_Strategy
{
    
    /**
     * Executes the strategy on the tokens.
     * 
     * @param $tokens Array of HTMLPurifier_Token objects to be operated on.
     * @param $config Configuration options
     * @returns Processed array of token objects.
     */
    abstract public function execute($tokens, $config, $context);
    
}

