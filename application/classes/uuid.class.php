<?php
class Uuid
{

  function __toString(){
    return get_class($this);
  }

  static function GetNew(){
    $value = null;
    $sql = "call sp_uuid_get()";
    $results = MySQL::Execute($sql);
    if($results) $value = $results[0]->uuid;
    return $value;
  }

}


?>