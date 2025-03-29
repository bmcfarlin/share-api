<?php
class Item{
  public $item_id;
  public $name;
  public $creation_user_id;
  public $creation_dtm;
  public $last_update_user_id;
  public $last_update_dtm;
  
  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->item_id = 0;
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
    $items = Item::GetAll();
    foreach($items as $item) $values[$item->item_id] = $item->name;
    return $values;
  }
  
  static function GetAll(){
    $values = array();
    $sql = "call sp_item_get";
    $results = \MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new Item($result);
    return $values;
  }
  
  static function GetById($item_id){
    $value = null;
    $sql = "call sp_item_get_by_id(?)";
    $params = array('i', $item_id);
    $results = \MySQL::Execute($sql, $params);
    if($results) $value = new Item($results[0]);
    return $value;
  }
  
  function Create(){
    $value = null;
    $sql = "call sp_item_ins(?, ?)";
    $params = array('ss', $this->name, $this->creation_user_id);
    $results = \MySQL::Execute($sql, $params);
    if($results){
      $this->item_id = $results[0]->item_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }
  
  function Update(){
    $value = null;
    $sql = "call sp_item_upd(?, ?, ?)";
    $params = array('iss', $this->item_id, $this->name, $this->last_update_user_id);
    $results = \MySQL::Execute($sql, $params);
    if($results){
      $this->last_update_dtm = $results[0]->last_update_dtm;
    }
  }
  
  function Save(){
    if(empty($this->item_id)){
      $this->Create();
    }else{
      $this->Update();
    }
  }
  
  function Delete(){
    $sql = "call sp_item_del(?)";
    $params = array('i', $this->item_id);
    \MySQL::Execute($sql, $params);
  }
  
  static function Truncate(){
    $sql = "call sp_item_trn()";
    \MySQL::Execute($sql);
  }
  
}
?>