<?php

class MySQL{
  function __construct(){
  }
  function __toString(){
    return get_class($this);
  }

  static function Execute(){

    $sql = null;
    $items = array();
    $params = array();
    $mysql_database = MYSQL_DATABASE;

    $num_args = func_num_args();
    switch($num_args){
      case 1:
        $sql = func_get_arg(0);
      break;
      case 2:
        $sql = func_get_arg(0);
        $value = func_get_arg(1);
        if($value) $params = $value;
      break;
      case 3:
        $sql = func_get_arg(0);
        $value = func_get_arg(1);
        if($value) $params = $value;
        $value = func_get_arg(2);
        if($value) $mysql_database = $value;
      break;
    }

    //trace('MYSQL_SERVER:'.MYSQL_SERVER);
    //trace('MYSQL_USERNAME:'.MYSQL_USERNAME);
    //trace('MYSQL_PASSWORD:'.MYSQL_PASSWORD);
    //trace('MYSQL_DATABASE:'.$mysql_database);

    $mysqli = new mysqli(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD, $mysql_database);
    if($mysqli->connect_errno) {
      $msg = sprintf("MySQL connect failed: %s", $mysqli->error);
      trace($msg);
      die("$msg\n");
    }

    $charset = $mysqli->character_set_name();
    //trace(sprintf("Initial character set is %s", $charset));

    if (!$mysqli->set_charset("utf8mb4")) {
      $msg = sprintf("Error loading character set utf8mb4: %s", $mysqli->error);
      trace($msg);
      die("$msg\n");
    }

    $charset = $mysqli->character_set_name();
    //trace(sprintf("Current character set is %s", $charset));

    if(!empty($params) && count($params) > 1){

      $param_types = str_split($params[0]);
      $param_types_count = count($param_types);
      $param_count = count($params)-1;
      $place_count = substr_count($sql, '?');

      if($param_types_count == $param_count && $param_count == $place_count){
        $sqlo = '';
        $i = 0;
        $offset = 0;
        while(($p = strpos($sql, '?', $offset)) !== false){
          $sql_value = substr($sql, $offset, ($p - $offset));
          $param = $params[$i+1];
          $param_type = $param_types[$i];
          $param_value = sprintf("%s", $param);
          $param_value = $mysqli->real_escape_string($param_value);
          if(($param_type == 's' || $param_type == 'b') && strlen($param_value) > 0) $param_value = sprintf("'%s'", $param_value);
          if(strlen($param_value) == 0) $param_value = 'null';
          if($param_value == 'on' && $param_type == 'i') $param_value = 1;
          if($param_value == 'off' && $param_type == 'i') $param_value = 0;
          $sqlo .= sprintf("%s%s", $sql_value, $param_value);
          $offset = $p+1;
          $i++;
        }
        if($offset <= strlen($sql)) $sqlo .= substr($sql, $offset);
        $sql = $sqlo;
      }
    }

    if(strpos($sql, MYSQL_FILTER) === 0){
      trace($sql);
    }

    $b = $mysqli->multi_query($sql);
    if(!$b){
      $values = $mysqli->error_list;
      $msg = 'multi_query failed:'.$mysqli->errno.':'.$mysqli->error.':'.$sql;
      trace($msg);
      die("$msg\n");
    }

    //$b = true;
    do{
      $result = $mysqli->store_result();

      if($result && gettype($result) == 'object'){
        while($item = $result->fetch_object()){
          $items[] = $item;
        }
        $result->free();
      }

      $b = $mysqli->more_results();
      if($b){
        $mysqli->next_result();
      }

    }while($b);

    $mysqli->close();

    //trace(count($items));

    return $items;
  }

}
