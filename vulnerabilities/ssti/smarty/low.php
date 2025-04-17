<?php

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

$smarty->assign('name', 'Skittles');

// Specify which template to use, default to the index page
$template = "index.tpl";
if (array_key_exists ("template", $_GET)) {
	$template = $_GET['template'];
}

$smarty->display($template);

?>
