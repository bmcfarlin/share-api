<?php include $layout->header; ?>
<section>
  <div class="container">
    <div class="row">
      <div class="col-12">

<?php
  $s= $_SERVER['PHP_SELF'];
  $filename = substr($s,strrpos($s,'/')+1);
  $app_path = substr($s,0,strrpos($s,'/')+1);

  $html = "<h1>Environment</h1>";

  $html .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"command\" style=\"width:100%; table-layout:fixed;\">";
  $html .= "<col width=\"30%\" />";
  $html .= "<col width=\"70%\" />";
  $html .= sprintf("<tr><td>app_path</td><td style=\"word-wrap:break-word\">%s</td></tr>", $app_path);
  $values = array('error_reporting', 'display_errors', 'display_startup_errors', 'log_errors', 'error_log');
  foreach($values as $value){
    $html .= sprintf("<tr><td>%s</td><td style=\"word-wrap:break-word\">%s</td></tr>", $value, ini_get($value));
  }

  $html .= "</table>";
  $html .= "<hr/>";

  $items = array($_SERVER);
  foreach($items as $item){
    $html .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"command\" style=\"width:100%; table-layout:fixed;\">";
    $html .= "<col width=\"30%\" />";
    $html .= "<col width=\"70%\" />";
    foreach($item as $key=>$value){
      $html .= sprintf("<tr><td>%s</td><td style=\"word-wrap:break-word\">%s</td></tr>", $key, $value);
    }
    $html .= "</table>";
    $html .= "<hr />";
  }


  echo $html;
?>

      </div>
    </div>
  </div>
</section>
<?php include $layout->footer; ?>

