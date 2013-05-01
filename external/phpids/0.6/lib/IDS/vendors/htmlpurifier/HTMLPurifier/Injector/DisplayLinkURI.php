<?php

/**
 * Injector that displays the URL of an anchor instead of linking to it, in addition to showing the text of the link.
 */
class HTMLPurifier_Injector_DisplayLinkURI extends HTMLPurifier_Injector
{
    
    public $name = 'DisplayLinkURI';
    public $needed = array('a');
    
    public function handleElement(&$token) {
    }
    
    public function handleEnd(&$token) {
        if (isset($token->start->attr['href'])){
            $url = $token->start->attr['href'];
            unset($token->start->attr['href']);
            $token = array($token, new HTMLPurifier_Token_Text(" ($url)"));
        } else {
            // nothing to display
        }
    }
}