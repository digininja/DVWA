<?php

/**
 * Structure that stores an HTML element definition. Used by
 * HTMLPurifier_HTMLDefinition and HTMLPurifier_HTMLModule.
 * @note This class is inspected by HTMLPurifier_Printer_HTMLDefinition.
 *       Please update that class too.
 * @warning If you add new properties to this class, you MUST update
 *          the mergeIn() method.
 */
class HTMLPurifier_ElementDef
{

    /**
     * Does the definition work by itself, or is it created solely
     * for the purpose of merging into another definition?
     */
    public $standalone = true;

    /**
     * Associative array of attribute name to HTMLPurifier_AttrDef
     * @note Before being processed by HTMLPurifier_AttrCollections
     *       when modules are finalized during
     *       HTMLPurifier_HTMLDefinition->setup(), this array may also
     *       contain an array at index 0 that indicates which attribute
     *       collections to load into the full array. It may also
     *       contain string indentifiers in lieu of HTMLPurifier_AttrDef,
     *       see HTMLPurifier_AttrTypes on how they are expanded during
     *       HTMLPurifier_HTMLDefinition->setup() processing.
     */
    public $attr = array();

    /**
     * Indexed list of tag's HTMLPurifier_AttrTransform to be done before validation
     */
    public $attr_transform_pre = array();

    /**
     * Indexed list of tag's HTMLPurifier_AttrTransform to be done after validation
     */
    public $attr_transform_post = array();

    /**
     * HTMLPurifier_ChildDef of this tag.
     */
    public $child;

    /**
     * Abstract string representation of internal ChildDef rules. See
     * HTMLPurifier_ContentSets for how this is parsed and then transformed
     * into an HTMLPurifier_ChildDef.
     * @warning This is a temporary variable that is not available after
     *      being processed by HTMLDefinition
     */
    public $content_model;

    /**
     * Value of $child->type, used to determine which ChildDef to use,
     * used in combination with $content_model.
     * @warning This must be lowercase
     * @warning This is a temporary variable that is not available after
     *      being processed by HTMLDefinition
     */
    public $content_model_type;



    /**
     * Does the element have a content model (#PCDATA | Inline)*? This
     * is important for chameleon ins and del processing in
     * HTMLPurifier_ChildDef_Chameleon. Dynamically set: modules don't
     * have to worry about this one.
     */
    public $descendants_are_inline = false;

    /**
     * List of the names of required attributes this element has. Dynamically
     * populated by HTMLPurifier_HTMLDefinition::getElement
     */
    public $required_attr = array();

    /**
     * Lookup table of tags excluded from all descendants of this tag.
     * @note SGML permits exclusions for all descendants, but this is
     *       not possible with DTDs or XML Schemas. W3C has elected to
     *       use complicated compositions of content_models to simulate
     *       exclusion for children, but we go the simpler, SGML-style
     *       route of flat-out exclusions, which correctly apply to
     *       all descendants and not just children. Note that the XHTML
     *       Modularization Abstract Modules are blithely unaware of such
     *       distinctions.
     */
    public $excludes = array();

    /**
     * This tag is explicitly auto-closed by the following tags.
     */
    public $autoclose = array();

    /**
     * If a foreign element is found in this element, test if it is
     * allowed by this sub-element; if it is, instead of closing the
     * current element, place it inside this element.
     */
    public $wrap;

    /**
     * Whether or not this is a formatting element affected by the
     * "Active Formatting Elements" algorithm.
     */
    public $formatting;

    /**
     * Low-level factory constructor for creating new standalone element defs
     */
    public static function create($content_model, $content_model_type, $attr) {
        $def = new HTMLPurifier_ElementDef();
        $def->content_model = $content_model;
        $def->content_model_type = $content_model_type;
        $def->attr = $attr;
        return $def;
    }

    /**
     * Merges the values of another element definition into this one.
     * Values from the new element def take precedence if a value is
     * not mergeable.
     */
    public function mergeIn($def) {

        // later keys takes precedence
        foreach($def->attr as $k => $v) {
            if ($k === 0) {
                // merge in the includes
                // sorry, no way to override an include
                foreach ($v as $v2) {
                    $this->attr[0][] = $v2;
                }
                continue;
            }
            if ($v === false) {
                if (isset($this->attr[$k])) unset($this->attr[$k]);
                continue;
            }
            $this->attr[$k] = $v;
        }
        $this->_mergeAssocArray($this->attr_transform_pre, $def->attr_transform_pre);
        $this->_mergeAssocArray($this->attr_transform_post, $def->attr_transform_post);
        $this->_mergeAssocArray($this->excludes, $def->excludes);

        if(!empty($def->content_model)) {
            $this->content_model =
                str_replace("#SUPER", $this->content_model, $def->content_model);
            $this->child = false;
        }
        if(!empty($def->content_model_type)) {
            $this->content_model_type = $def->content_model_type;
            $this->child = false;
        }
        if(!is_null($def->child)) $this->child = $def->child;
        if(!is_null($def->formatting)) $this->formatting = $def->formatting;
        if($def->descendants_are_inline) $this->descendants_are_inline = $def->descendants_are_inline;

    }

    /**
     * Merges one array into another, removes values which equal false
     * @param $a1 Array by reference that is merged into
     * @param $a2 Array that merges into $a1
     */
    private function _mergeAssocArray(&$a1, $a2) {
        foreach ($a2 as $k => $v) {
            if ($v === false) {
                if (isset($a1[$k])) unset($a1[$k]);
                continue;
            }
            $a1[$k] = $v;
        }
    }

}

// vim: et sw=4 sts=4
