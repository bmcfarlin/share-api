<?php

abstract class BaseController
{
  protected $registry;

  function __construct($registry)
  {
    $this->registry = $registry;

    $routeinfo = $this->registry->template->routeinfo;
    $controller = strtolower($routeinfo->controller);
    $action = strtolower($routeinfo->action);

    $meta = Meta::GetByControllerAction($controller, $action);

    if($meta)
    {
      if($meta->page_title) $this->registry->template->page_title = $meta->page_title;
      if($meta->page_description) $this->registry->template->page_description = $meta->page_description;
      if($meta->page_img) $this->registry->template->page_img = $meta->page_img;
    }else{
      if($controller != "api" && $controller != "admin")
      {
        $error = sprintf("no meta for %s.%s", $controller, $action);
        trace($error);
      }
    }
  }

  abstract function index();

  protected function noaction($action)
  {
    $s = sprintf("%s does not implement %s action", get_class($this), $action);
    print($s);
  }

  protected function redirect($url)
  {
    trace(sprintf("%s.redirect:%s", get_class($this), $url));

    if(!starts_with($url, 'http'))
    {
      if(!starts_with($url, '/en/'))
      {
        if(!starts_with($url, '/es/'))
        {
          $locale = locale();
          $value = sprintf("/%s", $locale);
          if(!starts_with($url, '/')) $value = sprintf("%s/", $value);
          $url = sprintf("%s%s", $value, $url);
        }
      }

      $https = $_SERVER['HTTPS'];
      $protocol = empty($https)? 'http' : 'https';
      $http_host = $_SERVER['HTTP_HOST'];
      $url = sprintf("%s://%s%s", $protocol, $http_host, $url);

    }
    header("location:$url");
    die;
  }

  protected function authorize()
  {
    $claims = null;

    $root = new stdClass();
    $root->status = 'success';

    $token = null;

    $http_authorization = $_SERVER['HTTP_AUTHORIZATION'];
    if($http_authorization)
    {
      if(preg_match('/^Bearer .*$/i', $http_authorization))
      {
        trace("*************** Bearer token ********************");
        $token = preg_replace('/^Bearer /i', '', $http_authorization);
      }
    }

    $__jwt = $_COOKIE["__jwt"];
    if($__jwt){
      $token = $__jwt;

      trace("*************** __jwt token ********************");

    }

    if($token){


      trace("token:$token");

      $item = Jwt::Validate($token);
      if($item->status == 'success')
      {
        foreach($item->items as $sitem)
        {
          $claims = $sitem;
        }
      }else{
        $root->status = 'error';
        $root->error = $item->error;
      }

    }else{
      $root->status = 'error';
      $root->error = 'Invalid request';
    }

    if($root->status == 'error')
    {
      header('Content-Type: application/json');
      print json_encode($root);
      die;
    }

    trace("===================== claims =============================");
    trace($claims);

    return $claims;
  }
}
