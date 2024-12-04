<?php

if (! function_exists('generateToken')) {

    function generateToken($data)
    {
        $cipher = 'AES-256-CBC';
        $iv = random_bytes(16);
        $encryptedData = openssl_encrypt($data, $cipher, 't0mzNqXzviaaMdWe3EUPHMK7Gq4d2k', 0, $iv);
        return base64_encode($encryptedData . '::' . base64_encode($iv));
    }
}
if (! function_exists('decodeToken')) {

    function decodeToken($token)
    {
        try {
            $cipher = 'AES-256-CBC';
            list($encryptedData, $iv) = explode('::', base64_decode($token), 2);
            $iv = base64_decode($iv);
            $decode = openssl_decrypt($encryptedData, $cipher, 't0mzNqXzviaaMdWe3EUPHMK7Gq4d2k', 0, $iv);
            return json_decode($decode, 1);
        } catch (Exception $e) {
            return [];
        }
    }
}
