<?php
class Property{
  public $property_id;
  public $application_id;
  public $property_name;
  public $creation_dtm;
  public $last_update_dtm;

  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->property_id = 0;
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
    $sql = "call sp_property_get";
    $results = MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new Property($result);
    return $values;
  }

  static function GetById($property_id){
    $value = null;
    $sql = "call sp_property_get_by_id(?)";
    $params = array('s', $property_id);
    $results = MySQL::Execute($sql, $params);
    if($results) $value = new Property($results[0]);
    return $value;
  }

  function Create(){
    $value = null;
    $sql = "call sp_property_ins(?, ?)";
    $params = array('ss', $this->application_id, $this->property_name);
    $results = MySQL::Execute($sql, $params);
    if($results){
      $this->property_id = $results[0]->property_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }

  function Save(){
    if(empty($this->property_id)){
      $this->Create();
    }else{
      $value = null;
      $sql = "call sp_property_upd(?, ?, ?)";
      $params = array('sss', $this->property_id, $this->application_id, $this->property_name);
      $results = MySQL::Execute($sql, $params);
      if($results){
        $this->last_update_dtm = $results[0]->last_update_dtm;
      }
    }
  }

  function Delete(){
    $sql = "call sp_property_del(?)";
    $params = array('s', $this->property_id);
    MySQL::Execute($sql, $params);
  }

  static function GetByApplicationPropertyName($application_id, $property_name){
    $value = null;
    $sql = "call sp_property_get_by_application_property_name(?, ?)";
    $params = array('ss', $application_id, $property_name);
    $results = MySQL::Execute($sql, $params);
    if($results) $value = new Property($results[0]);
    return $value;
  }

}
?>