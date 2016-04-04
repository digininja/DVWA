<?php
session_start();
$_SESSION = array();

include("simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Example &raquo; A simple PHP CAPTCHA script</title>
    <style type="text/css">
        pre {
            border: solid 1px #bbb;
            padding: 10px;
            margin: 2em;
        }

        img {
            border: solid 1px #ccc;
            margin: 0 2em;
        }
    </style>
</head>
<body>
    <h1>
        CAPTCHA Example
    </h1>

    <h2>Usage</h2>

    <p>
        The following code will prepare a CAPTCHA image and keep the code in a session
        variable for later use:
    </p>

<pre>
&lt;?php
session_start();
include("simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();
?&gt;
</pre>

    <p>
        After the call to <code>simple_php_captcha()</code> above,
        <code>$_SESSION['captcha']</code> will be something like this:
    </p>

<pre>
<?php
print_r($_SESSION['captcha']);
?>
</pre>

    <p>
        To display the CAPTCHA image, create an HTML <code>&lt;img&gt;</code> using
        <code>$_SESSION['captcha']['image_src']</code> as the <code>src</code> attribute:
    </p>

    <p>
        <?php
        echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">';

        ?>
    </p>

    <p>
        To verify the CAPTCHA value on the next page load (or in an AJAX request), test
        against  <code>$_SESSION['captcha']['code']</code>. You can use
        <code>strtolower()</code> or <code>strtoupper()</code> to perform a
        case-insensitive match.
    </p>

    <h2>Configuration</h2>
    <p>
        Configuration is easy and all values are optional. To specify one or more options,
        do this:
    </p>

<pre>
&lt;?php

$_SESSION['captcha'] = simple_php_captcha( array(
    'min_length' => 5,
    'max_length' => 5,
    'backgrounds' => array(image.png', ...),
    'fonts' => array('font.ttf', ...),
    'characters' => 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789',
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

&gt;
</pre>

    <h2>Notes</h2>
    <ul>
        <li>
            <strong>Important!</strong> Make sure you call <code>session_start()</code> before
            calling the <code>simple_php_captcha()</code> function
        </li>
        <li>
            Requires PHP GD2 library
        </li>
        <li>
            Backgound images must be in PNG format
        </li>
        <li>
            Fonts must be either TTF or OTF
        </li>
        <li>
            Backgrounds and fonts must be specified using their full paths (tip: use
            <code>$_SERVER['DOCUMENT_ROOT'] . '/' . [path-to-file]</code>)
        </li>
        <li>
            Angles should not exceed approximately 15 degrees, as the text will sometimes
            appear outside of the viewable area
        </li>
        <li>
            Creates a function called <code>simple_php_captcha()</code> in the global namespace
        </li>
        <li>
            Uses the <code>$_SESSION['simple-php-captcha']</code> session variable
        </li>
    </ul>

</body>
</html>