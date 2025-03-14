<?php
class Base{
  public $base_id;

  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->base_id = 0;
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

  static function GetName(){
    $r = new Base();
    return get_class($r);
  }

}
?>