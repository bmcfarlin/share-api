<?php include $layout->header; ?>
<div class="row">
  <div class="col-md-12">

<?php

  if(empty($email) && !empty($_SESSION['email'])){
    $email = $_SESSION['email'];
  }

  if(empty($username)){
    if(!empty($email)){
      $values = explode('@', $email);
      if(count($values) == 2) $username = $values[0];
    }
  }

  $facebook_id = $_SESSION['facebook_id'];

  $html = null;

  $post_id = md5(date('Hms').session_id());
  $cmd = CMD_SUBMIT;
  $uri = $routeinfo->uri;

$shtml = <<<BLOCK
<h1>$h1</h1>
<div class="error">$error</div>
<form id="signup" action="$uri" method="post" role="form">
<input type="hidden" name="post_id" value="$post_id" />
<input type="hidden" name="role_name" value="$role_name" />
<input type="hidden" name="facebook_id" value="$facebook_id" />
<input type="text" style="display:none">
<input type="password" style="display:none">
<div class="form-group">
<label>Username</label>
<input type="text" id="username" name="username" class="form-control" tabindex="2" maxlength="100" value="$username" autocomplete="off" />
</div>
<div class="form-group">
<label>Email Address</label>
<input type="text" id="email" name="email" class="form-control" tabindex="3" maxlength="100" value="$email" autocomplete="off"/>
</div>
<div class="form-group">
<label>Password</label>
<input type="password" id="password" name="password" class="form-control" tabindex="4" maxlength="48" autocomplete="off" />
</div>
<div class="form-group">
<label>Age</label>
<input type="text" id="age" name="age" class="form-control digits-only" tabindex="5" maxlength="2" autocomplete="off" />
</div>
<div class="form-group">
<label>Gender</label>
<select id="gender" name="gender" class="form-control" tabindex="6" autocomplete="off">
<option value="">Select...</option>
<option value="01">Male</option>
<option value="02">Female</option>
</select>
</div>
<div class="form-group">
Existing User? <a href="/user/signin">Sign In.</a><br/>
</div>
<div class="command"><input type="submit" id="cmd" name="cmd" class="button submit btn btn-lg btn-success" value="$cmd"  /></div>
</form>
</div>
BLOCK;

  $html .= $shtml;
  print $html;
?>

  </div>
</div>
<?php include $layout->footer; ?>


