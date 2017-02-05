<?php

/**
 * Takes the contents of blockquote when in strict and reformats for validation.
 */
class HTMLPurifier_ChildDef_StrictBlockquote extends HTMLPurifier_ChildDef_Required
{
    protected $real_elements;
    protected $fake_elements;
    public $allow_empty = true;
    public $type = 'strictblockquote';
    protected $init = false;

    /**
     * @note We don't want MakeWellFormed to auto-close inline elements since
     *       they might be allowed.
     */
    public function getAllowedElements($config) {
        $this->init($config);
        return $this->fake_elements;
    }

    public function validateChildren($tokens_of_children, $config, $context) {

        $this->init($config);

        // trick the parent class into thinking it allows more
        $this->elements = $this->fake_elements;
        $result = parent::validateChildren($tokens_of_children, $config, $context);
        $this->elements = $this->real_elements;

        if ($result === false) return array();
        if ($result === true) $result = $tokens_of_children;

        $def = $config->getHTMLDefinition();
        $block_wrap_start = new HTMLPurifier_Token_Start($def->info_block_wrapper);
        $block_wrap_end   = new HTMLPurifier_Token_End(  $def->info_block_wrapper);
        $is_inline = false;
        $depth = 0;
        $ret = array();

        // assuming that there are no comment tokens
        foreach ($result as $i => $token) {
            $token = $result[$i];
            // ifs are nested for readability
            if (!$is_inline) {
                if (!$depth) {
                     if (
                        ($token instanceof HTMLPurifier_Token_Text && !$token->is_whitespace) ||
                        (!$token instanceof HTMLPurifier_Token_Text && !isset($this->elements[$token->name]))
                     ) {
                        $is_inline = true;
                        $ret[] = $block_wrap_start;
                     }
                }
            } else {
                if (!$depth) {
                    // starting tokens have been inline text / empty
                    if ($token instanceof HTMLPurifier_Token_Start || $token instanceof HTMLPurifier_Token_Empty) {
                        if (isset($this->elements[$token->name])) {
                            // ended
                            $ret[] = $block_wrap_end;
                            $is_inline = false;
                        }
                    }
                }
            }
            $ret[] = $token;
            if ($token instanceof HTMLPurifier_Token_Start) $depth++;
            if ($token instanceof HTMLPurifier_Token_End)   $depth--;
        }
        if ($is_inline) $ret[] = $block_wrap_end;
        return $ret;
    }

    private function init($config) {
        if (!$this->init) {
            $def = $config->getHTMLDefinition();
            // allow all inline elements
            $this->real_elements = $this->elements;
            $this->fake_elements = $def->info_content_sets['Flow'];
            $this->fake_elements['#PCDATA'] = true;
            $this->init = true;
        }
    }
}

// vim: et sw=4 sts=4
