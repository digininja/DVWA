<?php

/**
 * Validates the HTML attribute style, otherwise known as CSS.
 * @note We don't implement the whole CSS specification, so it might be
 *       difficult to reuse this component in the context of validating
 *       actual stylesheet declarations.
 * @note If we were really serious about validating the CSS, we would
 *       tokenize the styles and then parse the tokens. Obviously, we
 *       are not doing that. Doing that could seriously harm performance,
 *       but would make these components a lot more viable for a CSS
 *       filtering solution.
 */
class HTMLPurifier_AttrDef_CSS extends HTMLPurifier_AttrDef
{

    public function validate($css, $config, $context) {

        $css = $this->parseCDATA($css);

        $definition = $config->getCSSDefinition();

        // we're going to break the spec and explode by semicolons.
        // This is because semicolon rarely appears in escaped form
        // Doing this is generally flaky but fast
        // IT MIGHT APPEAR IN URIs, see HTMLPurifier_AttrDef_CSSURI
        // for details

        $declarations = explode(';', $css);
        $propvalues = array();

        /**
         * Name of the current CSS property being validated.
         */
        $property = false;
        $context->register('CurrentCSSProperty', $property);

        foreach ($declarations as $declaration) {
            if (!$declaration) continue;
            if (!strpos($declaration, ':')) continue;
            list($property, $value) = explode(':', $declaration, 2);
            $property = trim($property);
            $value    = trim($value);
            $ok = false;
            do {
                if (isset($definition->info[$property])) {
                    $ok = true;
                    break;
                }
                if (ctype_lower($property)) break;
                $property = strtolower($property);
                if (isset($definition->info[$property])) {
                    $ok = true;
                    break;
                }
            } while(0);
            if (!$ok) continue;
            // inefficient call, since the validator will do this again
            if (strtolower(trim($value)) !== 'inherit') {
                // inherit works for everything (but only on the base property)
                $result = $definition->info[$property]->validate(
                    $value, $config, $context );
            } else {
                $result = 'inherit';
            }
            if ($result === false) continue;
            $propvalues[$property] = $result;
        }

        $context->destroy('CurrentCSSProperty');

        // procedure does not write the new CSS simultaneously, so it's
        // slightly inefficient, but it's the only way of getting rid of
        // duplicates. Perhaps config to optimize it, but not now.

        $new_declarations = '';
        foreach ($propvalues as $prop => $value) {
            $new_declarations .= "$prop:$value;";
        }

        return $new_declarations ? $new_declarations : false;

    }

}

// vim: et sw=4 sts=4
