<?php

  class Cache{

    const CACHE_TYPE_APC = 'APC';
    const CACHE_TYPE_REDIS = 'REDIS';

    static function Keys($key = "*"){
      $result = array();
      if(CACHE_TYPE == Cache::CACHE_TYPE_REDIS){
        $redis = RedisManager::getInstance();
        $result = $redis->keys($key);
      }else{
        //$result = apc_exists($key);
      }
      return $result;
    }

    static function Exists($key){
      $result = false;
      if(CACHE_TYPE == Cache::CACHE_TYPE_REDIS){
        $redis = RedisManager::getInstance();
        $result = $redis->exists($key);
      }else{
        $result = apc_exists($key);
      }
      return $result;
    }

    static function Fetch($key){
      $result = false;
      if(CACHE_TYPE == Cache::CACHE_TYPE_REDIS){
        $redis = RedisManager::getInstance();
        $result = $redis->get($key);
      }else{
        $result = apc_fetch($key);
      }
      return $result;
    }

    static function Store($key, $value, $ttl = 0){
      $result = false;
      if(CACHE_TYPE == Cache::CACHE_TYPE_REDIS){
        $redis = RedisManager::getInstance();
        $result = $redis->set($key, $value);
      }else{
        $result = apc_store($key, $value, $ttl);
      }
      return $result;
    }

    static function Delete($key){
      $result = false;
      if(CACHE_TYPE == Cache::CACHE_TYPE_REDIS){
        $redis = RedisManager::getInstance();
        $result = $redis->del($key);
      }else{
        $result = apc_delete($key);
      }
      return $result;
    }

    static function Clear(){
      $result = false;
      if(CACHE_TYPE == Cache::CACHE_TYPE_REDIS){
        $redis = RedisManager::getInstance();
        $result = $redis->flushDb();
      }else{
        $result = apc_clear_cache();
      }
      return $result;
    }

  }
?>
