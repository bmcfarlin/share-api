<?php
class Contact{
  public $contact_id;
  public $defendant_id;
  public $contact_type_cd;
  public $first_name;
  public $last_name;
  public $address;
  public $city;
  public $state_type_cd;
  public $zip;
  public $phone;
  public $age;
  public $school;
  public $creation_user_id;
  public $creation_dtm;
  public $last_update_user_id;
  public $last_update_dtm;
  
  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->contact_id = 0;
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
    $sql = "call sp_contact_get";
    $results = MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new Contact($result);
    return $values;
  }
  
  static function GetById($contact_id){
    $value = null;
    $sql = "call sp_contact_get_by_id(?)";
    $params = array('i', $contact_id);
    $results = MySQL::Execute($sql, $params);
    if($results) $value = new Contact($results[0]);
    return $value;
  }
  
  function Create(){
    $value = null;
    $sql = "call sp_contact_ins(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array('issssssssiss', $this->defendant_id, $this->contact_type_cd, $this->first_name, $this->last_name, $this->address, $this->city, $this->state_type_cd, $this->zip, $this->phone, $this->age, $this->school, $this->creation_user_id);
    $results = MySQL::Execute($sql, $params);
    if($results){
      $this->contact_id = $results[0]->contact_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }
  
  function Update(){
    $value = null;
    $sql = "call sp_contact_upd(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array('iissssssssiss', $this->contact_id, $this->defendant_id, $this->contact_type_cd, $this->first_name, $this->last_name, $this->address, $this->city, $this->state_type_cd, $this->zip, $this->phone, $this->age, $this->school, $this->last_update_user_id);
    $results = MySQL::Execute($sql, $params);
    if($results){
      $this->last_update_dtm = $results[0]->last_update_dtm;
    }
  }
  
  function Save(){
    if(empty($this->contact_id)){
      $this->Create();
    }else{
      $this->Update();
    }
  }
  
  function Delete(){
    $sql = "call sp_contact_del(?)";
    $params = array('i', $this->contact_id);
    MySQL::Execute($sql, $params);
  }
  
  function Truncate(){
    $sql = "call sp_contact_trn()";
    MySQL::Execute($sql);
  }
  
}
?>