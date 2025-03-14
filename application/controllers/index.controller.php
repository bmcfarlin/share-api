<?php

class IndexController extends BaseController
{
  public function index()
  {
    $this->registry->template->scripts = array('/js/jquery.validate.js','/js/index.list.js');
    $this->registry->template->show('index.list.php');
  }

}
