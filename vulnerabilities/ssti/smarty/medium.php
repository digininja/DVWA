<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

dvwaDatabaseConnect();

// Passing in the ID just in case I want to extend this in the future
function load_user($id) {
	$query = "SELECT users.* FROM users WHERE user_id = '$id';";
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );

	$row = mysqli_fetch_assoc ($result);

	mysqli_close($GLOBALS["___mysqli_ston"]);

	return $row;
}

$_SESSION['id'] = "my id";

require '../vendor/autoload.php';
use Smarty\Smarty;
$smarty = new Smarty();

$smarty->setTemplateDir ('../smarty_stuff/templates');
$smarty->setCompileDir ('../smarty_stuff/templates_c');
$smarty->setCacheDir ('../smarty_stuff/cache');
$smarty->setConfigDir ('../smarty_stuff/configs');
#$smarty->debugging = true;
// https://www.smarty.net/docsv2/en/variable.php.handling.tpl
#$smarty->php_handling = SMARTY_PHP_ALLOW;
$smarty->disableSecurity();

// Load a user
$user = load_user(2);

// Throw the user object into Smarty variables so they can be used in the template
foreach ($user as $key => $value) {
	$smarty->assign($key, $value);
}

$template = "medium.tpl";

$smarty->display($template);

?>
