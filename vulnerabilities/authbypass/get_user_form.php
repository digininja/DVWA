<?php
define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaDatabaseConnect();

$query  = "SELECT user_id, first_name, last_name FROM users";
$result = mysqli_query($GLOBALS["___mysqli_ston"],  $query );

$guestbook = ''; 
$users = array();

while ($row = mysqli_fetch_row($result) ) { 
	if( dvwaSecurityLevelGet() == 'impossible' ) { 
		$user_id = $row[0];
		$first_name = htmlspecialchars( $row[1] );
		$surname = htmlspecialchars( $row[2] );
	} else {
		$user_id = $row[0];
		$first_name = $row[1];
		$surname = $row[2];
	}   

	$user = array (
					"user_id" => $user_id,
					"first_name" => $first_name,
					"surname" => $surname
				);
	$users[] = $user;
}

foreach ($users as $user) {
	?>
	<p><input type="text" id="first_name_<?=$user['user_id']?>" name="first_name" value="<?=$user['first_name']?>" /><input type="text" name="surname" id="surname_<?=$user['user_id']?>" value="<?=$user['surname']?>" /><input type="button" value="Update" onclick="submit_change(<?=$user['user_id']?>)" /></p>
	<?php
}
