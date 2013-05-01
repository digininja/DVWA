<?php

/**
 * Generates HTML from tokens.
 * @todo Refactor interface so that configuration/context is determined
 *       upon instantiation, no need for messy generateFromTokens() calls
 * @todo Make some of the more internal functions protected, and have
 *       unit tests work around that
 */
class HTMLPurifier_Generator
{
    
    /**
     * Whether or not generator should produce XML output
     */
    private $_xhtml = true;
    
    /**
     * :HACK: Whether or not generator should comment the insides of <script> tags
     */
    private $_scriptFix = false;
    
    /**
     * Cache of HTMLDefinition during HTML output to determine whether or
     * not attributes should be minimized.
     */
    private $_def;
    
    /**
     * Cache of %Output.SortAttr
     */
    private $_sortAttr;
    
    /**
     * Configuration for the generator
     */
    protected $config;
    
    /**
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     */
    public function __construct($config, $context) {
        $this->config = $config;
        $this->_scriptFix = $config->get('Output', 'CommentScriptContents');
        $this->_sortAttr = $config->get('Output', 'SortAttr');
        $this->_def = $config->getHTMLDefinition();
        $this->_xhtml = $this->_def->doctype->xml;
    }
    
    /**
     * Generates HTML from an array of tokens.
     * @param $tokens Array of HTMLPurifier_Token
     * @param $config HTMLPurifier_Config object
     * @return Generated HTML
     */
    public function generateFromTokens($tokens) {
        if (!$tokens) return '';
        
        // Basic algorithm
        $html = '';
        for ($i = 0, $size = count($tokens); $i < $size; $i++) {
            if ($this->_scriptFix && $tokens[$i]->name === 'script'
                && $i + 2 < $size && $tokens[$i+2] instanceof HTMLPurifier_Token_End) {
                // script special case
                // the contents of the script block must be ONE token
                // for this to work.
                $html .= $this->generateFromToken($tokens[$i++]);
                $html .= $this->generateScriptFromToken($tokens[$i++]);
            }
            $html .= $this->generateFromToken($tokens[$i]);
        }
        
        // Tidy cleanup
        if (extension_loaded('tidy') && $this->config->get('Output', 'TidyFormat')) {
            $tidy = new Tidy;
            $tidy->parseString($html, array(
               'indent'=> true,
               'output-xhtml' => $this->_xhtml,
               'show-body-only' => true,
               'indent-spaces' => 2,
               'wrap' => 68,
            ), 'utf8');
            $tidy->cleanRepair();
            $html = (string) $tidy; // explicit cast necessary
        }
        
        // Normalize newlines to system defined value
        $nl = $this->config->get('Output', 'Newline');
        if ($nl === null) $nl = PHP_EOL;
        if ($nl !== "\n") $html = str_replace("\n", $nl, $html);
        return $html;
    }
    
    /**
     * Generates HTML from a single token.
     * @param $token HTMLPurifier_Token object.
     * @return Generated HTML
     */
    public function generateFromToken($token) {
        if (!$token instanceof HTMLPurifier_Token) {
            trigger_error('Cannot generate HTML from non-HTMLPurifier_Token object', E_USER_WARNING);
            return '';
            
        } elseif ($token instanceof HTMLPurifier_Token_Start) {
            $attr = $this->generateAttributes($token->attr, $token->name);
            return '<' . $token->name . ($attr ? ' ' : '') . $attr . '>';
            
        } elseif ($token instanceof HTMLPurifier_Token_End) {
            return '</' . $token->name . '>';
            
        } elseif ($token instanceof HTMLPurifier_Token_Empty) {
            $attr = $this->generateAttributes($token->attr, $token->name);
             return '<' . $token->name . ($attr ? ' ' : '') . $attr .
                ( $this->_xhtml ? ' /': '' ) // <br /> v. <br>
                . '>';
            
        } elseif ($token instanceof HTMLPurifier_Token_Text) {
            return $this->escape($token->data, ENT_NOQUOTES);
            
        } elseif ($token instanceof HTMLPurifier_Token_Comment) {
            return '<!--' . $token->data . '-->';
        } else {
            return '';
            
        }
    }
    
    /**
     * Special case processor for the contents of script tags
     * @warning This runs into problems if there's already a literal
     *          --> somewhere inside the script contents.
     */
    public function generateScriptFromToken($token) {
        if (!$token instanceof HTMLPurifier_Token_Text) return $this->generateFromToken($token);
        // Thanks <http://lachy.id.au/log/2005/05/script-comments>
        $data = preg_replace('#//\s*$#', '', $token->data);
        return '<!--//--><![CDATA[//><!--' . "\n" . trim($data) . "\n" . '//--><!]]>';
    }
    
    /**
     * Generates attribute declarations from attribute array.
     * @note This does not include the leading or trailing space.
     * @param $assoc_array_of_attributes Attribute array
     * @param $element Name of element attributes are for, used to check
     *        attribute minimization.
     * @return Generate HTML fragment for insertion.
     */
    public function generateAttributes($assoc_array_of_attributes, $element = false) {
        $html = '';
        if ($this->_sortAttr) ksort($assoc_array_of_attributes);
        foreach ($assoc_array_of_attributes as $key => $value) {
            if (!$this->_xhtml) {
                // Remove namespaced attributes
                if (strpos($key, ':') !== false) continue;
                // Check if we should minimize the attribute: val="val" -> val
                if ($element && !empty($this->_def->info[$element]->attr[$key]->minimized)) {
                    $html .= $key . ' ';
                    continue;
                }
            }
            $html .= $key.'="'.$this->escape($value).'" ';
        }
        return rtrim($html);
    }
    
    /**
     * Escapes raw text data.
     * @todo This really ought to be protected, but until we have a facility
     *       for properly generating HTML here w/o using tokens, it stays
     *       public.
     * @param $string String data to escape for HTML.
     * @param $quote Quoting style, like htmlspecialchars. ENT_NOQUOTES is
     *               permissible for non-attribute output.
     * @return String escaped data.
     */
    public function escape($string, $quote = ENT_COMPAT) {
        return htmlspecialchars($string, $quote, 'UTF-8');
    }
    
}

