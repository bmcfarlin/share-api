<?php
class RedisManager
{

  private static $_manager;
  private $redis;

  function __construct(){

    if(is_string(REDIS_SERVER)){
      list($host, $port) = explode(':', REDIS_SERVER);
      $this->redis = new Redis(); 
      $this->redis->connect($host, $port);
      $this->redis->auth(REDIS_PASSWORD);
    }else{
      if(is_array(REDIS_SERVER)){
        trace(REDIS_SERVER);
        $this->redis = new RedisCluster(NULL, REDIS_SERVER, 3, 3, true, REDIS_PASSWORD);
        trace("really");
      }else{
        throw new \RuntimeException("Invalid Redis Server configuration");
      }
    }
  }

  function __toString(){
    return get_class($this);
  }

  static function getInstance(){
    if(empty(self::$_manager)){
      self::$_manager = new RedisManager();
    }
    return self::$_manager;
  }

  public function log($dtm, $ipa, $msg){
    if(!is_scalar($msg)){
      $msg = serialize($msg);
    }
    $stream_name = 'trace';
    $values = ['dtm' => $dtm, 'ipa' => $ipa, 'msg' => $msg];
    $this->redis->xAdd($stream_name, "*", $values);
  }

  public function list(){
    $stream_name = 'trace';
    return $this->redis->xRange($stream_name, "-", "+", 1000);
  }

  public function set($key, $value){
    if(!is_scalar($value)){
      $value = serialize($value);
    }
    return $this->redis->set($key, $value);
  }

  public function get($key){
    $value = $this->redis->get($key);
    try{
      $item = unserialize($value);
      if($item !== false){
        $value = $item;
      }
    }catch(Exception $exc){
      trace($exc->getMessage());
    }
    return $value;
  }

  public function persist($key, $value = null){
    if($value){
      $this->set($key, $value);
    }
    return $this->redis->persist($key);
  }

  public function del($key){
    return $this->redis->del($key);
  }

  public function flushDb(){
    return $this->redis->flushDb();
  }

  public function keys($key = "*"){
    return $this->redis->keys($key);
  }

  public function exists($key){
    return $this->redis->exists($key);
  }

}


