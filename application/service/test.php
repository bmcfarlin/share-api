<?php

  declare(strict_types=1);

  use Monolog\Logger;
  use Monolog\Handler\StreamHandler;

  error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);

  include_once(__DIR__ . '/../../vendor/autoload.php');
  include_once(__DIR__ . '/../settings.php');
  include_once(__DIR__ . '/../common.php');

  $dry_run = false;
  $production = false;
  $name = null;

  $opts = getopt('d::p::n::', array('dry-run::','production::','name::'));

  if(array_key_exists('d', $opts))
  {
    $dry_run = true;
  }
  else if(array_key_exists('dry-run', $opts))
  {
    $dry_run = true;
  }

  if(array_key_exists('p', $opts))
  {
    $production = true;
  }
  else if(array_key_exists('production', $opts))
  {
    $production = true;
  }

  if(array_key_exists('n', $opts))
  {
    $name = $opts['n'];
  }
  else if(array_key_exists('name', $opts))
  {
    $name = $opts['name'];
  }

  if(ENVIRONMENT == 'prd'){
    if($production){
      // do nothing
    }else{
      print("production environment\n");
      die;
    }
  }
