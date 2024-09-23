<?php

function xor_this($cleartext, $key) {
    // Our output text
    $outText = '';

    // Iterate through each character
    for($i=0; $i<strlen($cleartext);) {
        for($j=0; ($j<strlen($key) && $i<strlen($cleartext)); $j++,$i++)
        {
            $outText .= $cleartext[$i] ^ $key[$j];
            //echo 'i=' . $i . ', ' . 'j=' . $j . ', ' . $outText{$i} . '<br />'; // For debugging
        }
    }
    return $outText;
}

$clear = "hello world, what a great day";
$key = "wachtwoord";

print "Clear text\n" . $clear . "\n";
print "\n";

$encoded =  (xor_this($clear, $key));
$b64_encoded = base64_encode ($encoded);
print "Encoded text\n";
var_dump ($b64_encoded);
print "\n";

$b64_decoded = base64_decode ($b64_encoded);
$decoded = xor_this($b64_decoded, $key);
print "Decoded text\n";
var_dump ($decoded);
print "\n";

?>
