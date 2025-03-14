<?php

  define('SESSION_NAME', 'sh-php-mvc');
  define('SITE_OFFLINE', false);
  define('SITE_OFFLINE_IP', '127.0.0.1');
  define('SITE_LANDING', false);

  include_once(__DIR__ . '/environment.php');

  $s = $_SERVER['PHP_SELF'];
  $p = substr($s,0,strrpos($s,'/'));

  define('APP_NAME', 'sh');
  define('APP_PATH', $p);
  define('APP_ID', 'd771134f-bd86-11e1-a2da-0303030a26a1');

  define('TRACE_ENABLED', true);
  define('SINGLE_LINE_OUTPUT', false);

  define('PASSWORD_MASK', '********');


  $s = $_SERVER['DOCUMENT_ROOT'];
  if( (strrpos($s, '/') !== (strlen($s)-strlen('/'))) ){
    $s = sprintf("%s/", $s);
  }

  define('TRACE_FILE_UNC', $s.'application/logs/trace.log');

  define('FILE_UNC', $s.'public/files');
  define('IMG_UNC', $s.'public/img');
  define('PHOTO_UNC', $s.'public/ph');
  define('PRINTS_UNC', $s.'public/prints');

  define('REDIS_SERVER', '127.0.0.1:6379');

  define('CACHE_TYPE', 'REDIS');
  define('CACHE_ENABLED', false);

  define('CONFIG_CACHE_TYPE', 'VAULT');

  Config::Load('sh.qea.la');

  if(ENVIRONMENT == 'prd'){

    define('SITE_URL', 'https://sh.qea.la');
    define('CDN_URL', 'https://sh.qea.la');
    define('CSS_VERSION', '20171612');
    define('JS_VERSION', CSS_VERSION);

  }else if(ENVIRONMENT == 'uat'){

    define('SITE_URL', 'https://sh.uat.qea.la');
    define('CDN_URL', 'https://sh.uat.qea.la');
    define('CSS_VERSION', '20171612');
    define('JS_VERSION', CSS_VERSION);

  }else if(ENVIRONMENT == 'lcl'){

    define('SITE_URL', 'https://lcl.qea.la:8248');
    define('CDN_URL', 'https://lcl.qea.la:8249');
    define('CSS_VERSION', time());
    define('JS_VERSION', CSS_VERSION);

  }else{

    die('invalid environment');

  }

  define('JWT_COOKIE_NAME', '__jwt');
  define('JWT_COOKIE_DURATION', (90 * 24 * 60 * 60));
  define('JWT_HTTP_ONLY', false);

  define('EMAIL_CONTACT', 'info@qeala.com');
  define('EMAIL_NOTIFY', 'sys@qeala.com');
  define('EMAIL_ADMIN', 'admin@qeala.com');
  define('EMAIL_TEST_PRINT', 'test@qeala.com');
  define('EMAIL_SIGNUP', 'signup@qeala.com');

  define('SMTP_HOST', 'smtp.google.com');
  define('SMTP_USERNAME', 'user@google.com');

  define('META_KEYWORDS', '');
  define('META_DESCRIPTION', '');

  define('APC_KEY', 'sh');

  define('MYSQL_SERVER', '127.0.0.1');
  define('MYSQL_DATABASE', 'sh');
  define('MYSQL_USERNAME', 'webuser');
  define('MYSQL_FILTER', 'call sp_xxx');

  define('MYSQL_DATETIME', 'Y-m-d H:i:s');
  define('MYSQL_INT_UNSIGNED_MAX', 4294967295);
  define('MYSQL_INT_MAX', 2147483647);

  define('FORMAT_JSON', 'Json');

  define('PHOTO_WIDTH_FULL', 640);
  define('MAX_FILE_UPLOAD_NAME', '100Mb');
  define('MAX_FILE_UPLOAD_SIZE', (100 * 1024 * 1024));

  define('SELECT_NONE', 'SelectNone');
  define('SELECT_ALL', 'SelectAll');

  define('ROLE_TYPE_ADMIN', 1);
  define('ROLE_TYPE_OWNER', 2);
  define('ROLE_TYPE_CUSTOMER', 3);

  define('CMD_SUBMIT', 'Submit');
  define('CMD_LOGIN', 'Login');
  define('CMD_EDIT', 'Edit');
  define('CMD_SAVE', 'Save');
  define('CMD_CANCEL', 'Cancel');
  define('CMD_PAGE', 'Page');
  define('CMD_SEARCH', 'Search');
  define('CMD_LIST', 'List');
  define('CMD_VIEW', 'View');
  define('CMD_BACK', 'Back');
  define('CMD_NEW', 'New');
  define('CMD_DELETE', 'Delete');
  define('CMD_RESET', 'Reset');
  define('CMD_CLEAR', 'Clear');
  define('CMD_FILTER', 'Filter');
  define('CMD_PRINT', 'Print');
  define('CMD_EXPORT', 'Export');
  define('CMD_PURGE', 'Purge');
  define('CMD_SORT', 'Sort');
  define('CMD_PREVIEW', 'Preview');
  define('CMD_UNMASK', 'Unmask');
  define('CMD_SIGNIN', 'Sign In');


  define('CMD_NEW_CHILD', 'NewChild');
  define('CMD_SAVE_CHILD', 'SaveChild');
  define('CMD_EDIT_CHILD', 'EditChild');
  define('CMD_CANCEL_CHILD', 'CancelChild');
  define('CMD_LIST_CHILD', 'ListChild');
