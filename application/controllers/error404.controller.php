<?php

class Error404Controller extends BaseController
{
  public function index()
  {
    $this->registry->template->error = '404 Page not found';
    $this->registry->template->show('error404.list.php');
  }
}