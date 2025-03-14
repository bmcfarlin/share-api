<?php
class Profile{
  public $user_id;

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

  function GetId(){
    return $this->base_id;
  }

  static function GetByUser($user_id){
    $obj = new stdClass();
    $obj->user_id = $user_id;

    $properties = UserProperty::GetByUser($user_id);

    foreach($properties as $property){
      $property_name = $property->property_name;
      $property_value = $property->property_value;
      $obj->$property_name = $property_value;
    }

    $prof = new Profile($obj);

    return $prof;
  }

  static function GetByProperty($property_name, $property_value){
    $values = array();

    $properties = UserProperty::GetByNameValue($property_name, $property_value);

    foreach($properties as $property){
      $obj = new stdClass();
      $property_name = $property->property_name;
      $property_value = $property->property_value;
      $obj->user_id = $property->user_id;
      $obj->$property_name = $property_value;
      $values[] = new Profile($obj);
    }

    return $values;
  }

  function Save(){

    UserProperty::DeleteByUser($this->user_id);

    foreach($this as $key => $value){
      trace($key);
      if($key != 'user_id'){
        $property = Property::GetByApplicationPropertyName(APP_ID, $key);
        if($property){
          $user_property = new UserProperty();
          $user_property->user_id = $this->user_id;
          $user_property->property_id = $property->property_id;
          $user_property->property_value = $this->$key;
          $user_property->Create();
        }
      }
    }
  }

  function Delete(){
    UserProperty::DeleteByUser($this->user_id);
  }

}
?>