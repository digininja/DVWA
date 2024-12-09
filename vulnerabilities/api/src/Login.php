<?php

namespace Src;

use OpenApi\Attributes as OAT;

class Login
{
	private const CIPHER = "aes-128-gcm";
	private const KEY = "Paintbrush";

	private static function encrypt($cleartext) {
		$ivlen = openssl_cipher_iv_length(Login::CIPHER);
		$iv = openssl_random_pseudo_bytes($ivlen);
		$ciphertext = openssl_encrypt($cleartext, Login::CIPHER, Login::KEY, $options=0, $iv, $tag);
		$ret = base64_encode ($ciphertext . ":" . $iv . ":" . $tag);
		return $ret;
	}
	private static function decrypt($ciphertext) {
		$str = base64_decode ($ciphertext);
		$bits = explode (":", $str);
		if (count ($bits) != 3) {
			return false;
		}
		$value = $bits[0];
		$iv = $bits[1];
		$tag = $bits[2];
		$cleartext = openssl_decrypt($value, Login::CIPHER, Login::KEY, $options=0, $iv, $tag);
		return $cleartext;
	}
	public static function create_token() {
		$token = array (
						"secret" => "12345",
						"expires" => time() + 60,
					);
		return Login::encrypt(json_encode ($token));
	}
	public static function check_token($token) {
		$decrypted = Login::decrypt($token);

		if ($decrypted === false) {
			return false;
		}

		$token = json_decode ($decrypted, true);
		if ($token['secret'] == "12345" && $token['expires'] > time()) {
			return true;
		}
		return false;
	}

}
