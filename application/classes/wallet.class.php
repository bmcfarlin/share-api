<?php
class Wallet{
  public $wallet_id;
  public $affiliate_id;
  public $name;
  public $creation_user_id;
  public $creation_dtm;
  public $last_update_user_id;
  public $last_update_dtm;
  
  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->wallet_id = 0;
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
        case 'wallet_id':
        case 'affiliate_id':
        case 'name':
        case 'creation_dtm':
        case 'last_update_dtm':
          break;
        default:
          unset($this->$key);
          break;
      }
    }
  }

  static function GetValues(){
    $values = array();
    $items = Wallet::GetAll();
    foreach($items as $item) $values[$item->wallet_id] = $item->name;
    return $values;
  }
  
  static function GetAll(){
    $values = array();
    $sql = "call sp_wallet_get";
    $results = \MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new Wallet($result);
    return $values;
  }
  
  static function GetById($wallet_id){
    $value = null;
    $sql = "call sp_wallet_get_by_id(?)";
    $params = array('i', $wallet_id);
    $results = \MySQL::Execute($sql, $params);
    if($results) $value = new Wallet($results[0]);
    return $value;
  }
  
  static function GetByAffiliate($affiliate_id){
    $value = null;
    $sql = "call sp_wallet_get_by_affiliate(?)";
    $params = array('i', $affiliate_id);
    $results = \MySQL::Execute($sql, $params);
    if($results) $value = new Wallet($results[0]);
    return $value;
  }

  function Create(){
    $value = null;
    $sql = "call sp_wallet_ins(?, ?, ?)";
    $params = array('iss', $this->affiliate_id, $this->name, $this->creation_user_id);
    $results = \MySQL::Execute($sql, $params);
    if($results){
      $this->wallet_id = $results[0]->wallet_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }
  
  function Update(){
    $value = null;
    $sql = "call sp_wallet_upd(?, ?, ?, ?)";
    $params = array('iiss', $this->wallet_id, $this->affiliate_id, $this->name, $this->last_update_user_id);
    $results = \MySQL::Execute($sql, $params);
    if($results){
      $this->last_update_dtm = $results[0]->last_update_dtm;
    }
  }
  
  function Save(){
    if(empty($this->wallet_id)){
      $this->Create();
    }else{
      $this->Update();
    }
  }
  
  function Delete(){
    $sql = "call sp_wallet_del(?)";
    $params = array('i', $this->wallet_id);
    \MySQL::Execute($sql, $params);
  }
  
  static function Truncate(){
    $sql = "call sp_wallet_trn()";
    \MySQL::Execute($sql);
  }
  
}
?>