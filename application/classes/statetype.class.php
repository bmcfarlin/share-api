<?php
class StateType{
  public $state_type_cd;
  public $state_type_name;
  
  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->state_type_cd = 0;
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
    $items = StateType::GetAll();
    foreach($items as $item) $values[$item->state_type_cd] = $item->state_type_name;
    return $values;
  }
  
  static function GetAll(){
    $values = array();
    $sql = "call sp_state_type_get";
    $results = MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new StateType($result);
    return $values;
  }
  
  static function GetByCd($state_type_cd){
    $value = null;
    $sql = "call sp_state_type_get_by_cd(?)";
    $params = array('s', $state_type_cd);
    $results = MySQL::Execute($sql, $params);
    if($results) $value = new StateType($results[0]);
    return $value;
  }
  
  
}
?>