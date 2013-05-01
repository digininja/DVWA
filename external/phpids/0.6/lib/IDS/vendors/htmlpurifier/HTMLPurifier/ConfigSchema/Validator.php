<?php

/**
 * Performs validations on HTMLPurifier_ConfigSchema_Interchange
 *
 * @note If you see '// handled by InterchangeBuilder', that means a
 *       design decision in that class would prevent this validation from
 *       ever being necessary. We have them anyway, however, for
 *       redundancy.
 */
class HTMLPurifier_ConfigSchema_Validator
{
    
    /**
     * Easy to access global objects.
     */
    protected $interchange, $aliases;
    
    /**
     * Context-stack to provide easy to read error messages.
     */
    protected $context = array();
    
    /**
     * HTMLPurifier_VarParser to test default's type.
     */
    protected $parser;
    
    public function __construct() {
        $this->parser = new HTMLPurifier_VarParser();
    }
    
    /**
     * Validates a fully-formed interchange object. Throws an
     * HTMLPurifier_ConfigSchema_Exception if there's a problem.
     */
    public function validate($interchange) {
        $this->interchange = $interchange;
        $this->aliases = array();
        // PHP is a bit lax with integer <=> string conversions in
        // arrays, so we don't use the identical !== comparison
        foreach ($interchange->namespaces as $i => $namespace) {
            if ($i != $namespace->namespace) $this->error(false, "Integrity violation: key '$i' does not match internal id '{$namespace->namespace}'");
            $this->validateNamespace($namespace);
        }
        foreach ($interchange->directives as $i => $directive) {
            $id = $directive->id->toString();
            if ($i != $id) $this->error(false, "Integrity violation: key '$i' does not match internal id '$id'");
            $this->validateDirective($directive);
        }
        return true;
    }
    
    /**
     * Validates a HTMLPurifier_ConfigSchema_Interchange_Namespace object.
     */
    public function validateNamespace($n) {
        $this->context[] = "namespace '{$n->namespace}'";
        $this->with($n, 'namespace')
            ->assertNotEmpty()
            ->assertAlnum(); // implicit assertIsString handled by InterchangeBuilder
        $this->with($n, 'description')
            ->assertNotEmpty()
            ->assertIsString(); // handled by InterchangeBuilder
        array_pop($this->context);
    }
    
    /**
     * Validates a HTMLPurifier_ConfigSchema_Interchange_Id object.
     */
    public function validateId($id) {
        $id_string = $id->toString();
        $this->context[] = "id '$id_string'";
        if (!$id instanceof HTMLPurifier_ConfigSchema_Interchange_Id) {
            // handled by InterchangeBuilder
            $this->error(false, 'is not an instance of HTMLPurifier_ConfigSchema_Interchange_Id');
        }
        if (!isset($this->interchange->namespaces[$id->namespace])) {
            $this->error('namespace', 'does not exist'); // assumes that the namespace was validated already
        }
        $this->with($id, 'directive')
            ->assertNotEmpty()
            ->assertAlnum(); // implicit assertIsString handled by InterchangeBuilder
        array_pop($this->context);
    }
    
    /**
     * Validates a HTMLPurifier_ConfigSchema_Interchange_Directive object.
     */
    public function validateDirective($d) {
        $id = $d->id->toString();
        $this->context[] = "directive '$id'";
        $this->validateId($d->id);
        
        $this->with($d, 'description')
            ->assertNotEmpty();
        
        // BEGIN - handled by InterchangeBuilder
        $this->with($d, 'type')
            ->assertNotEmpty();
        $this->with($d, 'typeAllowsNull')
            ->assertIsBool();
        try {
            // This also tests validity of $d->type
            $this->parser->parse($d->default, $d->type, $d->typeAllowsNull);
        } catch (HTMLPurifier_VarParserException $e) {
            $this->error('default', 'had error: ' . $e->getMessage());
        }
        // END - handled by InterchangeBuilder
        
        if (!is_null($d->allowed) || !empty($d->valueAliases)) {
            // allowed and valueAliases require that we be dealing with
            // strings, so check for that early.
            $d_int = HTMLPurifier_VarParser::$types[$d->type];
            if (!isset(HTMLPurifier_VarParser::$stringTypes[$d_int])) {
                $this->error('type', 'must be a string type when used with allowed or value aliases');
            }
        }
        
        $this->validateDirectiveAllowed($d);
        $this->validateDirectiveValueAliases($d);
        $this->validateDirectiveAliases($d);
        
        array_pop($this->context);
    }
    
