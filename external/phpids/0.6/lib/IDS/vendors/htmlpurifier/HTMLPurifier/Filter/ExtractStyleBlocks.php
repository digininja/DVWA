<?php

/**
 * This filter extracts <style> blocks from input HTML, cleans them up
 * using CSSTidy, and then places them in $purifier->context->get('StyleBlocks')
 * so they can be used elsewhere in the document.
 * 
 * @note
 *      See tests/HTMLPurifier/Filter/ExtractStyleBlocksTest.php for
 *      sample usage.
 * 
 * @note
 *      This filter can also be used on stylesheets not included in the
 *      document--something purists would probably prefer. Just directly
 *      call HTMLPurifier_Filter_ExtractStyleBlocks->cleanCSS()
 */
class HTMLPurifier_Filter_ExtractStyleBlocks extends HTMLPurifier_Filter
{
    
    public $name = 'ExtractStyleBlocks';
    private $_styleMatches = array();
    private $_tidy;
    
    public function __construct() {
        $this->_tidy = new csstidy();
    }
    
    /**
     * Save the contents of CSS blocks to style matches
     * @param $matches preg_replace style $matches array
     */
    protected function styleCallback($matches) {
        $this->_styleMatches[] = $matches[1];
    }
    
    /**
     * Removes inline <style> tags from HTML, saves them for later use
     * @todo Extend to indicate non-text/css style blocks
     */
    public function preFilter($html, $config, $context) {
        $tidy = $config->get('FilterParam', 'ExtractStyleBlocksTidyImpl');
        if ($tidy !== null) $this->_tidy = $tidy;
        $html = preg_replace_callback('#<style(?:\s.*)?>(.+)</style>#isU', array($this, 'styleCallback'), $html);
        $style_blocks = $this->_styleMatches;
        $this->_styleMatches = array(); // reset
        $context->register('StyleBlocks', $style_blocks); // $context must not be reused
        if ($this->_tidy) {
            foreach ($style_blocks as &$style) {
                $style = $this->cleanCSS($style, $config, $context);
            }
        }
        return $html;
    }
    
    /**
     * Takes CSS (the stuff found in <style>) and cleans it.
     * @warning Requires CSSTidy <http://csstidy.sourceforge.net/>
     * @param $css     CSS styling to clean
     * @param $config  Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     * @return Cleaned CSS
     */
    public function cleanCSS($css, $config, $context) {
        // prepare scope
        $scope = $config->get('FilterParam', 'ExtractStyleBlocksScope');
        if ($scope !== null) {
            $scopes = array_map('trim', explode(',', $scope));
        } else {
            $scopes = array();
        }
        // remove comments from CSS
        $css = trim($css);
        if (strncmp('<!--', $css, 4) === 0) {
            $css = substr($css, 4);
        }
        if (strlen($css) > 3 && substr($css, -3) == '-->') {
            $css = substr($css, 0, -3);
        }
        $css = trim($css);
        $this->_tidy->parse($css);
        $css_definition = $config->getDefinition('CSS');
        foreach ($this->_tidy->css as $k => $decls) {
            // $decls are all CSS declarations inside an @ selector
            $new_decls = array();
            foreach ($decls as $selector => $style) {
                $selector = trim($selector);
                if ($selector === '') continue; // should not happen
                if ($selector[0] === '+') {
                    if ($selector !== '' && $selector[0] === '+') continue;
                }
                if (!empty($scopes)) {
                    $new_selector = array(); // because multiple ones are possible
                    $selectors = array_map('trim', explode(',', $selector));
                    foreach ($scopes as $s1) {
                        foreach ($selectors as $s2) {
                            $new_selector[] = "$s1 $s2";
                        }
                    }
                    $selector = implode(', ', $new_selector); // now it's a string
                }
                foreach ($style as $name => $value) {
                    if (!isset($css_definition->info[$name])) {
                        unset($style[$name]);
                        continue;
                    }
                    $def = $css_definition->info[$name];
                    $ret = $def->validate($value, $config, $context);
                    if ($ret === false) unset($style[$name]);
                    else $style[$name] = $ret;
                }
                $new_decls[$selector] = $style;
            }
            $this->_tidy->css[$k] = $new_decls;
        }
        // remove stuff that shouldn't be used, could be reenabled
        // after security risks are analyzed
        $this->_tidy->import = array();
        $this->_tidy->charset = null;
        $this->_tidy->namespace = null;
        $css = $this->_tidy->print->plain();
        // we are going to escape any special characters <>& to ensure
        // that no funny business occurs (i.e. </style> in a font-family prop).
        if ($config->get('FilterParam', 'ExtractStyleBlocksEscaping')) {
            $css = str_replace(
                array('<',    '>',    '&'),
                array('\3C ', '\3E ', '\26 '),
                $css
            );
        }
        return $css;
    }
    
}

