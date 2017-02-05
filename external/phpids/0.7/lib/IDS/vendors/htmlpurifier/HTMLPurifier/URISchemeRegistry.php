<?php

/**
 * Registry for retrieving specific URI scheme validator objects.
 */
class HTMLPurifier_URISchemeRegistry
{

    /**
     * Retrieve sole instance of the registry.
     * @param $prototype Optional prototype to overload sole instance with,
     *                   or bool true to reset to default registry.
     * @note Pass a registry object $prototype with a compatible interface and
     *       the function will copy it and return it all further times.
     */
    public static function instance($prototype = null) {
        static $instance = null;
        if ($prototype !== null) {
            $instance = $prototype;
        } elseif ($instance === null || $prototype == true) {
            $instance = new HTMLPurifier_URISchemeRegistry();
        }
        return $instance;
    }

    /**
     * Cache of retrieved schemes.
     */
    protected $schemes = array();

    /**
     * Retrieves a scheme validator object
     * @param $scheme String scheme name like http or mailto
     * @param $config HTMLPurifier_Config object
     * @param $config HTMLPurifier_Context object
     */
    public function getScheme($scheme, $config, $context) {
        if (!$config) $config = HTMLPurifier_Config::createDefault();

        // important, otherwise attacker could include arbitrary file
        $allowed_schemes = $config->get('URI.AllowedSchemes');
        if (!$config->get('URI.OverrideAllowedSchemes') &&
            !isset($allowed_schemes[$scheme])
        ) {
            return;
        }

        if (isset($this->schemes[$scheme])) return $this->schemes[$scheme];
        if (!isset($allowed_schemes[$scheme])) return;

        $class = 'HTMLPurifier_URIScheme_' . $scheme;
        if (!class_exists($class)) return;
        $this->schemes[$scheme] = new $class();
        return $this->schemes[$scheme];
    }

    /**
     * Registers a custom scheme to the cache, bypassing reflection.
     * @param $scheme Scheme name
     * @param $scheme_obj HTMLPurifier_URIScheme object
     */
    public function register($scheme, $scheme_obj) {
        $this->schemes[$scheme] = $scheme_obj;
    }

}

// vim: et sw=4 sts=4
