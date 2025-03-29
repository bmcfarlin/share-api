<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Fig\Http\Message\StatusCodeInterface;

class ApiController extends BaseController
{

  public function __construct($registry)
  {
    parent::__construct($registry);
    // trace($_SERVER);
    // trace($_REQUEST);
  }

  public function index()
  {
    print 'Api';
  }

  public function affiliate($id)
  {
    $this->validate();

    $root = new stdClass();
    $root->status = 'success';

    $method = $_SERVER['REQUEST_METHOD'];
    $method_override = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
    $user_method_override = $_REQUEST['USER_METHOD_OVERRIDE'];

    if(isset($method_override)) $method = $method_override;
    if(isset($user_method_override)) $method = $user_method_override;

    $method = strtoupper($method);

    switch($method)
    {
      case 'GET':
        $request = $_REQUEST['request'];
        if(preg_match('/^\/(en|es)\/api\/affiliate\/\d+\/wallet$/', $request)){
          if(isset($id))
          {
            $affiliate = Affiliate::GetById($id);
            if($affiliate){
              $affiliate_id = $affiliate->affiliate_id;
              $item = Wallet::GetByAffiliate($affiliate_id);
              if($item){
                $item->clean();
                $root->items = [$item];
              }else{
                $root->status = 'error';
                $root->error = 'invalid request 306';
              }
            }else{
              $root->status = 'error';
              $root->error = 'invalid request 305';
            }
          }else{
            $root->status = 'error';
            $root->error = 'invalid request 304';
          }

        }else{
          if(isset($id))
          {
            $item = Affiliate::GetById($id);
            if($item){
              $item->clean();
              $root->items = [$item];
            }
          }else{
            $items = Affiliate::GetAll();
            foreach($items as $item) $item->clean();
            $root->items = $items;
          }
        }
        break;
      case 'POST':
        parse_str(file_get_contents("php://input"), $request);
        $partner_id = $request['partner_id'];
        $email = $request['email'];
        if($partner_id){
          $partner = Partner::GetById($partner_id);
          if($partner){
            if($email){
              if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $user = User::GetByEmail($email);
                if(empty($user)){
                  $username = $email;
                  $password = random_password();

                  $user = Membership::CreateUser($username, $password, $email);
                  $user->approved_flag = true;
                  $user->active_flag = true;
                  $user->Save();

                  Roles::AddUserToRole($username, 'User');
                }

                $user_id = $user->user_id;

                $item = Affiliate::GetByPartnerUser($partner_id, $user_id);

                if($item){
                  $item->clean();
                  $root->items = [$item];
                }else{

                  do{
                    $code = random_password(8);
                    $aitem = Affiliate::GetByCode($code);
                  }while($aitem);

                  $item = new Affiliate();
                  $item->partner_id = $partner_id;
                  $item->user_id = $user_id;
                  $item->code = $code;
                  $item->Save();

                  $wallet = new Wallet();
                  $wallet->affiliate_id = $affiliate_id;
                  $wallet->name = 'default';
                  $wallet->Save();

                  $item->clean();
                  $root->items = [$item];

                }
              }else{
                $root->status = 'error';
                $root->error = 'invalid request 303';
              }
            }else{
              $root->status = 'error';
              $root->error = 'invalid request 302';
            }
          }else{
            $root->status = 'error';
            $root->error = 'invalid request 301';
          }
        }else{
          $root->status = 'error';
          $root->error = 'invalid request 300';
        }

        break;
      case 'PUT':
        if(isset($id))
        {
          $item = Affiliate::GetById($id);
          if($item){
            parse_str(file_get_contents("php://input"), $request);
            $code = $request['code'];
            if($code){
              $item->code = $code;
              $item->Save();

              $item->clean();
              $root->items = [$item];
            }else{
              $root->status = 'error';
              $root->error = 'invalid request 101';
            }
          }else{
            $root->status = 'error';
            $root->error = 'invalid request 100';
          }
        }
        break;
      case 'DELETE':
        if(isset($id))
        {
          $item = Affiliate::GetById($id);
          if($item){
            $item->Delete();
          }
        }
        break;
    }

