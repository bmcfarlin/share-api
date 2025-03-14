<?php
class UserAuth{
  public $user_auth_id;
  public $user_id;
  public $ip_address;
  public $creation_user_id;
  public $creation_dtm;
  public $last_update_user_id;
  public $last_update_dtm;

  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->user_auth_id = 0;
      break;
      case 1:
        $result = func_get_arg(0);
        foreach($result as $key => $value) $this->$key = $value;
      break;
    }
  }

  function __toString(){
    return get_class($this);
  }

  static function GetAll(){
    $values = array();
    $sql = "call sp_user_auth_get";
    $results = MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new UserAuth($result);
    return $values;
  }

  static function GetById($user_auth_id){
    $value = null;
    $sql = "call sp_user_auth_get_by_id(?)";
    $params = array('i', $user_auth_id);
    $results = MySQL::Execute($sql, $params);
    if($results) $value = new UserAuth($results[0]);
    return $value;
  }

  function Create(){
    $value = null;
    $sql = "call sp_user_auth_ins(?, ?, ?)";
    $params = array('sss', $this->user_id, $this->ip_address, $this->creation_user_id);
    $results = MySQL::Execute($sql, $params);
    if($results){
      $this->user_auth_id = $results[0]->user_auth_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }

  function Update(){
    $value = null;
    $sql = "call sp_user_auth_upd(?, ?, ?, ?)";
    $params = array('isss', $this->user_auth_id, $this->user_id, $this->ip_address, $this->last_update_user_id);
    $results = MySQL::Execute($sql, $params);
    if($results){
      $this->last_update_dtm = $results[0]->last_update_dtm;
    }
  }

  function Save(){
    if(empty($this->user_auth_id)){
      $this->Create();
    }else{
      $this->Update();
    }
  }

  function Delete(){
    $sql = "call sp_user_auth_del(?)";
    $params = array('i', $this->user_auth_id);
    MySQL::Execute($sql, $params);
  }

  static function Truncate(){
    $sql = "call sp_user_auth_trn()";
    MySQL::Execute($sql);
  }

}
?>