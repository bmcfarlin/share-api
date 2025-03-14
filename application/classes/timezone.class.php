<?php
class TimeZone{
  public $time_zone_id;
  public $name;
  public $identifier;
  public $offset;
  public $creation_dtm;
  public $last_update_dtm;

  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->time_zone_id = 0;
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
    $items = TimeZone::GetAll();
    foreach($items as $item) $values[$item->time_zone_id] = $item->name;
    return $values;
  }
  static function GetAll(){
    $values = array();
    $sql = "call sp_time_zone_get";
    $results = MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new TimeZone($result);
    return $values;
  }

  static function GetById($time_zone_id){
    $value = null;
    $sql = "call sp_time_zone_get_by_id(?)";
    $params = array('i', $time_zone_id);
    $results = MySQL::Execute($sql, $params);
    if($results) $value = new TimeZone($results[0]);
    return $value;
  }

  function Create(){
    $value = null;
    $sql = "call sp_time_zone_ins(?, ?, ?)";
    $params = array('ssi', $this->name, $this->identifier, $this->offset);
    $results = MySQL::Execute($sql, $params);
    if($results){
      $this->time_zone_id = $results[0]->time_zone_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }

  function Save(){
    if($this->time_zone_id === 0){
      $this->Create();
    }else{
      $value = null;
      $sql = "call sp_time_zone_upd(?, ?, ?, ?)";
      $params = array('issi', $this->time_zone_id, $this->name, $this->identifier, $this->offset);
      $results = MySQL::Execute($sql, $params);
      if($results){
        $this->last_update_dtm = $results[0]->last_update_dtm;
      }
    }
  }

  function Delete(){
    $sql = "call sp_time_zone_del(?)";
    $params = array('i', $this->time_zone_id);
    MySQL::Execute($sql, $params);
  }

  static function GetByName($name){
    $value = null;
    $sql = "call sp_time_zone_get_by_name(?)";
    $params = array('s', $name);
    $results = MySQL::Execute($sql, $params);
    if($results) $value = new TimeZone($results[0]);
    return $value;
  }


}
?>