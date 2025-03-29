<?php

use Vault\AuthenticationStrategies\AppRoleAuthenticationStrategy;
use Vault\AuthenticationStrategies\UserPassAuthenticationStrategy;
use Vault\AuthenticationStrategies\TokenAuthenticationStrategy;
use Vault\Client;
use Laminas\Diactoros\RequestFactory;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\Uri;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Aws\SecretsManager\SecretsManagerClient; 
use Aws\Exception\AwsException;

class Config{

  function __toString(){
    return get_class($this);
  }

  static function Load($name, $env = ENVIRONMENT, $config_cache_type = CONFIG_CACHE_TYPE){

    $debug = true;

    $logger = null;

    if($debug){
      $file_path = '/var/log/logger/config.log';
      $file_path = realpath($file_path);
    
      if(!file_exists($file_path)){
        ob_clean();
        print("file $file_path does not exist");
        die;
      }
      $logger = new Logger('config');
      $logger->pushHandler(new StreamHandler($file_path, Logger::DEBUG));
    }


    $items = Config::GetAll($name, $env, $config_cache_type);
    foreach($items as $key => $value){
      $logger->debug("KEY", [$key]);
      $logger->debug("VALUE", [$value]);
      define($key, $value);
    }
  }

  static function GetAll($name, $env = ENVIRONMENT, $config_cache_type = CONFIG_CACHE_TYPE){

    $debug = true;

    $values = array();

    $logger = null;

    if($debug){
      $file_path = '/var/log/logger/config.log';
      $file_path = realpath($file_path);
    
      if(!file_exists($file_path)){
        ob_clean();
        print("file $file_path does not exist");
        die;
      }
      $logger = new Logger('config');
      $logger->pushHandler(new StreamHandler($file_path, Logger::DEBUG));
    }

    $key = "$name.$env";
    if($debug){
      $logger->debug("KEY", [$key]);
      $logger->debug("CONFIG_CACHE_TYPE", [$config_cache_type]);
      $logger->debug("CACHE_ENABLED", [CACHE_ENABLED]);
    }

    if($config_cache_type == 'VAULT'){

      if(!CACHE_ENABLED || !\Cache::Exists($key)){

        $vault_addr = getenv('VAULT_ADDR');
        if(empty($vault_addr)){
          ob_clean();
          print("vault_addr is null");
          die;
        }

        $vault_token = getenv('VAULT_TOKEN');
        if(empty($vault_token)){
          ob_clean();
          print("vault_token is null");
          die;
        }

        if($debug){
          $logger->debug("VAULT_ADDR", [$vault_addr]);
          $logger->debug("VAULT_TOKEN", [$vault_token]);
        }

        $client = new Client(
          new Uri($vault_addr),
          new \Guzzle\Client(),
          new RequestFactory(),
          new StreamFactory(),
          $logger
        );

        $strategy = new TokenAuthenticationStrategy($vault_token);
        $client->setAuthenticationStrategy($strategy);
        $authenticated = $client->authenticate();

        if($debug){
          $logger->debug('AUTHENTICATED', [$authenticated]);
        }

        try{
          $path = "/secret/data/$env/$name";
          $response = $client->read($path);
          $data = $response->getData();

          if($debug){
            $logger->debug('DATA', $data);
          }

          if(array_key_exists('data', $data)){
            $data = $data['data'];
            foreach($data as $key => $value){
              $value = unserialize($value);
              $values[$key] = $value;
            }
          }

          if(CACHE_ENABLED) \Cache::Store($key, $values, 0);

        }catch(Exception $exc){
          if($debug){
            $logger->debug('ERROR', [$exc->getMessage()]);
          }
        }
      }
      if(CACHE_ENABLED) $values = \Cache::Fetch($key);

    }else{

      if(!CACHE_ENABLED || !\Cache::Exists($key)){

        $aws_access_key_id = getenv('AWS_ACCESS_KEY_ID');
        if(empty($aws_access_key_id)){
          ob_clean();
          print("aws_access_key_id is null");
          die;
        }

        $aws_secret_access_key = getenv('AWS_SECRET_ACCESS_KEY');
        if(empty($aws_secret_access_key)){
          ob_clean();
          print("aws_secret_access_key is null");
          die;
        }

        $config = [
          'version' => '2017-10-17',
          'region' => 'us-west-2',
          'credentials' => [
            'key' => $aws_access_key_id,
            'secret' => $aws_secret_access_key
          ]
        ];

        if($debug){
          $logger->debug('CONFIG', [$config]);
        }

        $client = new SecretsManagerClient($config);

        try {

            $value = [
              'SecretId' => $key
            ];

            if($debug){
              $logger->debug('VALUE', [$value]);
            }

            $result = $client->getSecretValue($value);

            if($debug){
              $data = sprintf("%s", $result);
              $logger->debug('RESULT', [$data]);
            }

            $json = $result->get('SecretString');

            $values = json_decode($json, true);

            if($debug){
              $logger->debug('ITEMS', $values);
            }

            if(CACHE_ENABLED) \Cache::Store($key, $values, 0);

        } catch (AwsException $exc) {
          if($debug){
            $logger->debug('ERROR', [$exc->getMessage()]);
          }
        }

      }
    
      if(CACHE_ENABLED) $values = \Cache::Fetch($key);
    }

    if($debug){
      $logger->debug('VALUES', $values);
    }

    return $values;
  }

