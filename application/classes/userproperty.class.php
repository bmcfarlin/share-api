<?php
class UserProperty{
  public $user_id;
  public $property_id;
  public $property_name;
  public $property_value;
  public $creation_dtm;
  public $last_update_dtm;

  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->user_id = 0;
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
    $sql = "call sp_user_property_get";
    $results = MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new UserProperty($result);
    return $values;
  }

  function Create(){
    $value = null;
    $sql = "call sp_user_property_ins(?, ?, ?)";
    $params = array('sss', $this->user_id, $this->property_id, $this->property_value);
    $results = MySQL::Execute($sql, $params);
    if($results){
      $this->user_id = $results[0]->user_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }

  static function DeleteByUser($user_id){
    $sql = "call sp_user_property_del_by_user(?)";
    $params = array('s', $user_id);
    MySQL::Execute($sql, $params);
  }

  static function GetByUser($user_id){
    $values = array();
    $sql = "call sp_user_property_get_by_user(?)";
    $params = array('s', $user_id);
    $results = MySQL::Execute($sql, $params);
    if($results) foreach($results as $result) $values[] = new UserProperty($result);
    return $values;
  }

  static function GetByNameValue($property_name, $property_value){
    $values = array();
    $sql = "call sp_user_property_get_by_name_value(?, ?)";
    $params = array('ss', $property_name, $property_value);
    $results = MySQL::Execute($sql, $params);
    if($results) foreach($results as $result) $values[] = new UserProperty($result);
    return $values;
  }
}
?>