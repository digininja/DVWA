<?php

class HTMLPurifier_URIFilter_Munge extends HTMLPurifier_URIFilter
{
    public $name = 'Munge';
    public $post = true;
    private $target, $parser, $doEmbed, $secretKey;
    
    protected $replace = array();
    
    public function prepare($config) {
        $this->target    = $config->get('URI', $this->name);
        $this->parser    = new HTMLPurifier_URIParser();
        $this->doEmbed   = $config->get('URI', 'MungeResources');
        $this->secretKey = $config->get('URI', 'MungeSecretKey');
        return true;
    }
    public function filter(&$uri, $config, $context) {
        if ($context->get('EmbeddedURI', true) && !$this->doEmbed) return true;
        
        $scheme_obj = $uri->getSchemeObj($config, $context);
        if (!$scheme_obj) return true; // ignore unknown schemes, maybe another postfilter did it
        if (is_null($uri->host) || empty($scheme_obj->browsable)) {
            return true;
        }
        
        $this->makeReplace($uri, $config, $context);
        $this->replace = array_map('rawurlencode', $this->replace);
        
        $new_uri = strtr($this->target, $this->replace);
        $new_uri = $this->parser->parse($new_uri);
        // don't redirect if the target host is the same as the 
        // starting host
        if ($uri->host === $new_uri->host) return true;
        $uri = $new_uri; // overwrite
        return true;
    }
    
    protected function makeReplace($uri, $config, $context) {
        $string = $uri->toString();
        // always available
        $this->replace['%s'] = $string;
        $this->replace['%r'] = $context->get('EmbeddedURI', true);
        $token = $context->get('CurrentToken', true);
        $this->replace['%n'] = $token ? $token->name : null;
        $this->replace['%m'] = $context->get('CurrentAttr', true);
        $this->replace['%p'] = $context->get('CurrentCSSProperty', true);
        // not always available
        if ($this->secretKey) $this->replace['%t'] = sha1($this->secretKey . ':' . $string);
    }
    
}
