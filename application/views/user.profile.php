<?php include $layout->header; ?>
<section>
  <div class="container">
    <div class="row">
      <div class="col-md-6">

        <h1>Profile</h1>
        <?php

          $html = null;
          foreach($prof as $key => $value){
            if($key != 'user_id'){
              if($value) {
                $html .= sprintf("<b>%s:</b> %s<br/>", label($key), $value);
              }
            }
          }
          $html .= sprintf("<b>Email:</b> %s<br/>", $user->email);
          $html .= sprintf("<b>Created:</b> %s<br/>", to_date_time($user->creation_dtm));
          print $html;

        ?>

      </div>
      <div class="col-md-6">
      </div>
    </div>
  </div>
</section>
<?php include $layout->footer; ?>

