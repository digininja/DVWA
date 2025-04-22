<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

require '../vendor/autoload.php';
use Smarty\Smarty;
$smarty = new Smarty();

$smarty->setTemplateDir ('../smarty/templates');
$smarty->setCompileDir ('../smarty/templates_c');
$smarty->setCacheDir ('../smarty/cache');
$smarty->setConfigDir ('../smarty/configs');

$me = $_SERVER['REQUEST_URI'];
$directory_prefix = str_replace ("vulnerabilities/sst/source/high_popup.php", "", $me);

$smarty->assign('protocol', "http");
$smarty->assign('host', $_SERVER['HTTP_HOST']);
$smarty->assign('page', $directory_prefix . 'vulnerabilities/sst/protected/index.php');

// Specify which template to use, default to the index page
$template = "high.tpl";

$smarty->display($template);

?>
