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
$smarty->disableSecurity();


$page = "Page not passed";
if (array_key_exists ("page", $_GET)) {
	$page = $_GET['page'];
}

$inject = "Inject into me";
if (array_key_exists ("inject", $_GET)) {
	$inject = $_GET['inject'];
}

$external_template = null;
if (array_key_exists ("external_template", $_GET)) {
	$external_template = $_GET['external_template'];
}

$smarty->assign('name', 'Ned');
$smarty->assign('inject', $inject);
$smarty->assign('page', $page);
$smarty->display('index.tpl');

if (!is_null ($external_template)) {
	$out = $smarty->fetch($external_template);
	var_dump ($out);
}

?>
