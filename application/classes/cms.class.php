<?php
class Cms{

  // ultimately the best way to handle this is to load all
  // the text up in memory and access it that way;
  // so it would be an in memory respresentation of all the items in the db
  // easy enough; just get all and cache them;
  // yea easy;

  public $cms_id;
  public $name;
  public $en;
  public $es;
  public $creation_user_id;
  public $creation_dtm;
  public $last_update_user_id;
  public $last_update_dtm;

  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->cms_id = 0;
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
    $items = Cms::GetAll();
    foreach($items as $item) $values[$item->cms_id] = $item->name;
    return $values;
  }

  static function GetAll(){
    $values = array();
    $sql = "call sp_cms_get";
    $results = MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new Cms($result);
    return $values;
  }

  static function GetById($cms_id){
    $value = null;
    $sql = "call sp_cms_get_by_id(?)";
    $params = array('i', $cms_id);
    $results = MySQL::Execute($sql, $params);
    if($results) $value = new Cms($results[0]);
    return $value;
  }

  static function GetByName($name, $locale = null, $wrap = true){

    $value = null;

    if(empty($locale)){
      $locale = locale();
      if(empty($locale)) $locale = 'en';
    }

    $key = "Cms:GetByName:$name:$locale";
    $apc_enabled = ini_get('apc.enabled');

    $apc_enabled = false;
    //trace("apc_enabled:$apc_enabled");

    if(!$apc_enabled || !apc_exists($key)){

      $sql = "call sp_cms_get_by_name(?)";
      $params = array('s', $name);
      $results = MySQL::Execute($sql, $params);
      if($results){
        $value = new Cms($results[0]);
        $value = $value->$locale;
      }else{
        $value = sprintf("{%s}", $name);
      }

      if($apc_enabled && !empty($value)) apc_store($key, $value, 0);
    }
    if($apc_enabled) $value = apc_fetch($key);

    if($value) {
      if($wrap){
        if($name != 'site.title' && $name != 'site.description'){
          $value = sprintf("<var class=\"cms\" data-name=\"%s\" data-locale=\"%s\" title=\"%s\">%s</var>", $name, $locale, $name, $value);
        }
      }
    }

    return $value;
  }

  function Create(){
    $value = null;
    $sql = "call sp_cms_ins(?, ?, ?, ?)";
    $params = array('ssss', $this->name, $this->en, $this->es, $this->creation_user_id);
    $results = MySQL::Execute($sql, $params);
    if($results){
      $this->cms_id = $results[0]->cms_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }

  function Update(){
    $value = null;
    $sql = "call sp_cms_upd(?, ?, ?, ?, ?)";
    $params = array('issss', $this->cms_id, $this->name, $this->en, $this->es, $this->last_update_user_id);
    $results = MySQL::Execute($sql, $params);
    if($results){
      $this->last_update_dtm = $results[0]->last_update_dtm;
    }
  }

  function Save(){
    if(empty($this->cms_id)){
      $this->Create();
    }else{
      $this->Update();
    }
  }

  function Delete(){
    $sql = "call sp_cms_del(?)";
    $params = array('i', $this->cms_id);
    MySQL::Execute($sql, $params);
  }

  static function Truncate(){
    $sql = "call sp_cms_trn()";
    MySQL::Execute($sql);
  }

}
?>