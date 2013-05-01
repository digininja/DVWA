<?php

/**
 * Takes a well formed list of tokens and fixes their nesting.
 * 
 * HTML elements dictate which elements are allowed to be their children,
 * for example, you can't have a p tag in a span tag.  Other elements have
 * much more rigorous definitions: tables, for instance, require a specific
 * order for their elements.  There are also constraints not expressible by
 * document type definitions, such as the chameleon nature of ins/del
 * tags and global child exclusions.
 * 
 * The first major objective of this strategy is to iterate through all the
 * nodes (not tokens) of the list of tokens and determine whether or not
 * their children conform to the element's definition.  If they do not, the
 * child definition may optionally supply an amended list of elements that
 * is valid or require that the entire node be deleted (and the previous
 * node rescanned).
 * 
 * The second objective is to ensure that explicitly excluded elements of
 * an element do not appear in its children.  Code that accomplishes this
 * task is pervasive through the strategy, though the two are distinct tasks
 * and could, theoretically, be seperated (although it's not recommended).
 * 
 * @note Whether or not unrecognized children are silently dropped or
 *       translated into text depends on the child definitions.
 * 
 * @todo Enable nodes to be bubbled out of the structure.
 */

class HTMLPurifier_Strategy_FixNesting extends HTMLPurifier_Strategy
{
    
