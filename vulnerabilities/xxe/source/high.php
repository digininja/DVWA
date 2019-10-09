<?php

function XXE(){
        error_reporting(E_ALL);
        ini_set("display_errors", 1);

        libxml_disable_entity_loader (false);
        $xmlcontent = file_get_contents('php://input');

        $dom = new DOMDocument();
        $dom->loadXML($xmlcontent, LIBXML_NOENT | LIBXML_DTDLOAD);
        $dom->xinclude();

        echo $dom->saveXML();
}
?>
    