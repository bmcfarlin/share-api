<?php
class TxType{
  public $tx_type_cd;
  public $tx_type_name;
  
  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->tx_type_cd = 0;
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
    $items = TxType::GetAll();
    foreach($items as $item) $values[$item->tx_type_cd] = $item->tx_type_name;
    return $values;
  }
  
  static function GetAll(){
    $values = array();
    $sql = "call sp_tx_type_get";
    $results = \MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new TxType($result);
    return $values;
  }
  
  static function GetByCd($tx_type_cd){
    $value = null;
    $sql = "call sp_tx_type_get_by_cd(?)";
    $params = array('s', $tx_type_cd);
    $results = \MySQL::Execute($sql, $params);
    if($results) $value = new TxType($results[0]);
    return $value;
  }
  
  
}
?>