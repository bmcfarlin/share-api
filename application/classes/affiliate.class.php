<?php
class Affiliate{
  public $affiliate_id;
  public $partner_id;
  public $user_id;
  public $code;
  public $creation_user_id;
  public $creation_dtm;
  public $last_update_user_id;
  public $last_update_dtm;
  
  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->affiliate_id = 0;
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

  function clean(){
    foreach($this as $key => $value){
      switch($key){
        case 'affiliate_id':
        case 'partner_id':
        case 'code':
        case 'creation_dtm':
        case 'last_update_dtm':
          break;
        default:
          unset($this->$key);
          break;
      }
    }
  }
  
  static function GetAll(){
    $values = array();
    $sql = "call sp_affiliate_get";
    $results = \MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new Affiliate($result);
    return $values;
  }
  
  static function GetById($affiliate_id){
    $value = null;
    $sql = "call sp_affiliate_get_by_id(?)";
    $params = array('i', $affiliate_id);
    $results = \MySQL::Execute($sql, $params);
    if($results) $value = new Affiliate($results[0]);
    return $value;
  }
  
  static function GetByPartnerUser($partner_id, $user_id){
    $value = null;
    $sql = "call sp_affiliate_get_by_partner_user(?, ?)";
    $params = array('is', $partner_id, $user_id);
    $results = \MySQL::Execute($sql, $params);
    if($results) $value = new Affiliate($results[0]);
    return $value;
  }

  static function GetByCode($code){
    $value = null;
    $sql = "call sp_affiliate_get_by_code(?)";
    $params = array('s', $code);
    $results = \MySQL::Execute($sql, $params);
    if($results) $value = new Affiliate($results[0]);
    return $value;
  }

  function Create(){
    $value = null;
    $sql = "call sp_affiliate_ins(?, ?, ?, ?)";
    $params = array('isss', $this->partner_id, $this->user_id, $this->code, $this->creation_user_id);
    $results = \MySQL::Execute($sql, $params);
    if($results){
      $this->affiliate_id = $results[0]->affiliate_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }
  
  function Update(){
    $value = null;
    $sql = "call sp_affiliate_upd(?, ?, ?, ?, ?)";
    $params = array('iisss', $this->affiliate_id, $this->partner_id, $this->user_id, $this->code, $this->last_update_user_id);
    $results = \MySQL::Execute($sql, $params);
    if($results){
      $this->last_update_dtm = $results[0]->last_update_dtm;
    }
  }
  
  function Save(){
    if(empty($this->affiliate_id)){
      $this->Create();
    }else{
      $this->Update();
    }
  }
  
  function Delete(){
    $sql = "call sp_affiliate_del(?)";
    $params = array('i', $this->affiliate_id);
    \MySQL::Execute($sql, $params);
  }
  
  static function Truncate(){
    $sql = "call sp_affiliate_trn()";
    \MySQL::Execute($sql);
  }
  
}
?>