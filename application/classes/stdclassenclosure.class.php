<?php
class StdClassEnclosure{

  public $sort_id;
  public $sort_order;

  function __construct($sort_id, $sort_order=0){
    $this->sort_id = $sort_id;
    $this->sort_order = $sort_order;
  }

  function cmp($a,$b){
    $s_value = $this->sort_order;
    $f_value = $this->sort_id;
    $a_value = $a->$f_value;
    $b_value = $b->$f_value;


    if($a_value == $b_value) return 0;

    $values = Array($a_value, $b_value);
    natsort($values);
    $values = array_values($values);
    $retval = ($values[$s_value] == $a_value)? -1:1;

    return $retval;
  }
}
?>