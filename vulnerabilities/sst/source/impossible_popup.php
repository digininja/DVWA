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

$wargames['name'] = "WarGames";
$wargames['release'] = "June 3, 1983";
$wargames['image'] = "../images/wargames.jpg";
$wargames['image_alt'] = "WarGames movie poster";
$wargames['running_time'] = "1 hours, 54 minutes";
$wargames['director'] = "John Badham";
$wargames['stars'] = "Matthew Broderick, Ally Sheedy, and John Wood";
$smarty->assign('wargames', $wargames);

// Specify which template to use, default to the index page
$template = "impossible.tpl";

$smarty->display($template);

?>
