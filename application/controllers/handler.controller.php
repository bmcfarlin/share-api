<?php

class HandlerController extends BaseController
{
  public function index()
  {
    print 'Handler';
  }
  public function test(){

    // authorize the claims
    $claims = $this->authorize();

    $root = new stdClass();
    $root->status = 'success';

    trace("claims.before");
    trace($claims);

    // modify the claims here
    $claims->tme = time();

    trace("claims.after");
    trace($claims);

    // expose any part of the claims I would like
    $item = new stdClass();
    $item->username = $claims->username;
    $item->tme = $claims->tme;
    $items = [$item];
    $root->items = $items;


    if(JWT_HTTP_ONLY){
      setcookie(JWT_COOKIE_NAME, Jwt::GetNew($claims), time() + JWT_COOKIE_DURATION, '/', null, JWT_HTTP_ONLY, JWT_HTTP_ONLY);
    }else{
      $data = json_decode(json_encode($claims), true);
      $jwt = Jwt::GetNew($data);
      $expires = time() + JWT_COOKIE_DURATION;
      $root->jwt = $jwt;
    }

    header('Content-Type: application/json');
    print json_encode($root);
  }
}
