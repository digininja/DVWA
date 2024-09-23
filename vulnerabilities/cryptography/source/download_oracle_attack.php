<?php

// open the file in a binary mode
$name = './oracle_attack.php';
$fp = fopen($name, 'rb');

// send the right headers
header("Content-Type: application/x-httpd-php");
header("Content-Length: " . filesize($name));
header("Content-Disposition: attachment; filename= oracle_attack.php");

// dump the picture and stop the script
fpassthru($fp);
exit;

?>
