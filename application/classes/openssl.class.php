<?php

  class OpenSSL{

    static function Encrypt($text, $key = null){
      if(empty($key)){
        $key = OPENSSL_KEY;
      }
      $data = $text;
      $method = 'AES-256-CBC';
      $ivSize = openssl_cipher_iv_length($method);
      $iv = openssl_random_pseudo_bytes($ivSize);
      $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
      $encrypted = base64_encode($iv . $encrypted);
      return $encrypted;
    }

    static function Decrypt($text, $key = null){
      if(empty($key)){
        $key = OPENSSL_KEY;
      }
      $data = $text;
      $method = 'AES-256-CBC';
      $data = base64_decode($data);
      $ivSize = openssl_cipher_iv_length($method);
      $iv = substr($data, 0, $ivSize);
      $data = openssl_decrypt(substr($data, $ivSize), $method, $key, OPENSSL_RAW_DATA, $iv);
      return $data;
    }
  }
?>
