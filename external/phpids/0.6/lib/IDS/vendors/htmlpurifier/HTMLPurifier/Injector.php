<?php

/**
 * Injects tokens into the document while parsing for well-formedness.
 * This enables "formatter-like" functionality such as auto-paragraphing,
 * smiley-ification and linkification to take place.
 * 
 * A note on how handlers create changes; this is done by assigning a new
 * value to the $token reference. These values can take a variety of forms and
 * are best described HTMLPurifier_Strategy_MakeWellFormed->processToken()
 * documentation.
 * 
 * @todo Allow injectors to request a re-run on their output. This 
 *       would help if an operation is recursive.
 */
abstract class HTMLPurifier_Injector
{
    
    /**
     * Advisory name of injector, this is for friendly error messages
     */
    public $name;
    
    /**
     * Instance of HTMLPurifier_HTMLDefinition
     */
    protected $htmlDefinition;
    
    /**
     * Reference to CurrentNesting variable in Context. This is an array
     * list of tokens that we are currently "inside"
     */
    protected $currentNesting;
    
    /**
     * Reference to InputTokens variable in Context. This is an array
     * list of the input tokens that are being processed.
     */
    protected $inputTokens;
    
    /**
     * Reference to InputIndex variable in Context. This is an integer
     * array index for $this->inputTokens that indicates what token
     * is currently being processed.
     */
    protected $inputIndex;
    
    /**
     * Array of elements and attributes this injector creates and therefore
     * need to be allowed by the definition. Takes form of
     * array('element' => array('attr', 'attr2'), 'element2')
     */
    public $needed = array();
    
    /**
     * Index of inputTokens to rewind to.
     */
    protected $rewind = false;
    
    /**
     * Rewind to a spot to re-perform processing. This is useful if you
     * deleted a node, and now need to see if this change affected any
     * earlier nodes. Rewinding does not affect other injectors, and can
     * result in infinite loops if not used carefully.
     * @warning HTML Purifier will prevent you from fast-forwarding with this
     *          function.
     */
    public function rewind($index) {
        $this->rewind = $index;
    }
    
    /**
     * Retrieves rewind, and then unsets it.
     */
    public function getRewind() {
        $r = $this->rewind;
        $this->rewind = false;
        return $r;
    }
    
    /**
     * Prepares the injector by giving it the config and context objects:
     * this allows references to important variables to be made within
     * the injector. This function also checks if the HTML environment
     * will work with the Injector (see checkNeeded()).
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     * @return Boolean false if success, string of missing needed element/attribute if failure
     */
    public function prepare($config, $context) {
        $this->htmlDefinition = $config->getHTMLDefinition();
        // Even though this might fail, some unit tests ignore this and
        // still test checkNeeded, so be careful. Maybe get rid of that
        // dependency.
        $result = $this->checkNeeded($config);
        if ($result !== false) return $result;
        $this->currentNesting =& $context->get('CurrentNesting');
        $this->inputTokens    =& $context->get('InputTokens');
        $this->inputIndex     =& $context->get('InputIndex');
        return false;
    }
    
    /**
     * This function checks if the HTML environment
     * will work with the Injector: if p tags are not allowed, the
     * Auto-Paragraphing injector should not be enabled.
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     * @return Boolean false if success, string of missing needed element/attribute if failure
     */
    public function checkNeeded($config) {
        $def = $config->getHTMLDefinition();
        foreach ($this->needed as $element => $attributes) {
            if (is_int($element)) $element = $attributes;
            if (!isset($def->info[$element])) return $element;
            if (!is_array($attributes)) continue;
            foreach ($attributes as $name) {
                if (!isset($def->info[$element]->attr[$name])) return "$element.$name";
            }
        }
        return false;
    }
    
    /**
     * Tests if the context node allows a certain element
     * @param $name Name of element to test for
     * @return True if element is allowed, false if it is not
     */
    public function allowsElement($name) {
        if (!empty($this->currentNesting)) {
            $parent_token = array_pop($this->currentNesting);
            $this->currentNesting[] = $parent_token;
            $parent = $this->htmlDefinition->info[$parent_token->name];
        } else {
            $parent = $this->htmlDefinition->info_parent_def;
        }
        if (!isset($parent->child->elements[$name]) || isset($parent->excludes[$name])) {
            return false;
        }
        return true;
    }
    
    /**
     * Iterator function, which starts with the next token and continues until
     * you reach the end of the input tokens.
     * @warning Please prevent previous references from interfering with this
     *          functions by setting $i = null beforehand!
     * @param &$i Current integer index variable for inputTokens
     * @param &$current Current token variable. Do NOT use $token, as that variable is also a reference
     */
    protected function forward(&$i, &$current) {
        if ($i === null) $i = $this->inputIndex + 1;
        else $i++;
        if (!isset($this->inputTokens[$i])) return false;
        $current = $this->inputTokens[$i];
        return true;
    }
    
    /**
     * Similar to _forward, but accepts a third parameter $nesting (which
     * should be initialized at 0) and stops when we hit the end tag
     * for the node $this->inputIndex starts in.
     */
    protected function forwardUntilEndToken(&$i, &$current, &$nesting) {
        $result = $this->forward($i, $current);
        if (!$result) return false;
        if ($nesting === null) $nesting = 0;
        if     ($current instanceof HTMLPurifier_Token_Start) $nesting++;
        elseif ($current instanceof HTMLPurifier_Token_End) {
            if ($nesting <= 0) return false;
            $nesting--;
        }
        return true;
    }
    
    /**
     * Iterator function, starts with the previous token and continues until
     * you reach the beginning of input tokens.
     * @warning Please prevent previous references from interfering with this
     *          functions by setting $i = null beforehand!
     * @param &$i Current integer index variable for inputTokens
     * @param &$current Current token variable. Do NOT use $token, as that variable is also a reference
     */
    protected function backward(&$i, &$current) {
        if ($i === null) $i = $this->inputIndex - 1;
        else $i--;
        if ($i < 0) return false;
        $current = $this->inputTokens[$i];
        return true;
    }
    
    /**
     * Initializes the iterator at the current position. Use in a do {} while;
     * loop to force the _forward and _backward functions to start at the
     * current location.
     * @warning Please prevent previous references from interfering with this
     *          functions by setting $i = null beforehand!
     * @param &$i Current integer index variable for inputTokens
     * @param &$current Current token variable. Do NOT use $token, as that variable is also a reference
     */
    protected function current(&$i, &$current) {
        if ($i === null) $i = $this->inputIndex;
        $current = $this->inputTokens[$i];
    }
    
    /**
     * Handler that is called when a text token is processed
     */
    public function handleText(&$token) {}
    
    /**
     * Handler that is called when a start or empty token is processed
     */
    public function handleElement(&$token) {}
    
    /**
     * Handler that is called when an end token is processed
     */
    public function handleEnd(&$token) {
        $this->notifyEnd($token);
    }
    
    /**
     * Notifier that is called when an end token is processed
     * @note This differs from handlers in that the token is read-only
     * @deprecated
     */
    public function notifyEnd($token) {}
    
    
}

