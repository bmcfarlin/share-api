<?php

  function error_handler ($errorNumber, $message, $errfile, $errline) {
    $enabled = false;

    switch ($errorNumber) {
      case E_ERROR :
        $errorLevel = 'Error';
        $enabled = true;
        break;
      case E_WARNING :
        $errorLevel = 'Warning';
        $enabled = false;
        break;
      case E_NOTICE :
        $errorLevel = 'Notice';
        $enabled = false;
        break;
      default :
        $errorLevel = 'Undefined';
        $enabled = false;
    }

    if($enabled){
      $s = $_SERVER['DOCUMENT_ROOT'];
      if(!ends_with($s, '/')) $s = sprintf("%s/", $s);
      $errfile = str_replace($s, "", $errfile);
      echo sprintf("<div class=\"handler %s\">%s: %s (%s:%d)</div>", strtolower($errorLevel), $errorLevel, $message, $errfile, $errline);
    }
  }
  function locale(){
    global $registry;
    $result = null;
    $routeinfo = $registry->template->routeinfo;
    if($routeinfo) $result = $routeinfo->locale;
    else die('routeinfo not found');
    return $result;
  }
  function random_code($chars = 6) {
     $letters = '123456789';
     return substr(str_shuffle($letters), 0, $chars);
  }
  function random_password($chars = 8) {
     $letters = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
     return substr(str_shuffle($letters), 0, $chars);
  }
  function label($value){
    $values = explode('_', $value);
    for($i=0;$i<count($values);$i++){
      $values[$i] = ucfirst($values[$i]);
    }
    $value = implode(' ', $values);
    $value = str_replace(' Flag', '', $value);
    $value = str_replace(' Id', '', $value);
    $value = str_replace('_Type', 'Type', $value);
    $value = str_replace('Type', ' Type', $value);
    $value = str_replace('Creation Dtm', 'Created', $value);
    $value = str_replace('Last Update Dtm', 'Updated', $value);
    $value = str_replace('Ext', 'Type', $value);
    $value = str_replace(' Type Cd', '', $value);
    $value = str_replace(' Dtm', ' Date', $value);
    $value = str_replace('Full Address', 'Address', $value);
    return $value;
  }
  function to_bit($s){
    return ($s === "on" || $s === "1" || $s === 1) ? 1 : 0;
  }
  function yes_no($i){
    return empty($i)?'No':'Yes';
  }
  function to_date($sdtm = null){
    //return date("F j, Y", strtotime($sdtm));
    if(empty($sdtm)) $sdtm = 'now';
    return date("Y-m-d", strtotime($sdtm));
  }
  function to_money($n){
    $s = money_format('$%i', $n);
    $s = preg_replace("/USD /", '', $s);
    $s = preg_replace("/\.00/", '.', $s);
    return $s;
  }
  function to_height($n){
    if(is_numeric($n)){
      $n = intval($n);
      $f = floor($n/12);
      $i = $n % 12;
      $s = sprintf("%d' %d\"", $f, $i);
    }
    return $s;
  }
  function to_weight($n){
    $s = $n;
    if(is_numeric($n)){
      $n = intval($n);
      $s = sprintf("%s lbs.", $n);
    }
    return $s;
  }
  function to_display_date($sdtm){
    return date("F j, Y", strtotime($sdtm));
  }
  function due_day($sdtm = null){
    if(empty($sdtm)) $sdtm = 'now';
    $dtm = new DateTime($sdtm, new DateTimeZone('UTC'));
    $dtm->setTimezone(new DateTimeZone('EST'));
    // if(date('I', time())){
    // }else{
    //   $dtm = $dtm->add(new DateInterval('PT3600S'));
    // }
    return $dtm->format('j');
  }
  function to_display_date_time($sdtm){
    $dtm = new DateTime($sdtm, new DateTimeZone('UTC'));
    $dtm->setTimezone(new DateTimeZone('EST'));
    // if(date('I', time())){
    // }else{
    //   $dtm = $dtm->add(new DateInterval('PT3600S'));
    // }
    return $dtm->format('F j, Y g:i A T');
    //return $dtm->format('n/j/y g:i A T');
  }
  function to_search_date($sdtm){
    $dtm = new DateTime($sdtm, new DateTimeZone('UTC'));
    $dtm->setTimezone(new DateTimeZone('EST'));
    // if(date('I', time())){
    // }else{
    //   $dtm = $dtm->add(new DateInterval('PT3600S'));
    // }
    return $dtm->format('n/j/y');
  }
  function to_date_time($sdtm){
    return date("F j, Y g:i A T", strtotime($sdtm));
  }
  function to_sql_date_time($sdtm  = null){
    if(empty($sdtm)) $sdtm = 'now';
    return date("Y-m-d H:i:s", strtotime($sdtm));
  }
  function utc_timestamp(){
    $result = null;
    list($usec, $sec) = explode(' ', microtime());
    $msec = round( floatval($usec) * 1000000);
    $msec = str_pad($msec, 6, "0", STR_PAD_LEFT);
    $result = sprintf("%s.%s", gmdate('Y-m-d H:i:s', $sec),  $msec);
    return $result;
  }
  function server($key){
    $result = '';
    if(isset($_SERVER[$key])){
      $result = $_SERVER[$key];
    }
    return $result;
  }
  function session($key, $value = "default_value"){
    $result = '';
    if($key){
      if($value != "default_value"){
        $_SESSION[$key] = $value;
      }
      if(isset($_SESSION[$key])){
        if(empty($value)){
          unset($_SESSION[$key]);
        }else{
          $result = $_SESSION[$key];
        }
      }
    }
    return $result;
  }
  function request($key){
    $result = '';
    if(isset($_REQUEST[$key])){
      $result = $_REQUEST[$key];
    }
    return $result;
  }
  function post_id(){
    $t = microtime(true);
    $micro = sprintf("%06d",($t - floor($t)) * 1000000);
    $d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
    $dtm =  $d->format("Y-m-d H:i:s.u");
    $result = md5($dtm.session_id());
    return $result;
  }
  function is_post_back(){
    $value = false;
    $request_uri = $_SERVER['REQUEST_URI'];
    if(!preg_match('/^.*\/(handler)\/.*$/', $request_uri)){
      if(isset($_REQUEST['post_id'])){
        $value = true;
      }
    }
    return $value;
  }
  function is_double_post_back(){
    $value = false;
    $request_uri = $_SERVER['REQUEST_URI'];
    if(!preg_match('/^.*\/(handler)\/.*$/', $request_uri)){
      if(isset($_REQUEST['post_id'])){
        $post_id = $_REQUEST['post_id'];
        if(isset($_SESSION['last_post_id'])){
          $last_post_id = $_SESSION['last_post_id'];
          $value = ($post_id == $last_post_id);
        }
        $_SESSION['last_post_id'] = $post_id;
      }
    }
    return $value;
  }
  function trace($s, $j=0){
    global $starttime;

    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $mtime = $mtime[1] + $mtime[0];
    $endtime = $mtime;
    $totaltime = ($endtime - $starttime);
    $sdtm = $totaltime;

    $dtm = new DateTime('now');
    $sdtm = $dtm->format("Y-m-d H:i:s.u");

    if(!is_scalar($s)){
      $s = json_encode($s, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
    if(TRACE_ENABLED){
      if(php_sapi_name() == 'cli'){
        print "$s\n";
      }else{
        error_log(sprintf("%s %s \n", $sdtm, $s), 3, TRACE_FILE_UNC);
      }
    }
  }
  function session_init(){
    session_start();
    //trace(sprintf("session_id:%s", session_id()));
  }
  function contains($h, $n){
    return (strpos($h, $n) !== false);
  }
  function starts_with($h, $n){
    return (strpos($h, $n) === 0);
  }
  function ends_with($h, $n){
    return (strrpos($h, $n) === (strlen($h)-strlen($n)));
  }
  function is_assoc($arr){
    return array_keys($arr) != range(0, count($arr) - 1);
  }
  function cmp($a, $b){
    return $a->cmp($b);
  }
  function is_masked($value){
    return starts_with($value, '*');
  }
  function to_phone($s){
    $result = $s;
    $s = preg_replace('/(^1|^\+1)/', '', $s);
    if (preg_match('/^\d{10}$/', $s)){
      $npa = substr($s, 0, 3);
      $nxx = substr($s, 3, 3);
      $xxxx = substr($s, 6);
      $result = sprintf("(%s) %s-%s", $npa, $nxx, $xxxx);
    }
    return $result;
  }
  function to_fb_date($s){
    $value = null;

    $sdtm = new DateTime($s, new DateTimeZone('UTC'));
    $ndtm = new DateTime('now', new DateTimeZone('UTC'));
    $ddtm = new DateTime(gmdate('Y-m-d'), new DateTimeZone('UTC'));

    $su = $sdtm->format('U');
    $nu = $ndtm->format('U');
    $du = $ddtm->format('U');

    $tu = ($du - $su);
    if($tu <= 0){
      $uu = ($nu - $su);
      if($uu >= 0){
        if($uu > 3600){
          $hours = round($uu / 3600);
          $value = "About $hours hours";
        }else if($uu > 120){
          $minutes = round($uu / 60);
          $value = "$minutes minutes";
        }else if($uu > 60){
          $value = "1 minute";
        }else{
          $value = "Just Now";
        }
      }else{
        $value = "Just Now";
      }
    }else{
      $uu = ($nu - $su);
      if($uu >= 0){
        if($uu > 7 * 86400){
          $value = sprintf("%s", $sdtm->format('M j'));
        }else if($uu > 86400){
          $value = sprintf("%s", $sdtm->format('l'));
        }else if($uu > 3600){
          $hours = round($uu / 3600);
          $value = "About $hours hours";
        }else if($uu > 120){
          $minutes = round($uu / 60);
          $value = "$minutes minutes";
        }else if($uu > 60){
          $value = "1 minute";
        }else{
          $value = "Just Now";
        }
      }else{
        $value = "Just Now";
      }
    }

    return $value;
  }
  function mask($s, $n=0){
    $value = "";
    if($n == 0){
      $left = $s;
      $right = "";
    }else if($n > 0){
      if($n > strlen($s)) $n = strlen($s);
      $left = substr($s, 0, strlen($s)-$n);
      $right = substr(
        $s, -$n, $n);
    }
    $mask = str_pad('', strlen($left), '*', STR_PAD_LEFT);
    $value = sprintf("%s%s", $mask, $right);
    return $value;
  }
  function seo_name($s){
    if($s == null) return '';
    $s = trim($s);
    $s = preg_replace("/<.*?>/", '', $s);
    $strips = array("%", "™", "®", ",", "<sup>", "</sup>", "reg;", "eacute;", "trade;", "copy;", "trad;", "&", "'", "’", ".", "®", "©");
    foreach($strips as $strip) $s = str_replace($strip, '', $s);
    $s = preg_replace('/[\s+|\/]+/', '-', $s);
    $s = preg_replace('/[-+]+/', '-', $s);
    $s = preg_replace('/[ÀÁÂÃÄÅàáâãäå]+/', 'a', $s);
    $s = preg_replace('/[Çç]+/', 'c', $s);
    $s = preg_replace('/[ÈÉÊËèéêë]+/', 'e', $s);
    $s = preg_replace('/[ÌÍÎÏìíîï]+/', 'i', $s);
    $s = preg_replace('/[Ññ]+/', 'n', $s);
    $s = preg_replace('/[ÒÓÔÕÖòóôõö]+/', 'o', $s);
    $s = preg_replace('/[ÙÚÛÜùúûü]+/', 'u', $s);
    $s = preg_replace('/[Ýýÿ]+/', 'y', $s);
    $s = strtolower($s);
    return $s;
  }
  function array_copy ($aSource) {
    if (!is_array($aSource)) {
      throw new Exception("Input is not an Array");
    }
    $aRetAr = array();
    $aKeys = array_keys($aSource);
    $aVals = array_values($aSource);
    for ($x=0;$x<count($aKeys);$x++) {
      if (is_object($aVals[$x])) {
          $aRetAr[$aKeys[$x]]=clone $aVals[$x];
      } elseif (is_array($aVals[$x])) {
          $aRetAr[$aKeys[$x]]=array_copy ($aVals[$x]);
      } else {
          $aRetAr[$aKeys[$x]]=$aVals[$x];
      }
    }
    return $aRetAr;
  }
  function send_mail($mto, $subj, $msg){

    trace("send_mail('$mto', '$subj', '$msg')");

    $item = new Message();
    $item->message_type_cd = Message::MESSAGE_TYPE_EMAIL;
    $item->mto = $mto;
    $item->subj = $subj;
    $item->msg = $msg;
    $item->Save();

    // now push to queue for processing
    $id = msg_get_queue(MQ_ID);
    msg_send($id, MQ_TYPE, $item);
  }

  function send_sms($mto, $msg, $src = PLIVO_SRC_NO){

    trace("send_sms('$mto', '$msg', '$src')");

    //if(ENVIRONMENT == 'lcl') return;

    if($mto){
      if($msg){
        if($src){

          $mto_valid = false;
          if(contains($mto, '<')){
            $mtos = array();
            $values = explode('<', $mto);
            foreach($values as $value){
              if(preg_match('/^\d{10}$/', $value)){
                $mtos[] = sprintf("1%s", $value);
              }else if(preg_match('/^\+1\d{10}$/', $value)){
                $mtos[] = $value;
              }else if(preg_match('/^1\d{10}$/', $value)){
                $mtos[] = $value;
              }
            }
            if(count($mtos) > 0){
              $mto = implode('<', $mtos);
              $mto_valid = true;
            }
          }else{
            if(preg_match('/^\d{10}$/', $mto)){
              $mto = sprintf("1%s", $mto);
              $mto_valid = true;
            }else if(preg_match('/^\+1\d{10}$/', $mto)){
              $mto_valid = true;
            }else if(preg_match('/^1\d{10}$/', $mto)){
              $mto_valid = true;
            }
          }

          $src_valid = false;
          if(preg_match('/^\d{10}$/', $src)){
            $src = sprintf("1%s", $src);
            $src_valid = true;
          }else if(preg_match('/^\+1\d{10}$/', $src)){
            $src_valid = true;
          }else if(preg_match('/^1\d{10}$/', $src)){
            $src_valid = true;
          }

          if($mto_valid){
            if($src_valid){

              $item = new Message();
              $item->message_type_cd = Message::MESSAGE_TYPE_SMS;
              $item->src = $src;
              $item->mto = $mto;
              $item->msg = $msg;
              $item->Save();

              // now push to queue for processing
              $id = msg_get_queue(MQ_ID);
              msg_send($id, MQ_TYPE, $item);

              trace("send_sms:$src:$mto:$msg" . PHP_EOL);

            }else{
              trace("src $src is invalid");
            }
          }else{
            trace("mto $mto is invalid");
          }
        }else{
          trace('send_sms:src is null');
        }
      }else{
        trace('send_sms:msg is null');
      }
    }else{
      trace('send_sms:mto is null');
    }
  }
  function redirect($url){
    header("location:" . $_SERVER['SERVER_URL'] . $url);
  }
  function var_die($o){
    var_dump($o);
    die;
  }
  function compress_page($html){
    if(SINGLE_LINE_OUTPUT){
      $search = array('/\r\n|\n/','/\s+/');
      $replace = array('',' ');
      $html = preg_replace($search, $replace, $html);
    }
    return $html;
  }
  function is_auth(){
    $retval = false;
    $user_id = $_SESSION['user_id'];
    if($user_id) $retval = true;
    return $retval;
  }
  function is_admin(){
    $retval = false;
    $user_id = $_SESSION['user_id'];
    if($user_id){
      $username = $_SESSION['username'];
      if($username){
        $retval = Roles::IsUserInRole($username, 'Administrator');
      }
    }
    return $retval;
  }
  function is_ben(){
    $retval = false;
    $user_id = $_SESSION['user_id'];
    if($user_id){
      $username = $_SESSION['username'];
      if($username){
        $retval = ($username == 'ben' || $username == 'ben@qeala.com');
      }
    }
    return $retval;
  }
  function date_time_six($s){
    $result = null;
    if($s){
      if (preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}\.\d{6}$/', $s)){
        $result = $s;
      }else if (preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}\.\d{5}$/', $s)){
        $result = sprintf("%s0", $s);
      }else if (preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}\.\d{4}$/', $s)){
        $result = sprintf("%s00", $s);
      }else if (preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}\.\d{3}$/', $s)){
        $result = sprintf("%s000", $s);
      }else if (preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}\.\d{2}$/', $s)){
        $result = sprintf("%s0000", $s);
      }else if (preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}\.\d{1}$/', $s)){
        $result = sprintf("%s00000", $s);
      }else if (preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}$/', $s)){
        $result = sprintf("%s.000000", $s);
      }else if (preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}$/', $s)){
        $result = sprintf("%s:00.000000", $s);
      }else if (preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}$/', $s)){
        $result = sprintf("%s.000000", $s);
      }else if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $s)){
        $result = sprintf("%s 00:00.000000", $s);
      }else{
        $msg = "invalid date_time_six $s";
        trace($msg);
        throw new Exception($msg);
      }
    }
    return $result;
  }
