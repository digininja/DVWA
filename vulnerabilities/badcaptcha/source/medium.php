<?php

if( isset( $_POST[ 'Submit' ] ) ) {
	// Hide the CAPTCHA form
	$hide_form = true;

	// Get input
	$captcha_code  = $_POST[ 'captcha' ];

	// Check CAPTCHA the the one in the session
	$resp = strtoupper($captcha_code) == $_SESSION['captcha']['code'];

	// Did the CAPTCHA fail?
	if( !$resp ) {
		// What happens when the CAPTCHA was entered incorrectly
		$html     .= "<pre><br />The CAPTCHA was incorrect. Please try again.</pre>";
		$hide_form = true;
		dvwaLogout();
	}
	else {
		// CAPTCHA was correct.
        $html     .= "<pre>You can continue logged in into the Web site.</pre>";
        $hide_form = true;
        return;
	}
}

// Generate a new CAPTCHA
$_SESSION['captcha'] = simple_php_captcha( array(
    'min_length' => 5,
    'max_length' => 5,
    'backgrounds' => array(DVWA_WEB_PAGE_TO_ROOT . "external/simple-php-captcha/backgrounds/" . "45-degree-fabric.png",
                           DVWA_WEB_PAGE_TO_ROOT . "external/simple-php-captcha/backgrounds/" . "cloth-alike.png",
                           DVWA_WEB_PAGE_TO_ROOT . "external/simple-php-captcha/backgrounds/" . "grey-sandbag.png"),
    'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz',
    'min_font_size' => 28,
    'max_font_size' => 28,
    'color' => '#666',
    'angle_min' => 0,
    'angle_max' => 10,
    'shadow' => true,
    'shadow_color' => '#fff',
    'shadow_offset_x' => -1,
    'shadow_offset_y' => 1
));

?>
