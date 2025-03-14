<?php

  error_reporting(E_ALL & ~E_NOTICE);

  $lines = array();

  $file_path = __DIR__ . '/../settings.php';
  $file = fopen($file_path, "r") or die("Unable to open file!");
  while(!feof($file)) {
    $line = fgets($file);
    if (preg_match('/CSS_VERSION\'\, \'/', $line)){
      $parts = explode('\'', $line);
      if(count($parts) == 5){
        $version = $parts[3];
        $version = intval($version) + 1;
        $line = str_replace($parts[3], $version, $line);
      }
    }
    $lines[] = $line;
  }
  fclose($file);

  $content = implode("", $lines);

  $file_path = __DIR__ . '/../settings.php';
  $file = fopen($file_path, "w") or die("Unable to open file!");
  fwrite($file, $content);
  fclose($file);

?>