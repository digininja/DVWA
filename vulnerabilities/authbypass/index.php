<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Authorisation Bypass' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'authbypass';
$page[ 'help_button' ]   = 'authbypass';
$page[ 'source_button' ] = 'authbypass';
dvwaDatabaseConnect();

$method            = 'GET';
$vulnerabilityFile = '';
switch( dvwaSecurityLevelGet() ) {
	case 'low':
		$vulnerabilityFile = 'low.php';
		break;
	case 'medium':
		$vulnerabilityFile = 'medium.php';
		break;
	case 'high':
		$vulnerabilityFile = 'high.php';
		break;
	default:
		$vulnerabilityFile = 'impossible.php';
		$method = 'POST';
		break;
}

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/authbypass/source/{$vulnerabilityFile}";

$page[ 'body' ] .= '
<div class="body_padded">
	<h1>Vulnerability: Authorisation Bypass</h1>

	<p>This page should only be accessible by the admin user. Your challenge is to gain access to the fetures using one of the other users, for example <i>gordonb</i> / <i>abc123</i>.</p>

	<div class="vulnerable_code_area">
	<div style="font-weight: bold;color: red;font-size: 120%;" id="save_result"></div>
	<div id="user_form"></div>';

$page[ 'body' ] .= "
<script>
	function show_save_result (data) {
		if (data.result == 'ok') {
			document.getElementById('save_result').innerText = 'Save Successful';
		} else {
			document.getElementById('save_result').innerText = 'Save Failed';
		}
	}
		
	function submit_change(id) {
		first_name = document.getElementById('first_name_' + id).value
		surname = document.getElementById('surname_' + id).value

		fetch('change_user_details.php', {
			method: 'POST',
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({ 'id': id, 'first_name': first_name, 'surname': surname })
		}
		)
		.then((response) => response.json())
		.then((data) => show_save_result(data));

		/*
		alert (first_name);
		alert (surname);
		*/
	}

	var xhr= new XMLHttpRequest();
	xhr.open('GET', 'get_user_form.php', true);
	xhr.onreadystatechange= function() {
	if (this.readyState!==4) return;
		if (this.status!==200) return;
			document.getElementById('user_form').innerHTML= this.responseText;
	};
	xhr.send();
</script>
";

$page[ 'body' ] .= '
		<p>Put rest of function here.</p>
		' . 
		$html
		. '
	</div>
</div>';

dvwaHtmlEcho( $page );

?>
