<?php

/**
 * HTML Purifier's internal representation of a URI.
 * @note
 *      Internal data-structures are completely escaped. If the data needs
 *      to be used in a non-URI context (which is very unlikely), be sure
 *      to decode it first. The URI may not necessarily be well-formed until
 *      validate() is called.
 */
class HTMLPurifier_URI
{
    
    public $scheme, $userinfo, $host, $port, $path, $query, $fragment;
    
    /**
     * @note Automatically normalizes scheme and port
     */
    public function __construct($scheme, $userinfo, $host, $port, $path, $query, $fragment) {
        $this->scheme = is_null($scheme) || ctype_lower($scheme) ? $scheme : strtolower($scheme);
        $this->userinfo = $userinfo;
        $this->host = $host;
        $this->port = is_null($port) ? $port : (int) $port;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
    }
    
    /**
     * Retrieves a scheme object corresponding to the URI's scheme/default
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     * @return Scheme object appropriate for validating this URI
     */
    public function getSchemeObj($config, $context) {
        $registry = HTMLPurifier_URISchemeRegistry::instance();
        if ($this->scheme !== null) {
            $scheme_obj = $registry->getScheme($this->scheme, $config, $context);
            if (!$scheme_obj) return false; // invalid scheme, clean it out
        } else {
            // no scheme: retrieve the default one
            $def = $config->getDefinition('URI');
            $scheme_obj = $registry->getScheme($def->defaultScheme, $config, $context);
            if (!$scheme_obj) {
                // something funky happened to the default scheme object
                trigger_error(
                    'Default scheme object "' . $def->defaultScheme . '" was not readable',
                    E_USER_WARNING
                );
                return false;
            }
        }
        return $scheme_obj;
    }
    
    /**
     * Generic validation method applicable for all schemes. May modify
     * this URI in order to get it into a compliant form.
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     * @return True if validation/filtering succeeds, false if failure
     */
    public function validate($config, $context) {
        
        // ABNF definitions from RFC 3986
        $chars_sub_delims = '!$&\'()*+,;=';
        $chars_gen_delims = ':/?#[]@';
        $chars_pchar = $chars_sub_delims . ':@';
        
        // validate scheme (MUST BE FIRST!)
        if (!is_null($this->scheme) && is_null($this->host)) {
            $def = $config->getDefinition('URI');
            if ($def->defaultScheme === $this->scheme) {
                $this->scheme = null;
            }
        }
        
        // validate host
        if (!is_null($this->host)) {
            $host_def = new HTMLPurifier_AttrDef_URI_Host();
            $this->host = $host_def->validate($this->host, $config, $context);
            if ($this->host === false) $this->host = null;
        }
        
        // validate username
        if (!is_null($this->userinfo)) {
            $encoder = new HTMLPurifier_PercentEncoder($chars_sub_delims . ':');
            $this->userinfo = $encoder->encode($this->userinfo);
        }
        
        // validate port
        if (!is_null($this->port)) {
            if ($this->port < 1 || $this->port > 65535) $this->port = null;
        }
        
        // validate path
        $path_parts = array();
        $segments_encoder = new HTMLPurifier_PercentEncoder($chars_pchar . '/');
        if (!is_null($this->host)) {
            // path-abempty (hier and relative)
            $this->path = $segments_encoder->encode($this->path);
        } elseif ($this->path !== '' && $this->path[0] === '/') {
            // path-absolute (hier and relative)
            if (strlen($this->path) >= 2 && $this->path[1] === '/') {
                // This shouldn't ever happen!
                $this->path = '';
            } else {
                $this->path = $segments_encoder->encode($this->path);
            }
        } elseif (!is_null($this->scheme) && $this->path !== '') {
            // path-rootless (hier)
            // Short circuit evaluation means we don't need to check nz
            $this->path = $segments_encoder->encode($this->path);
        } elseif (is_null($this->scheme) && $this->path !== '') {
            // path-noscheme (relative)
            // (once again, not checking nz)
            $segment_nc_encoder = new HTMLPurifier_PercentEncoder($chars_sub_delims . '@');
            $c = strpos($this->path, '/');
            if ($c !== false) {
                $this->path = 
                    $segment_nc_encoder->encode(substr($this->path, 0, $c)) .
                    $segments_encoder->encode(substr($this->path, $c));
            } else {
                $this->path = $segment_nc_encoder->encode($this->path);
            }
        } else {
            // path-empty (hier and relative)
            $this->path = ''; // just to be safe
        }
        
        // qf = query and fragment
        $qf_encoder = new HTMLPurifier_PercentEncoder($chars_pchar . '/?');
        
        if (!is_null($this->query)) {
            $this->query = $qf_encoder->encode($this->query);
        }
        
        if (!is_null($this->fragment)) {
            $this->fragment = $qf_encoder->encode($this->fragment);
        }
        
        return true;
        
    }
    
    /**
     * Convert URI back to string
     * @return String URI appropriate for output
     */
    public function toString() {
        // reconstruct authority
        $authority = null;
        if (!is_null($this->host)) {
            $authority = '';
            if(!is_null($this->userinfo)) $authority .= $this->userinfo . '@';
            $authority .= $this->host;
            if(!is_null($this->port))     $authority .= ':' . $this->port;
        }
        
        // reconstruct the result
        $result = '';
        if (!is_null($this->scheme))    $result .= $this->scheme . ':';
        if (!is_null($authority))       $result .=  '//' . $authority;
        $result .= $this->path;
        if (!is_null($this->query))     $result .= '?' . $this->query;
        if (!is_null($this->fragment))  $result .= '#' . $this->fragment;
        
        return $result;
    }
    
}

