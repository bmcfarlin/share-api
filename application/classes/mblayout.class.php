<?php
class MbLayout implements PageLayout {

  public $page_view_count;

  function __construct(){
    $num_args = func_num_args();
    switch($num_args){
      case 0:
        $this->page_view_count = 10;
      break;
      case 1:
        $this->page_view_count = func_get_arg(0);
      break;
    }
  }
  function __toString(){
    return get_class($this);
  }

  public function fetchPagedLinks($parent, $queryVars) {
    $item_count = $parent->getCount();
    $item_count_total = $parent->getCountTotal();
    $page_number = $parent->getPageNumber();
    $page_count_total = $parent->fetchNumberPages();


    $item_view_count = $parent->pageSize;

    //var_dump($item_view_count);

    $page_view_start = (floor(($page_number-1)/$this->page_view_count) * $this->page_view_count)+1;
    $page_view_end = $page_view_start + $this->page_view_count;

    $html = "<div class=\"pager-mblayout\">";
    if(!$parent->isFirstPage()) {
      $page_number_prev = $page_number - $this->page_view_count;
      if($page_number_prev < 1) $page_number_prev = 1;
      $html .= sprintf(" %s ", LinkButton::GetHtml('<', 'Page', $page_number_prev));
    }
    for($i=$page_view_start;$i<$page_view_end;$i++){
      $item_view_start = (($i-1) * $item_view_count) + 1;
      //$num = str_pad($i, 2, "0", STR_PAD_LEFT);
      $num = $i;
      if($i == $page_number || $item_view_start > $item_count_total){
        $html .= sprintf("<span> %s </span>", $num);
      }else{
        $html .= sprintf(" %s ", LinkButton::GetHtml($num, 'Page', $i));
      }
    }
    if(!$parent->isLastPage()) {
      $page_number_next = $page_number + $this->page_view_count;
      if($page_number_next > $page_count_total) $page_number_next = $page_count_total;
      $html .= sprintf(" %s ", LinkButton::GetHtml('>', 'Page', $page_number_next));
    }
    $html .= "</div>";
    return $html;
  }
}

?>