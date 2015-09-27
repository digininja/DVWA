<?php

if( isset( $_POST[ 'Upload' ] ) ) {
	// Check Anti-CSRF token
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );

	$uploaded_name = $_FILES[ 'uploaded' ][ 'name' ];
	$uploaded_ext  = substr( $uploaded_name, strrpos( $uploaded_name, ' . ' ) + 1);
	$uploaded_size = $_FILES[ 'uploaded' ][ 'size' ];
	$uploaded_type = $_FILES[ 'uploaded' ][ 'type' ];
	$uploaded_tmp  = $_FILES[ 'uploaded' ][ 'tmp_name' ];
	$target_path   = DVWA_WEB_PAGE_TO_ROOT . 'hackable/uploads/';
	//$target_file   = basename( $uploaded_name, ' . ' . $uploaded_ext ) . '-';
	$target_file   =  md5( uniqid() . $uploaded_name ) . ' . ' . $uploaded_ext;
	$temp_file     = ( ( ini_get( 'upload_tmp_dir' ) == '' ) ? ( sys_get_temp_dir() ) : ( ini_get( 'upload_tmp_dir' ) ) );
	$temp_file    .= DIRECTORY_SEPARATOR . md5( uniqid() . $uploaded_name ) . ' . ' . $uploaded_ext;

	$html .= '<pre>';
	if( ( strtolower( $uploaded_ext ) == 'jpg' || strtolower( $uploaded_ext ) == 'jpeg' || strtolower( $uploaded_ext ) == 'png' ) &&
		( $uploaded_size < 100000 ) &&
		( $uploaded_type == 'image/jpeg' || $uploaded_type == 'image/png' ) &&
		getimagesize( $uploaded_tmp ) ) {

		// Strip any metadata (using Imagick is recommended over GD)
		if( $uploaded_type == 'image/jpeg' ) {
			$img = imagecreatefromjpeg( $uploaded_tmp );
			imagejpeg( $img, $temp_file, 100);
		}
		else {
			$img = imagecreatefrompng( $uploaded_tmp );
			imagepng( $img, $temp_file, 9);

		}
		imagedestroy( $img );

		// Move the file to the web root from the temp folder
		if( rename( $temp_file, ( getcwd() . DIRECTORY_SEPARATOR . $target_path . $target_file ) ) ) {
			$html .= "<a href='${target_path}${target_file}'>${target_file}</a> succesfully uploaded!";
		}
		else {
			$html .= 'Your image was not uploaded.';
		}

		// Delete any temp files
		if( file_exists( $temp_file ) )
			unlink( $temp_file );
	}
	else {
		$html .= 'Your image was not uploaded. We only accept JPEG or PNG images.';
	}
	$html .= '</pre>';
}

// Generate Anti-CSRF token
generateSessionToken();

?>
