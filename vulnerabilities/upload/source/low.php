<?php

if( isset( $_POST[ 'Upload' ] ) ) {
	$target_path = DVWA_WEB_PAGE_TO_ROOT . "hackable/uploads/";
	$target_path = $target_path . basename( $_FILES[ 'uploaded' ][ 'name' ] );

	$html .= '<pre>';
	if( !move_uploaded_file( $_FILES[ 'uploaded' ][ 'tmp_name' ], $target_path ) ) {
		$html .= 'Your image was not uploaded.';
	}
	else {
		$html .= $target_path . ' succesfully uploaded!';
	}
	$html .= '</pre>';
}

?>
