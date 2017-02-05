<?php

/**
 * Adds important param elements to inside of object in order to make
 * things safe.
 */
class HTMLPurifier_Injector_SafeObject extends HTMLPurifier_Injector
{
    public $name = 'SafeObject';
    public $needed = array('object', 'param');

    protected $objectStack = array();
    protected $paramStack  = array();

    // Keep this synchronized with AttrTransform/SafeParam.php
    protected $addParam = array(
        'allowScriptAccess' => 'never',
        'allowNetworking' => 'internal',
    );
    protected $allowedParam = array(
        'wmode' => true,
        'movie' => true,
        'flashvars' => true,
        'src' => true,
        'allowFullScreen' => true, // if omitted, assume to be 'false'
    );

    public function prepare($config, $context) {
        parent::prepare($config, $context);
    }

    public function handleElement(&$token) {
        if ($token->name == 'object') {
            $this->objectStack[] = $token;
            $this->paramStack[] = array();
            $new = array($token);
            foreach ($this->addParam as $name => $value) {
                $new[] = new HTMLPurifier_Token_Empty('param', array('name' => $name, 'value' => $value));
            }
            $token = $new;
        } elseif ($token->name == 'param') {
            $nest = count($this->currentNesting) - 1;
            if ($nest >= 0 && $this->currentNesting[$nest]->name === 'object') {
                $i = count($this->objectStack) - 1;
                if (!isset($token->attr['name'])) {
                    $token = false;
                    return;
                }
                $n = $token->attr['name'];
                // We need this fix because YouTube doesn't supply a data
                // attribute, which we need if a type is specified. This is
                // *very* Flash specific.
                if (!isset($this->objectStack[$i]->attr['data']) &&
                    ($token->attr['name'] == 'movie' || $token->attr['name'] == 'src')) {
                    $this->objectStack[$i]->attr['data'] = $token->attr['value'];
                }
                // Check if the parameter is the correct value but has not
                // already been added
                if (
                    !isset($this->paramStack[$i][$n]) &&
                    isset($this->addParam[$n]) &&
                    $token->attr['name'] === $this->addParam[$n]
                ) {
                    // keep token, and add to param stack
                    $this->paramStack[$i][$n] = true;
                } elseif (isset($this->allowedParam[$n])) {
                    // keep token, don't do anything to it
                    // (could possibly check for duplicates here)
                } else {
                    $token = false;
                }
            } else {
                // not directly inside an object, DENY!
                $token = false;
            }
        }
    }

    public function handleEnd(&$token) {
        // This is the WRONG way of handling the object and param stacks;
        // we should be inserting them directly on the relevant object tokens
        // so that the global stack handling handles it.
        if ($token->name == 'object') {
            array_pop($this->objectStack);
            array_pop($this->paramStack);
        }
    }

}

// vim: et sw=4 sts=4
