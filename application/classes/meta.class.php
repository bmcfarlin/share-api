<?php
class Meta{
  public $meta_id;
  public $controller;
  public $action;
  public $en_page_title;
  public $en_page_description;
  public $en_page_img;
  public $en_share_title;
  public $en_share_description;
  public $en_share_img;
  public $en_share_url;
  public $es_action;
  public $es_page_title;
  public $es_page_description;
  public $es_page_img;
  public $es_share_title;
  public $es_share_description;
  public $es_share_img;
  public $es_share_url;
  public $active_flag;
  public $creation_user_id;
  public $creation_dtm;
  public $last_update_user_id;
  public $last_update_dtm;

  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->meta_id = 0;
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
    $sql = "call sp_meta_get";
    $results = MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new Meta($result);
    return $values;
  }

  static function GetById($meta_id){
    $value = null;
    $sql = "call sp_meta_get_by_id(?)";
    $params = array('i', $meta_id);
    $results = MySQL::Execute($sql, $params);
    if($results) $value = new Meta($results[0]);
    return $value;
  }


  static function GetByControllerAction($controller, $action, $locale = null){
    $value = null;

    if(empty($locale)){
      $locale = locale();
      if(empty($locale)) $locale = 'en';
    }

    $key = "Meta:GetByControllerAction:$controller:$action:$locale";
    $apc_enabled = ini_get('apc.enabled');

    $apc_enabled = false;
    //trace("apc_enabled:$apc_enabled");

    if(!$apc_enabled || !apc_exists($key)){

      $sql = "call sp_meta_get_by_controller_action(?, ?)";
      $params = array('ss', $controller, $action);
      $results = MySQL::Execute($sql, $params);
      if($results){
        $value = new Meta($results[0]);

        $names = array("page_title","page_description","page_img","share_title","share_description","share_img","share_url");
        foreach($names as $name){
          $en = $value->{ "en_$name" };
          $es = $value->{ "es_$name" };
          $value->{$name} = ($locale == 'es' && !empty($es))? $es : $en;
        }

      }

      if($apc_enabled && !empty($value)) apc_store($key, $value, 0);
    }
    if($apc_enabled) $value = apc_fetch($key);

    return $value;
  }


  function Create(){
    $value = null;
    $sql = "call sp_meta_ins(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array('sssssssssssssssssis', $this->controller, $this->action, $this->en_page_title, $this->en_page_description, $this->en_page_img, $this->en_share_title, $this->en_share_description, $this->en_share_img, $this->en_share_url, $this->es_action, $this->es_page_title, $this->es_page_description, $this->es_page_img, $this->es_share_title, $this->es_share_description, $this->es_share_img, $this->es_share_url, to_bit($this->active_flag), $this->creation_user_id);
    $results = MySQL::Execute($sql, $params);
    if($results){
      $this->meta_id = $results[0]->meta_id;
      $this->creation_dtm = $results[0]->creation_dtm;
    }
  }

  function Update(){
    $value = null;
    $sql = "call sp_meta_upd(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array('isssssssssssssssssis', $this->meta_id, $this->controller, $this->action, $this->en_page_title, $this->en_page_description, $this->en_page_img, $this->en_share_title, $this->en_share_description, $this->en_share_img, $this->en_share_url, $this->es_action, $this->es_page_title, $this->es_page_description, $this->es_page_img, $this->es_share_title, $this->es_share_description, $this->es_share_img, $this->es_share_url, to_bit($this->active_flag), $this->last_update_user_id);
    $results = MySQL::Execute($sql, $params);
    if($results){
      $this->last_update_dtm = $results[0]->last_update_dtm;
    }
  }

  function Save(){
    if(empty($this->meta_id)){
      $this->Create();
    }else{
      $this->Update();
    }
  }

  function Delete(){
    $sql = "call sp_meta_del(?)";
    $params = array('i', $this->meta_id);
    MySQL::Execute($sql, $params);
  }

  static function Truncate(){
    $sql = "call sp_meta_trn()";
    MySQL::Execute($sql);
  }

}
?>