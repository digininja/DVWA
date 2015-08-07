<?php

/**
 * Definition that allows a set of elements, and allows no children.
 * @note This is a hack to reuse code from HTMLPurifier_ChildDef_Required,
 *       really, one shouldn't inherit from the other.  Only altered behavior
 *       is to overload a returned false with an array.  Thus, it will never
 *       return false.
 */
class HTMLPurifier_ChildDef_Optional extends HTMLPurifier_ChildDef_Required
{
    public $allow_empty = true;
    public $type = 'optional';
    public function validateChildren($tokens_of_children, $config, $context) {
        $result = parent::validateChildren($tokens_of_children, $config, $context);
        // we assume that $tokens_of_children is not modified
        if ($result === false) {
            if (empty($tokens_of_children)) return true;
            elseif ($this->whitespace) return $tokens_of_children;
            else return array();
        }
        return $result;
    }
}

// vim: et sw=4 sts=4
