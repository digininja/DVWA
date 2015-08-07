<?php

/**
 * Implements data: URI for base64 encoded images supported by GD.
 */
class HTMLPurifier_URIScheme_data extends HTMLPurifier_URIScheme {

    public $browsable = true;
    public $allowed_types = array(
        // you better write validation code for other types if you
        // decide to allow them
        'image/jpeg' => true,
        'image/gif' => true,
        'image/png' => true,
        );
    // this is actually irrelevant since we only write out the path
    // component
    public $may_omit_host = true;

    public function doValidate(&$uri, $config, $context) {
        $result = explode(',', $uri->path, 2);
        $is_base64 = false;
        $charset = null;
        $content_type = null;
        if (count($result) == 2) {
            list($metadata, $data) = $result;
            // do some legwork on the metadata
            $metas = explode(';', $metadata);
            while(!empty($metas)) {
                $cur = array_shift($metas);
                if ($cur == 'base64') {
                    $is_base64 = true;
                    break;
                }
                if (substr($cur, 0, 8) == 'charset=') {
                    // doesn't match if there are arbitrary spaces, but
                    // whatever dude
                    if ($charset !== null) continue; // garbage
                    $charset = substr($cur, 8); // not used
                } else {
                    if ($content_type !== null) continue; // garbage
                    $content_type = $cur;
                }
            }
        } else {
            $data = $result[0];
        }
        if ($content_type !== null && empty($this->allowed_types[$content_type])) {
            return false;
        }
        if ($charset !== null) {
            // error; we don't allow plaintext stuff
            $charset = null;
        }
        $data = rawurldecode($data);
        if ($is_base64) {
            $raw_data = base64_decode($data);
        } else {
            $raw_data = $data;
        }
        // XXX probably want to refactor this into a general mechanism
        // for filtering arbitrary content types
        $file = tempnam("/tmp", "");
        file_put_contents($file, $raw_data);
        if (function_exists('exif_imagetype')) {
            $image_code = exif_imagetype($file);
        } elseif (function_exists('getimagesize')) {
            set_error_handler(array($this, 'muteErrorHandler'));
            $info = getimagesize($file);
            restore_error_handler();
            if ($info == false) return false;
            $image_code = $info[2];
        } else {
            trigger_error("could not find exif_imagetype or getimagesize functions", E_USER_ERROR);
        }
        $real_content_type = image_type_to_mime_type($image_code);
        if ($real_content_type != $content_type) {
            // we're nice guys; if the content type is something else we
            // support, change it over
            if (empty($this->allowed_types[$real_content_type])) return false;
            $content_type = $real_content_type;
        }
        // ok, it's kosher, rewrite what we need
        $uri->userinfo = null;
        $uri->host = null;
        $uri->port = null;
        $uri->fragment = null;
        $uri->query = null;
        $uri->path = "$content_type;base64," . base64_encode($raw_data);
        return true;
    }

    public function muteErrorHandler($errno, $errstr) {}

}

