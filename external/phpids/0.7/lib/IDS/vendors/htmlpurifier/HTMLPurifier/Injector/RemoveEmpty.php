<?php

class HTMLPurifier_Injector_RemoveEmpty extends HTMLPurifier_Injector
{

    private $context, $config, $attrValidator, $removeNbsp, $removeNbspExceptions;

    public function prepare($config, $context) {
        parent::prepare($config, $context);
        $this->config = $config;
        $this->context = $context;
        $this->removeNbsp = $config->get('AutoFormat.RemoveEmpty.RemoveNbsp');
        $this->removeNbspExceptions = $config->get('AutoFormat.RemoveEmpty.RemoveNbsp.Exceptions');
        $this->attrValidator = new HTMLPurifier_AttrValidator();
    }

    public function handleElement(&$token) {
        if (!$token instanceof HTMLPurifier_Token_Start) return;
        $next = false;
        for ($i = $this->inputIndex + 1, $c = count($this->inputTokens); $i < $c; $i++) {
            $next = $this->inputTokens[$i];
            if ($next instanceof HTMLPurifier_Token_Text) {
                if ($next->is_whitespace) continue;
                if ($this->removeNbsp && !isset($this->removeNbspExceptions[$token->name])) {
                    $plain = str_replace("\xC2\xA0", "", $next->data);
                    $isWsOrNbsp = $plain === '' || ctype_space($plain);
                    if ($isWsOrNbsp) continue;
                }
            }
            break;
        }
        if (!$next || ($next instanceof HTMLPurifier_Token_End && $next->name == $token->name)) {
            if ($token->name == 'colgroup') return;
            $this->attrValidator->validateToken($token, $this->config, $this->context);
            $token->armor['ValidateAttributes'] = true;
            if (isset($token->attr['id']) || isset($token->attr['name'])) return;
            $token = $i - $this->inputIndex + 1;
            for ($b = $this->inputIndex - 1; $b > 0; $b--) {
                $prev = $this->inputTokens[$b];
                if ($prev instanceof HTMLPurifier_Token_Text && $prev->is_whitespace) continue;
                break;
            }
            // This is safe because we removed the token that triggered this.
            $this->rewind($b - 1);
            return;
        }
    }

}

// vim: et sw=4 sts=4
