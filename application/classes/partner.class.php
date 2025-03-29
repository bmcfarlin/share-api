<?php
class Partner{
  public $partner_id;
  public $name;
  public $description;
  public $url;
  public $email;
  public $creation_user_id;
  public $creation_dtm;
  public $last_update_user_id;
  public $last_update_dtm;
  
  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->partner_id = 0;
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
  
  static function GetValues(){
    $values = array();
    $items = Partner::GetAll();
    foreach($items as $item) $values[$item->partner_id] = $item->name;
    return $values;
  }
  
  static function GetAll(){
    $values = array();
    $sql = "call sp_partner_get";
    $results = \MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new Partner($result);
    return $values;
  }
  
  static function GetById($partner_id){
    $value = null;
    $sql = "call sp_partner_get_by_id(?)";
    $params = array('i', $partner_id);
    $results = \MySQL::Execute($sql, $params);
    if($results) $value = new Partner($results[0]);
    return $value;
  }
  
  function Create(){
    $value = null;
    $sql = "call sp_partner_ins(?, ?, ?, ?, ?)";
    $params = array('sssss', $this->name, $this->description, $this->url, $this->email, $this->creation_user_id);
    $results = \MySQL::Execute($sql, $params);
    if($results){
      $this->partner_id = $results[0]->partner_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }
  
  function Update(){
    $value = null;
    $sql = "call sp_partner_upd(?, ?, ?, ?, ?, ?)";
    $params = array('isssss', $this->partner_id, $this->name, $this->description, $this->url, $this->email, $this->last_update_user_id);
    $results = \MySQL::Execute($sql, $params);
    if($results){
      $this->last_update_dtm = $results[0]->last_update_dtm;
    }
  }
  
  function Save(){
    if(empty($this->partner_id)){
      $this->Create();
    }else{
      $this->Update();
    }
  }
  
  function Delete(){
    $sql = "call sp_partner_del(?)";
    $params = array('i', $this->partner_id);
    \MySQL::Execute($sql, $params);
  }
  
  static function Truncate(){
    $sql = "call sp_partner_trn()";
    \MySQL::Execute($sql);
  }
  
}
?>