    $this->printJSON($root);
  }

  public function wallet($id)
  {
    $this->validate();

    $root = new stdClass();
    $root->status = 'success';

    $method = $_SERVER['REQUEST_METHOD'];
    $method_override = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
    $user_method_override = $_REQUEST['USER_METHOD_OVERRIDE'];

    if(isset($method_override)) $method = $method_override;
    if(isset($user_method_override)) $method = $user_method_override;

    $method = strtoupper($method);

    switch($method)
    {
      case 'GET':
        $request = $_REQUEST['request'];
        if(preg_match('/^\/(en|es)\/api\/wallet\/\d+\/tx$/', $request)){
          if(isset($id))
          {
            $wallet = Wallet::GetById($id);
            if($wallet){
              $wallet_id = $wallet->wallet_id;
              $items = Tx::GetByWallet($wallet_id);
              foreach($items as $item){
                $item->clean();
              }
              $root->items = $items;
            }else{
              $root->status = 'error';
              $root->error = 'invalid request 401';
            }
          }else{
            $root->status = 'error';
            $root->error = 'invalid request 400';
          }

        }else{
          if(isset($id))
          {
            $item = Wallet::GetById($id);
            if($item){
              $item->clean();
              $root->items = [$item];
            }
          }
        }
        break;
    }

    $this->printJSON($root);
  }

  public function tx($id)
  {
    $this->validate();

    $root = new stdClass();
    $root->status = 'success';

    $method = $_SERVER['REQUEST_METHOD'];
    $method_override = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
    $user_method_override = $_REQUEST['USER_METHOD_OVERRIDE'];

    if(isset($method_override)) $method = $method_override;
    if(isset($user_method_override)) $method = $user_method_override;

    $method = strtoupper($method);

    switch($method)
    {
      case 'GET':
        if(isset($id))
        {
          $item = Tx::GetById($id);
          if($item){
            $item->clean();
            $root->items = [$item];
          }
        }
        break;
        case 'POST':
          parse_str(file_get_contents("php://input"), $request);
          
          $partner_id = $request['partner_id'];
          $email = $request['email'];
          $amount = $request['amount'];
          $tx_type_cd = $request['tx_type_cd'];

          if($partner_id){
            $partner = Partner::GetById($partner_id);
            if($partner){
              if($email){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){

                  $user = User::GetByEmail($email);

                  if(empty($user)){
                    $username = $email;
                    $password = random_password();

                    $user = Membership::CreateUser($username, $password, $email);
                    $user->approved_flag = true;
                    $user->active_flag = true;
                    $user->Save();

                    Roles::AddUserToRole($username, 'User');
                  }

                  $user_id = $user->user_id;

                  $affiliate = Affiliate::GetByPartnerUser($partner_id, $user_id);

                  if(empty($affiliate)){

                    do{
                      $code = random_password(8);
                      $aitem = Affiliate::GetByCode($code);
                    }while($aitem);

                    $affiliate = new Affiliate();
                    $affiliate->partner_id = $partner_id;
                    $affiliate->user_id = $user_id;
                    $affiliate->code = $code;
                    $affiliate->Save();
                  }

                  $affiliate_id = $affiliate->affiliate_id;

                  $wallet = Wallet::GetByAffiliate($affiliate_id);

                  if(empty($wallet)){
                    $wallet = new Wallet();
                    $wallet->affiliate_id = $affiliate_id;
                    $wallet->name = 'default';
                    $wallet->Save();
                  }

                  $wallet_id = $wallet->wallet_id;

                  $tx = new Tx();
                  $tx->wallet_id = $wallet_id;
                  $tx->tx_type_cd = $tx_type_cd;
                  $tx->amount = $amount;
                  $tx->Save();

                  $tx_id = $tx->tx_id;

                  $tx->clean();
                  $root->items = [$tx];

                }else{
                  $root->status = 'error';
                  $root->error = 'invalid request 303';
                }
              }else{
                $root->status = 'error';
                $root->error = 'invalid request 302';
              }
            }else{
              $root->status = 'error';
              $root->error = 'invalid request 301';
            }
          }else{
            $root->status = 'error';
            $root->error = 'invalid request 300';
          }

          break;
        case 'PUT':
          if(isset($id))
          {
            $item = Tx::GetById($id);
            if($item){
              parse_str(file_get_contents("php://input"), $request);
              $amount = $request['amount'];
              if($amount){
                $item->amount = $amount;
                $item->Save();

                $item->clean();
                $root->items = [$item];
              }else{
                $root->status = 'error';
                $root->error = 'invalid request 101';
              }
            }else{
              $root->status = 'error';
              $root->error = 'invalid request 100';
            }
          }
          break;
        case 'DELETE':
          if(isset($id))
          {
            $item = Tx::GetById($id);
            if($item){
              $item->Delete();
            }
          }
          break;
    }

    $this->printJSON($root);
  }
}