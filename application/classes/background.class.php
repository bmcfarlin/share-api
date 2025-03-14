<?php
class Background{

  public $background_id;
  public $agent_name;
  public $entity_name;
  public $phone;
  public $address;
  public $expiration_dtm;
  public $agent_id;


  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->background_id = 0;
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

  function toHtml(){
    $html = sprintf("<div class=\"result %s\">", strtolower(get_class($this)));
    $html .= "<h2>Texas Secretary of State Registration</h2>";
    foreach($this as $key => $value){
      if(strpos($key, "_id") !== false) continue;
      if($value) $html .= sprintf("<label>%s:</label> %s<br/>", label($key), $value);
    }
    $html .= "</div>";
    return $html;
  }

  function GetByName($first_name, $last_name){
    $values = array();
    $sql = "select background_id, agent_name, entity_name, phone, address, expiration_dtm, agent_id from background where agent_name like '%$first_name%' and agent_name like '%$last_name%'";
    $results = MySQL::Execute($sql);
    if($results) foreach($results as $result) $values[] = new Background($result);
    return $values;
  }

}
?>