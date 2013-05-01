<?php

/**
 * Validates contents based on NMTOKENS attribute type.
 * @note The only current use for this is the class attribute in HTML
 * @note Could have some functionality factored out into Nmtoken class
 * @warning We cannot assume this class will be used only for 'class'
 *          attributes. Not sure how to hook in magic behavior, then.
 */
class HTMLPurifier_AttrDef_HTML_Nmtokens extends HTMLPurifier_AttrDef
{
    
    public function validate($string, $config, $context) {
        
        $string = trim($string);
        
        // early abort: '' and '0' (strings that convert to false) are invalid
        if (!$string) return false;
        
        // OPTIMIZABLE!
        // do the preg_match, capture all subpatterns for reformulation
        
        // we don't support U+00A1 and up codepoints or
        // escaping because I don't know how to do that with regexps
        // and plus it would complicate optimization efforts (you never
        // see that anyway).
        $matches = array();
        $pattern = '/(?:(?<=\s)|\A)'. // look behind for space or string start
                   '((?:--|-?[A-Za-z_])[A-Za-z_\-0-9]*)'.
                   '(?:(?=\s)|\z)/'; // look ahead for space or string end
        preg_match_all($pattern, $string, $matches);
        
        if (empty($matches[1])) return false;
        
        // reconstruct string
        $new_string = '';
        foreach ($matches[1] as $token) {
            $new_string .= $token . ' ';
        }
        $new_string = rtrim($new_string);
        
        return $new_string;
        
    }
    
}

