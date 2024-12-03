<?php
    require_once 'config.php';
    function encryptdata($data, $key){
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    function decryptdata($encryptdata, $key){
        $decoded = base64_decode($encryptdata);
        $iv = substr($decoded, 0, 16);
        $cipherText = substr($decoded, 16);
        return openssl_decrypt($cipherText, 'AES-256-CBC', $key, 0, $iv);
    }
?>