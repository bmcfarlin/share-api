<?php
class Tx{
  public $tx_id;
  public $wallet_id;
  public $tx_type_cd;
  public $amount;
  public $creation_user_id;
  public $creation_dtm;
  public $last_update_user_id;
  public $last_update_dtm;
  
  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->tx_id = 0;
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
        case 'tx_id':
        case 'wallet_id':
        case 'tx_type_cd':
        case 'amount':
        case 'creation_dtm':
        case 'last_update_dtm':
          break;
        default:
          unset($this->$key);
          break;
      }
    }

    $tx_type_cd = $this->tx_type_cd;
    $tx_type = TxType::GetByCd($tx_type_cd);
    $tx_type_name = $tx_type->tx_type_name;
    $this->tx_type_name = $tx_type_name;
    unset($this->tx_type_cd);
  }

  static function GetAll(){
    $values = array();
    $sql = "call sp_tx_get";
    $results = \MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new Tx($result);
    return $values;
  }
  
  static function GetById($tx_id){
    $value = null;
    $sql = "call sp_tx_get_by_id(?)";
    $params = array('i', $tx_id);
    $results = \MySQL::Execute($sql, $params);
    if($results) $value = new Tx($results[0]);
    return $value;
  }
  
  static function GetByWallet($wallet_id){
    $values = array();
    $sql = "call sp_tx_get_by_wallet(?)";
    $params = array('i', $wallet_id);
    $results = \MySQL::Execute($sql, $params);
    if($results) foreach($results as $result) $values[] = new Tx($result);
    return $values;
  }

  function Create(){
    $value = null;
    $sql = "call sp_tx_ins(?, ?, ?, ?)";
    $params = array('isis', $this->wallet_id, $this->tx_type_cd, $this->amount, $this->creation_user_id);
    $results = \MySQL::Execute($sql, $params);
    if($results){
      $this->tx_id = $results[0]->tx_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }
  
  function Update(){
    $value = null;
    $sql = "call sp_tx_upd(?, ?, ?, ?, ?)";
    $params = array('iisis', $this->tx_id, $this->wallet_id, $this->tx_type_cd, $this->amount, $this->last_update_user_id);
    $results = \MySQL::Execute($sql, $params);
    if($results){
      $this->last_update_dtm = $results[0]->last_update_dtm;
    }
  }
  
  function Save(){
    if(empty($this->tx_id)){
      $this->Create();
    }else{
      $this->Update();
    }
  }
  
  function Delete(){
    $sql = "call sp_tx_del(?)";
    $params = array('i', $this->tx_id);
    \MySQL::Execute($sql, $params);
  }
  
  static function Truncate(){
    $sql = "call sp_tx_trn()";
    \MySQL::Execute($sql);
  }
  
}
?>