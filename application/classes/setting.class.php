<?php
class Setting{
  public $setting_id;
  public $application_id;
  public $setting_name;
  public $setting_value;
  public $creation_dtm;
  public $last_update_dtm;

  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->setting_id = 0;
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
    $sql = "call sp_setting_get";
    $results = MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new Setting($result);
    return $values;
  }

  static function GetById($setting_id){
    $value = null;
    $sql = "call sp_setting_get_by_id(?)";
    $params = array('s', $setting_id);
    $results = MySQL::Execute($sql, $params);
    if($results) $value = new Setting($results[0]);
    return $value;
  }

  function Create(){
    $value = null;
    $sql = "call sp_setting_ins(?, ?, ?)";
    $params = array('sss', $this->application_id, $this->setting_name, $this->setting_value);
    $results = MySQL::Execute($sql, $params);
    if($results){
      $this->setting_id = $results[0]->setting_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }

  function Save(){
    if(empty($this->setting_id)){
      $this->Create();
    }else{
      $value = null;
      $sql = "call sp_setting_upd(?, ?, ?, ?)";
      $params = array('ssss', $this->setting_id, $this->application_id, $this->setting_name, $this->setting_value);
      $results = MySQL::Execute($sql, $params);
      if($results){
        $this->last_update_dtm = $results[0]->last_update_dtm;
      }
    }
  }

  function Delete(){
    $sql = "call sp_setting_del(?)";
    $params = array('s', $this->setting_id);
    MySQL::Execute($sql, $params);
  }

}
?>