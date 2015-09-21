<?php

if( isset( $_POST[ 'Upload' ] ) ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	$uploaded_name = $_FILES[ 'uploaded' ][ 'name' ];
	$uploaded_ext  = substr( $uploaded_name, strrpos( $uploaded_name, '.' ) + 1);
	$uploaded_size = $_FILES[ 'uploaded' ][ 'size' ];
	$uploaded_type = $_FILES[ 'uploaded' ][ 'type' ];
	$uploaded_tmp  = $_FILES[ 'uploaded' ][ 'tmp_name' ];
	$target_path   = DVWA_WEB_PAGE_TO_ROOT."hackable/uploads/";
	//$target_path   = $target_path . basename( $uploaded_name, '.' . $uploaded_ext ) . '-';
	$target_path   = $target_path . md5( uniqid() . $uploaded_name ) . "." . $uploaded_ext;

	$html .= '<pre>';
	if( ( strtolower( $uploaded_ext ) == "jpg" || strtolower( $uploaded_ext ) == "jpeg" || strtolower( $uploaded_ext ) == "png" ) &&
		( $uploaded_size < 100000 ) &&
		( $uploaded_type == "image/jpeg" || $uploaded_type == "image/png" ) &&
		getimagesize( $uploaded_tmp ) ) {

		if(!move_uploaded_file( $_FILES[ 'uploaded' ][ 'tmp_name' ], $target_path )) {
			$html .= 'Your image was not uploaded.';
		}
		else {
			$html .= $target_path . ' succesfully uploaded!';
		}
	}
	else {
		$html .= 'Your image was not uploaded. We only accept JPEG or PNG images.';
	}
	$html .= '</pre>';
}

// Generate Anti-CSRF token
generateSessionToken();

?>
