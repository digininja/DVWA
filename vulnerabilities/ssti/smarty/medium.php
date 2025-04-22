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
# https://www.smarty.net/docsv2/en/chapter.debugging.console.tpl
$smarty->debugging_ctrl = 'URL';

$template_string = '<p>Hello {$first_name} {$last_name}';
$user_id = 2;

if (array_key_exists ("user_id", $_GET) && is_numeric ($_GET['user_id'])) {
	$user_id = intval ($_GET['user_id']);
}

if (array_key_exists ("template", $_GET)) {
	$template_string = base64_decode (str_replace (" ", "+", $_GET['template']));
}

// Load a user
$user = load_user($user_id);
$smarty->assign ("secret_key", "8331173");
$smarty->assign ("hidden_debug_info", "You can only find the secret key through the debug console.");

if (is_null ($user)) {
	$smarty->display('string:<p>Invalid User</p>');
} else {
	// Throw the user object into Smarty variables so they can be used in the template
	foreach ($user as $key => $value) {
		$smarty->assign($key, $value);
	}

	$smarty->display('string:'.$template_string);
}
?>