    public function execute($tokens, $config, $context) {
        //####################################################################//
        // Pre-processing
        
        // get a copy of the HTML definition
        $definition = $config->getHTMLDefinition();
        
        // insert implicit "parent" node, will be removed at end.
        // DEFINITION CALL
        $parent_name = $definition->info_parent;
        array_unshift($tokens, new HTMLPurifier_Token_Start($parent_name));
        $tokens[] = new HTMLPurifier_Token_End($parent_name);
        
        // setup the context variable 'IsInline', for chameleon processing
        // is 'false' when we are not inline, 'true' when it must always
        // be inline, and an integer when it is inline for a certain
        // branch of the document tree
        $is_inline = $definition->info_parent_def->descendants_are_inline;
        $context->register('IsInline', $is_inline);
        
        // setup error collector
        $e =& $context->get('ErrorCollector', true);
        
        //####################################################################//
        // Loop initialization
        
        // stack that contains the indexes of all parents,
        // $stack[count($stack)-1] being the current parent
        $stack = array();
        
        // stack that contains all elements that are excluded
        // it is organized by parent elements, similar to $stack, 
        // but it is only populated when an element with exclusions is
        // processed, i.e. there won't be empty exclusions.
        $exclude_stack = array();
        
        // variable that contains the start token while we are processing
        // nodes. This enables error reporting to do its job
        $start_token = false;
        $context->register('CurrentToken', $start_token);
        
        //####################################################################//
        // Loop
        
        // iterate through all start nodes. Determining the start node
        // is complicated so it has been omitted from the loop construct
        for ($i = 0, $size = count($tokens) ; $i < $size; ) {
            
            //################################################################//
            // Gather information on children
            
            // child token accumulator
            $child_tokens = array();
            
            // scroll to the end of this node, report number, and collect
            // all children
            for ($j = $i, $depth = 0; ; $j++) {
                if ($tokens[$j] instanceof HTMLPurifier_Token_Start) {
                    $depth++;
                    // skip token assignment on first iteration, this is the
                    // token we currently are on
                    if ($depth == 1) continue;
                } elseif ($tokens[$j] instanceof HTMLPurifier_Token_End) {
                    $depth--;
                    // skip token assignment on last iteration, this is the
                    // end token of the token we're currently on
                    if ($depth == 0) break;
                }
                $child_tokens[] = $tokens[$j];
            }
            
            // $i is index of start token
            // $j is index of end token
            
            $start_token = $tokens[$i]; // to make token available via CurrentToken
            
            //################################################################//
            // Gather information on parent
            
            // calculate parent information
            if ($count = count($stack)) {
                $parent_index = $stack[$count-1];
                $parent_name  = $tokens[$parent_index]->name;
                if ($parent_index == 0) {
                    $parent_def   = $definition->info_parent_def;
                } else {
                    $parent_def   = $definition->info[$parent_name];
                }
            } else {
                // processing as if the parent were the "root" node
                // unknown info, it won't be used anyway, in the future,
                // we may want to enforce one element only (this is 
                // necessary for HTML Purifier to clean entire documents
                $parent_index = $parent_name = $parent_def = null;
            }
            
            // calculate context
            if ($is_inline === false) {
                // check if conditions make it inline
                if (!empty($parent_def) && $parent_def->descendants_are_inline) {
                    $is_inline = $count - 1;
                }
            } else {
                // check if we're out of inline
                if ($count === $is_inline) {
                    $is_inline = false;
                }
            }
            
            //################################################################//
            // Determine whether element is explicitly excluded SGML-style
            
            // determine whether or not element is excluded by checking all
            // parent exclusions. The array should not be very large, two
            // elements at most.
            $excluded = false;
            if (!empty($exclude_stack)) {
                foreach ($exclude_stack as $lookup) {
                    if (isset($lookup[$tokens[$i]->name])) {
                        $excluded = true;
                        // no need to continue processing
                        break;
                    }
                }
            }
            
            //################################################################//
            // Perform child validation
            
            if ($excluded) {
                // there is an exclusion, remove the entire node
                $result = false;
                $excludes = array(); // not used, but good to initialize anyway
            } else {
                // DEFINITION CALL
                if ($i === 0) {
                    // special processing for the first node
                    $def = $definition->info_parent_def;
                } else {
                    $def = $definition->info[$tokens[$i]->name];
                    
                }
                
                if (!empty($def->child)) {
                    // have DTD child def validate children
                    $result = $def->child->validateChildren(
                        $child_tokens, $config, $context);
                } else {
                    // weird, no child definition, get rid of everything
                    $result = false;
                }
                
                // determine whether or not this element has any exclusions
                $excludes = $def->excludes;
            }
            
            // $result is now a bool or array
            
            //################################################################//
            // Process result by interpreting $result
            
            if ($result === true || $child_tokens === $result) {
                // leave the node as is
                
                // register start token as a parental node start
                $stack[] = $i;
                
                // register exclusions if there are any
                if (!empty($excludes)) $exclude_stack[] = $excludes;
                
                // move cursor to next possible start node
                $i++;
                
            } elseif($result === false) {
                // remove entire node
                
                if ($e) {
                    if ($excluded) {
                        $e->send(E_ERROR, 'Strategy_FixNesting: Node excluded');
                    } else {
                        $e->send(E_ERROR, 'Strategy_FixNesting: Node removed');
                    }
                }
                
                // calculate length of inner tokens and current tokens
                $length = $j - $i + 1;
                
                // perform removal
                array_splice($tokens, $i, $length);
                
                // update size
                $size -= $length;
                
                // there is no start token to register,
                // current node is now the next possible start node
                // unless it turns out that we need to do a double-check
                
                // this is a rought heuristic that covers 100% of HTML's
                // cases and 99% of all other cases. A child definition
                // that would be tricked by this would be something like:
                // ( | a b c) where it's all or nothing. Fortunately,
                // our current implementation claims that that case would
                // not allow empty, even if it did
                if (!$parent_def->child->allow_empty) {
                    // we need to do a double-check
                    $i = $parent_index;
                    array_pop($stack);
                }
                
                // PROJECTED OPTIMIZATION: Process all children elements before
                // reprocessing parent node.
                
            } else {
                // replace node with $result
                
                // calculate length of inner tokens
                $length = $j - $i - 1;
                
                if ($e) {
                    if (empty($result) && $length) {
                        $e->send(E_ERROR, 'Strategy_FixNesting: Node contents removed');
                    } else {
                        $e->send(E_WARNING, 'Strategy_FixNesting: Node reorganized');
                    }
                }
                
                // perform replacement
                array_splice($tokens, $i + 1, $length, $result);
                
                // update size
                $size -= $length;
                $size += count($result);
                
                // register start token as a parental node start
                $stack[] = $i;
                
                // register exclusions if there are any
                if (!empty($excludes)) $exclude_stack[] = $excludes;
                
                // move cursor to next possible start node
                $i++;
                
            }
            
            //################################################################//
            // Scroll to next start node
            
            // We assume, at this point, that $i is the index of the token
            // that is the first possible new start point for a node.
            
            // Test if the token indeed is a start tag, if not, move forward
            // and test again.
            $size = count($tokens);
            while ($i < $size and !$tokens[$i] instanceof HTMLPurifier_Token_Start) {
                if ($tokens[$i] instanceof HTMLPurifier_Token_End) {
                    // pop a token index off the stack if we ended a node
                    array_pop($stack);
                    // pop an exclusion lookup off exclusion stack if
                    // we ended node and that node had exclusions
                    if ($i == 0 || $i == $size - 1) {
                        // use specialized var if it's the super-parent
                        $s_excludes = $definition->info_parent_def->excludes;
                    } else {
                        $s_excludes = $definition->info[$tokens[$i]->name]->excludes;
                    }
                    if ($s_excludes) {
                        array_pop($exclude_stack);
                    }
                }
                $i++;
            }
            
        }
        
        //####################################################################//
        // Post-processing
        
        // remove implicit parent tokens at the beginning and end
        array_shift($tokens);
        array_pop($tokens);
        
        // remove context variables
        $context->destroy('IsInline');
        $context->destroy('CurrentToken');
        
        //####################################################################//
        // Return
        
        return $tokens;
        
    }
    
}


