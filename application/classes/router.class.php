<?php

class Router
{
  private $path, $controller, $action, $id;
  static $instance;

  public function __construct()
  {
    $request = $_GET['request'];
    $split = explode('/',trim($request,'/'));

    $this->locale = !empty($split[0]) ? strtolower($split[0]) : 'en';
    $locales = array('en','es');
    if(!in_array($this->locale, $locales)){
      $split = array('en', 'error404', 'index', null);
      $this->locale = $split[0];
    }


    $this->controller = !empty($split[1]) ? ucfirst($split[1]) : 'Index';
    $this->action = !empty($split[2]) ? $split[2] : 'index';
    $this->id = !empty($split[3]) ? $split[3] : null;

    if($r = strpos($this->action, '?')) $this->action = substr($this->action, 0, $r);
    if($r = strpos($this->id, '?')) $this->id = substr($this->id, 0, $r);

    $this->action = str_replace('-','_', $this->action);

  }

  public function route($registry)
  {

    require_once(__DIR__.'/../controllers/base.controller.php');

    $file = __DIR__.'/../controllers/' . strtolower($this->controller) . '.controller.php';

    $routeinfo = new stdClass();
    $routeinfo->locale = $this->locale;
    $routeinfo->controller = $this->controller;
    $routeinfo->action = $this->action;
    $routeinfo->id = $this->id;

    $uri = sprintf("/%s/%s", $this->locale, $this->controller);
    if(strlen($this->action) > 0){
      $uri = sprintf("%s/%s", $uri, $this->action);
      if(strlen($this->id) > 0){
        $uri = sprintf("%s/%s", $uri, $this->id);
      }
    }

    $locale = $this->locale;

    $routeinfo->uri = strtolower($uri);
    $routeinfo->request_uri = $_SERVER['REQUEST_URI'];

    $registry->template->routeinfo = $routeinfo;

    $file = __DIR__.'/../controllers/' . strtolower($this->controller) . '.controller.php';

    if(strtolower($this->controller) != 'user') unset($_SESSION['return_url']);

    $site_offline = SITE_OFFLINE;
    $site_landing = SITE_LANDING;

    $remote_address  = null;

    if(isset($_SERVER['REMOTE_ADDR'])){
      $remote_address = $_SERVER['REMOTE_ADDR'];
    }

    if(SITE_OFFLINE){
      $site_offline_ips = explode(',', SITE_OFFLINE_IP);
      foreach($site_offline_ips as $site_offline_ip){
        if($site_offline_ip == $remote_address) $site_offline = false;
      }
    }

    if(SITE_LANDING){
      $site_landing_ips = explode(',', SITE_LANDING_IP);
      foreach($site_landing_ips as $site_landing_ip){
        if($site_landing_ip == $remote_address) $site_landing = false;
      }
    }

    if($site_offline && $routeinfo->action != 'offline'){
      redirect("/$locale/offline");
    }else if($site_landing && $routeinfo->action != 'landing'){
      redirect("/$locale/landing");
    }else{
      if(is_readable($file))
      {
        include $file;
        $class = $this->controller . 'Controller';

        $controller = new $class($registry);

        if (is_callable(array($controller, $this->action))){
          $action = $this->action;
          $controller->$action($this->id);
        }else if(strtolower($this->controller) == 'contest'){
          $controller->index($this->action);
        }else if(strtolower($this->controller) == 'n'){
          $controller->index($this->action);
        }else{
          //$controller->noaction($this->action);
          include __DIR__.'/../controllers/error404.controller.php';
          $controller = new Error404Controller($registry);
          $controller->index();
        }
      }
      else
      {
        include __DIR__.'/../controllers/error404.controller.php';
        $controller = new Error404Controller($registry);
        $controller->index();
      }
    }

  }
}