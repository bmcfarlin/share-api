<?php

class Template
{
  private $vars = array();

  public function __set($index, $value)
  {
    $this->vars[$index] = $value;
  }

  public function __get($index)
  {
    return $this->vars[$index];
  }

  public function __construct()
  {
    $layout = new stdClass();
    $layout->header = 'application/views/layout/header.php';
    $layout->footer = 'application/views/layout/footer.php';
    $this->layout = $layout;
  }

  public function SetLayout($layout){
    $this->layout = $layout;
  }

  private function is_assoc ($arr) {
    return (is_array($arr) && count(array_filter(array_keys($arr),'is_string')) == count($arr));
  }


  public function show($viewName)
  {
    try
    {
      $file = 'application/views/' . $viewName;

      if (!file_exists($file))
        throw new Exception('View ' . $viewName . ' not found.');   else

      foreach ($this->vars as $key => $value)
      {
        $$key = $value;
      }

      $locale = $this->routeinfo->locale;

      include($file);
    }
    catch(Exception $e)
    {
      echo $e->getMessage();
      exit(0);
    }
  }
}