  static function Cache($name, $kvps, $env = ENVIRONMENT, $config_cache_type = CONFIG_CACHE_TYPE){
  
    $debug = true;
  
    $logger = null;

    if($debug){
      $file_path = '/var/log/logger/config.log';
      $file_path = realpath($file_path);
      
      if(!file_exists($file_path)){
        ob_clean();
        print("file $file_path does not exist");
        die;
      }

      $logger = new Logger('config');
      $logger->pushHandler(new StreamHandler($file_path, Logger::DEBUG));
    }

    $key = "$name.$env";
    if($debug){
      $logger->debug("KEY", [$key]);
      $logger->debug("CONFIG_CACHE_TYPE", [$config_cache_type]);
      $logger->debug("CACHE_ENABLED", [CACHE_ENABLED]);
    }

    if($config_cache_type == 'VAULT'){

      $vault_addr = getenv('VAULT_ADDR');
      if(empty($vault_addr)){
        ob_clean();
        print("vault_addr is null");
        die;
      }

      $vault_token = getenv('VAULT_TOKEN');
      if(empty($vault_token)){
        ob_clean();
        print("vault_token is null");
        die;
      }

      if($debug){
        $logger->debug("VAULT_ADDR", [$vault_addr]);
        $logger->debug("VAULT_TOKEN", [$vault_token]);
      }

      $client = new Client(
        new Uri($vault_addr),
        new \Guzzle\Client(),
        new RequestFactory(),
        new StreamFactory(),
        $logger
      );

      $strategy = new TokenAuthenticationStrategy($vault_token);
      $client->setAuthenticationStrategy($strategy);
      $authenticated = $client->authenticate();

      if($debug){
        $logger->debug('AUTHENTICATED', [$authenticated]);
      }

      try{
        $data = [];
        foreach($kvps as $key => $value){
          $data[$key] = serialize($value);
        }
        $path = "/secret/data/$env/$name";
        $response = $client->write($path, ['data' => $data]);
        $data = $response->getData();
        
        if($debug){
          $logger->debug('DATA', $data);
        }

        if(CACHE_ENABLED) \Cache::Delete($key);

      }catch(Exception $exc){
        if($debug){
          $logger->debug('ERROR', [$exc->getMessage()]);
        }
      }

    }else{

      $aws_access_key_id = getenv('AWS_ACCESS_KEY_ID');
      if(empty($aws_access_key_id)){
        ob_clean();
        print("vault_addr is null");
        die;
      }

      $aws_secret_access_key = getenv('AWS_SECRET_ACCESS_KEY');
      if(empty($aws_secret_access_key)){
        ob_clean();
        print("vault_addr is null");
        die;
      }

      $config = [
        'version' => '2017-10-17',
        'region' => 'us-west-2',
        'credentials' => [
          'key' => $aws_access_key_id,
          'secret' => $aws_secret_access_key
        ]
      ];

      if($debug){
        $logger->debug('CONFIG', [$config]);
      }

      $client = new SecretsManagerClient($config);

      $name = "$name.$env";
      $item = (object)$kvps;
      $secret = json_encode($item);
      $description = "describe $name";

      try {

        $options = ['Filters' => [['Key' => 'name', 'Values' => [$name]]]];
        $result = $client->listSecrets($options);
        $items = $result->get('SecretList');

        if($debug){
          $logger->debug('ITEMS', $items);
        }

        if($items){

          $value = [
            'SecretId' => $name,
            'SecretString' => $secret,
          ];

          if($debug){
            $logger->debug('VALUE', [$value]);
          }

          $result = $client->putSecretValue($value);

          if(CACHE_ENABLED) \Cache::Delete($key);

        }else{

          $value = [
            'Name' => $name,
            'SecretString' => $secret,
            'Description' => $description,
          ];

          if($debug){
            $logger->debug('VALUE', [$value]);
          }

          $result = $client->createSecret($value);

          if(CACHE_ENABLED) \Cache::Delete($key);
        }

        if($debug){
          $data = sprintf("%s", $result);
          $logger->debug('RESULT', [$data]);
        }

      } catch (AwsException $exc) {
        if($debug){
          $logger->debug('ERROR', [$exc->getMessage()]);
        }
      }
       
    }
  }

}
