<?php include $layout->header; ?>
<section>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
<?php
  $html = null;
  $names = array(100 => 'User', 101 => 'Transaction', 102 => 'Scheduling', 103 => 'Payroll');
  $name = $names[$id];
  if(empty($name)) $name = 'Report';
  $html = sprintf("<h1>$name</h1>", $name);
  print $html;
?>

      </div>
    </div>
  </div>
</section>
<?php include $layout->footer; ?>