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

$sneakers['name'] = "Sneakers";
$sneakers['release'] = "September 11, 1992";
$sneakers['image'] = "../images/sneakers.jpg";
$sneakers['image_alt'] = "Sneakers movie poster";
$sneakers['running_time'] = "2 hours, 6 minutes";
$sneakers['director'] = "Phil Alden Robinson";
$sneakers['stars'] = "Robert Redford, Dan Aykroyd, ben Kingsley, Mary McDonnell, River Phoenix, Sidney Poitier, and David Strathairn";
$smarty->assign('sneakers', $sneakers);

$hackers['name'] = "Hackers";
$hackers['release'] = "September 15, 1995";
$hackers['image'] = "../images/hackers.jpg";
$hackers['image_alt'] = "Hackers movie poster";
$hackers['running_time'] = "1 hour, 45 minutes";
$hackers['director'] = "Ian Softley";
$hackers['stars'] = "Jonny Lee Miller, Angelian Jolie, Fisher Stevens and Lorraine Bracco";
$smarty->assign('hackers', $hackers);

// Specify which template to use, default to the index page
$template = "index.tpl";
if (array_key_exists ("template", $_GET)) {
	$template = $_GET['template'];
}

$smarty->display($template);

?>