    /**
     * Extra validation if $allowed member variable of
     * HTMLPurifier_ConfigSchema_Interchange_Directive is defined.
     */
    public function validateDirectiveAllowed($d) {
        if (is_null($d->allowed)) return;
        $this->with($d, 'allowed')
            ->assertNotEmpty()
            ->assertIsLookup(); // handled by InterchangeBuilder
        if (is_string($d->default) && !isset($d->allowed[$d->default])) {
            $this->error('default', 'must be an allowed value');
        }
        $this->context[] = 'allowed';
        foreach ($d->allowed as $val => $x) {
            if (!is_string($val)) $this->error("value $val", 'must be a string');
        }
        array_pop($this->context);
    }
    
    /**
     * Extra validation if $valueAliases member variable of
     * HTMLPurifier_ConfigSchema_Interchange_Directive is defined.
     */
    public function validateDirectiveValueAliases($d) {
        if (is_null($d->valueAliases)) return;
        $this->with($d, 'valueAliases')
            ->assertIsArray(); // handled by InterchangeBuilder
        $this->context[] = 'valueAliases';
        foreach ($d->valueAliases as $alias => $real) {
            if (!is_string($alias)) $this->error("alias $alias", 'must be a string');
            if (!is_string($real))  $this->error("alias target $real from alias '$alias'",  'must be a string');
            if ($alias === $real) {
                $this->error("alias '$alias'", "must not be an alias to itself");
            }
        }
        if (!is_null($d->allowed)) {
            foreach ($d->valueAliases as $alias => $real) {
                if (isset($d->allowed[$alias])) {
                    $this->error("alias '$alias'", 'must not be an allowed value');
                } elseif (!isset($d->allowed[$real])) {
                    $this->error("alias '$alias'", 'must be an alias to an allowed value');
                }
            }
        }
        array_pop($this->context);
    }
    
    /**
     * Extra validation if $aliases member variable of
     * HTMLPurifier_ConfigSchema_Interchange_Directive is defined.
     */
    public function validateDirectiveAliases($d) {
        $this->with($d, 'aliases')
            ->assertIsArray(); // handled by InterchangeBuilder
        $this->context[] = 'aliases';
        foreach ($d->aliases as $alias) {
            $this->validateId($alias);
            $s = $alias->toString();
            if (isset($this->interchange->directives[$s])) {
                $this->error("alias '$s'", 'collides with another directive');
            }
            if (isset($this->aliases[$s])) {
                $other_directive = $this->aliases[$s];
                $this->error("alias '$s'", "collides with alias for directive '$other_directive'");
            }
            $this->aliases[$s] = $d->id->toString();
        }
        array_pop($this->context);
    }
    
    // protected helper functions
    
    /**
     * Convenience function for generating HTMLPurifier_ConfigSchema_ValidatorAtom
     * for validating simple member variables of objects.
     */
    protected function with($obj, $member) {
        return new HTMLPurifier_ConfigSchema_ValidatorAtom($this->getFormattedContext(), $obj, $member);
    }
    
    /**
     * Emits an error, providing helpful context.
     */
    protected function error($target, $msg) {
        if ($target !== false) $prefix = ucfirst($target) . ' in ' .  $this->getFormattedContext();
        else $prefix = ucfirst($this->getFormattedContext());
        throw new HTMLPurifier_ConfigSchema_Exception(trim($prefix . ' ' . $msg));
    }
    
    /**
     * Returns a formatted context string.
     */
    protected function getFormattedContext() {
        return implode(' in ', array_reverse($this->context));
    }
    
}
