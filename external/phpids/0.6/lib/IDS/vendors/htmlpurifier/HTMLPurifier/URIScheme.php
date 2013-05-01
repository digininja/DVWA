<?php

/**
 * Validator for the components of a URI for a specific scheme
 */
class HTMLPurifier_URIScheme
{
    
    /**
     * Scheme's default port (integer)
     */
    public $default_port = null;
    
    /**
     * Whether or not URIs of this schem are locatable by a browser
     * http and ftp are accessible, while mailto and news are not.
     */
    public $browsable = false;
    
    /**
     * Whether or not the URI always uses <hier_part>, resolves edge cases
     * with making relative URIs absolute
     */
    public $hierarchical = false;
    
    /**
     * Validates the components of a URI
     * @note This implementation should be called by children if they define
     *       a default port, as it does port processing.
     * @param $uri Instance of HTMLPurifier_URI
     * @param $config HTMLPurifier_Config object
     * @param $context HTMLPurifier_Context object
     * @return Bool success or failure
     */
    public function validate(&$uri, $config, $context) {
        if ($this->default_port == $uri->port) $uri->port = null;
        return true;
    }
    
}

