<?php	include $layout->header; ?>
<div class="row">
<div class="col-md-12">

<?php

  $html = null;
  $html .= sprintf("<h1>%s</h1>", $vuser->username);
  $html .= "<div class=\"profile-container\">";

  $photo = 'no-photo';
  if(!empty($vprof) && !empty($vprof->photo)) $photo = $vprof->photo;
  $html .= sprintf("<div><img class=\"photo\" src=\"/img/%s.png\" /></div>", $photo);

  foreach($vprof as $key => $value){
    switch($key){
      case 'user_id':
      case 'first_name':
      case 'last_name':
      case 'facebook_id':
      break;
      default:
      $html .= sprintf("<b>%s:</b> %s<br/>", label($key), $value);
      break;
    }
  }
  $html .= sprintf("<b>Member since:</b> %s<br/>", to_date_time($vuser->creation_dtm));

  $html .= "<br/><br/>";
  $html .= "<h1>Quizes</h1>";

  $surveys = Survey::GetByUser($vuser->user_id);
  if(count($surveys) > 0){

    foreach($surveys as $survey){
      $html .= sprintf("<div><a href=\"/quiz/view/%d\">%s</a></div>", $survey->survey_id, $survey->name);
    }
  }else{
    $html .= sprintf("<div>%s hasn't created a Quiz yet.</div>", $vuser->username);
  }


  $html .= "</div>";


  print $html;

?>

  </div>
</div>
<?php	include $layout->footer; ?>
