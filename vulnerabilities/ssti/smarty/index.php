<?php

/*
 * http://dvwa.test/smarty/?inject={include%20file=%27string:{system(%22id%22)}%27}&page=%3Cb%3Ehello%3C/b%3E&external_template=/etc/passwd
 *
 * https://portswigger.net/research/server-side-template-injection
 *
 */

session_start();

$_SESSION['id'] = "my id";

require '../vendor/autoload.php';
use Smarty\Smarty;
$smarty = new Smarty();

$smarty->setTemplateDir ('../smarty_stuff/templates');
$smarty->setCompileDir ('../smarty_stuff/templates_c');
$smarty->setCacheDir ('../smarty_stuff/cache');
$smarty->setConfigDir ('../smarty_stuff/configs');
$smarty->debugging = true;
// https://www.smarty.net/docsv2/en/variable.php.handling.tpl
#$smarty->php_handling = SMARTY_PHP_ALLOW;
$smarty->disableSecurity();


$page = "Page not passed";
if (array_key_exists ("page", $_GET)) {
	$page = $_GET['page'];
}

$inject = "Inject into me";
if (array_key_exists ("inject", $_GET)) {
	$inject = $_GET['inject'];
}

$smarty->assign('name', 'Ned');
$smarty->assign('inject', $inject);
$smarty->assign('page', $page);

// Exploit this by uploading a template using the file uploader then pass it here
// http://dvwa.test/vulnerabilities/ssti/smarty/?template=/home/robin/dvwa/index.tpl

$template = "index.tpl";
if (array_key_exists ("template", $_GET)) {
	$template = $_GET['template'];
}

$smarty->display($template);

?>
