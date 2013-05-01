<?php

/**
 * Validates a font family list according to CSS spec
 * @todo whitelisting allowed fonts would be nice
 */
class HTMLPurifier_AttrDef_CSS_FontFamily extends HTMLPurifier_AttrDef
{
    
    public function validate($string, $config, $context) {
        static $generic_names = array(
            'serif' => true,
            'sans-serif' => true,
            'monospace' => true,
            'fantasy' => true,
            'cursive' => true
        );
        
        // assume that no font names contain commas in them
        $fonts = explode(',', $string);
        $final = '';
        foreach($fonts as $font) {
            $font = trim($font);
            if ($font === '') continue;
            // match a generic name
            if (isset($generic_names[$font])) {
                $final .= $font . ', ';
                continue;
            }
            // match a quoted name
            if ($font[0] === '"' || $font[0] === "'") {
                $length = strlen($font);
                if ($length <= 2) continue;
                $quote = $font[0];
                if ($font[$length - 1] !== $quote) continue;
                $font = substr($font, 1, $length - 2);
                
                $new_font = '';
                for ($i = 0, $c = strlen($font); $i < $c; $i++) {
                    if ($font[$i] === '\\') {
                        $i++;
                        if ($i >= $c) {
                            $new_font .= '\\';
                            break;
                        }
                        if (ctype_xdigit($font[$i])) {
                            $code = $font[$i];
                            for ($a = 1, $i++; $i < $c && $a < 6; $i++, $a++) {
                                if (!ctype_xdigit($font[$i])) break;
                                $code .= $font[$i];
                            }
                            // We have to be extremely careful when adding
                            // new characters, to make sure we're not breaking
                            // the encoding.
                            $char = HTMLPurifier_Encoder::unichr(hexdec($code));
                            if (HTMLPurifier_Encoder::cleanUTF8($char) === '') continue;
                            $new_font .= $char;
                            if ($i < $c && trim($font[$i]) !== '') $i--;
                            continue;
                        }
                        if ($font[$i] === "\n") continue;
                    }
                    $new_font .= $font[$i];
                }
                
                $font = $new_font;
            }
            // $font is a pure representation of the font name
            
            if (ctype_alnum($font) && $font !== '') {
                // very simple font, allow it in unharmed
                $final .= $font . ', ';
                continue;
            }
            
            // complicated font, requires quoting
            
            // armor single quotes and new lines
            $font = str_replace("\\", "\\\\", $font);
            $font = str_replace("'", "\\'", $font);
            $final .= "'$font', ";
        }
        $final = rtrim($final, ', ');
        if ($final === '') return false;
        return $final;
    }
    
}

