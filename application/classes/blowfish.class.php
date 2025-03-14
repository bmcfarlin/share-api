<?php

  class Blowfish{

    static function Encrypt($text, $key = null){
      if(empty($key)){
        $key = BLOWFISH_KEY;
      }
      $td = mcrypt_module_open(MCRYPT_BLOWFISH, '', 'ecb', '');
      $iv_size = mcrypt_enc_get_iv_size ($td);
      $key_size = mcrypt_enc_get_key_size ($td);
      srand((double) microtime() * 1000000);
      $iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND);
      $enc = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $text, MCRYPT_MODE_ECB, $iv);
      $ret = base64_encode($enc);
      return $ret;
    }

    static function Decrypt($text, $key = null){
      if(empty($key)){
        $key = BLOWFISH_KEY;
      }
      $td = mcrypt_module_open(MCRYPT_BLOWFISH, '', 'ecb', '');
      $iv_size = mcrypt_enc_get_iv_size ($td);
      $key_size = mcrypt_enc_get_key_size ($td);
      srand((double) microtime() * 1000000);
      $iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND);
      $dec = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, base64_decode($text), MCRYPT_MODE_ECB, $iv);
      $ret = trim($dec);
      return $ret;
    }
  }
?>
