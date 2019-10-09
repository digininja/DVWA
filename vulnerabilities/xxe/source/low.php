<?php
function HandleXmlError($errno, $errstr, $errfile, $errline){
                    if ($errno==E_WARNING && (substr_count($errstr,"DOMDocument::loadXML()")>0))
                    {
                        throw new DOMException($errstr);
                    }
                    else
                        return false;
        }

function XXE(){
	$xmloutput = '';
	if( isset( $_FILES[ 'uploaded' ] ) ) {
		$filepath = $_FILES[ 'uploaded' ][ 'tmp_name' ];
		$content = file_get_contents($filepath);
		libxml_disable_entity_loader(false);
		set_error_handler('HandleXmlError');
		$dom = new DOMDocument();
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($content, LIBXML_NOENT | LIBXML_DTDLOAD);
		$xml = simplexml_import_dom($dom);
		restore_error_handler();
		if(!strlen(trim($xml->children())) ===0) { 
			echo $xml->title; 
			$xmloutput = "Cant be empty!";
		} else { 
		$xmloutput .= '
		<code>' . 'Title: '. htmlentities($xml->title) . '<br />' . 
		'Album: '. htmlentities($xml->album)  . '<br />' .  
		'Artist: '. htmlentities($xml->artist)  . '<br />' . 
		'</code>';
		}
	}
	return $xmloutput;

}
?>