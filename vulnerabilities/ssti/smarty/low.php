<?php

/*
 * http://dvwa.test/smarty/?inject={include%20file=%27string:{system(%22id%22)}%27}&page=%3Cb%3Ehello%3C/b%3E&external_template=/etc/passwd
 *
 * https://portswigger.net/research/server-side-template-injection
 *
 */
define( 'DVWA_WEB_PAGE_TO_ROOT', '../../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

require '../vendor/autoload.php';
use Smarty\Smarty;
$smarty = new Smarty();

$smarty->setTemplateDir ('../smarty_stuff/templates');
$smarty->setCompileDir ('../smarty_stuff/templates_c');
$smarty->setCacheDir ('../smarty_stuff/cache');
$smarty->setConfigDir ('../smarty_stuff/configs');
#$smarty->debugging = true;
$smarty->disableSecurity();

$smarty->assign('name', 'Skittles');

// Specify which template to use, default to the index page
$template = "index.tpl";
if (array_key_exists ("template", $_GET)) {
	$template = $_GET['template'];
}

$smarty->display($template);

?>
