<?php  include $layout->header; ?>
<div class="row">
  <div class="col-md-12">

    <form id="password" action="<?php echo sprintf("%s/%s", $routeinfo->uri, $id); ?>" method="post">
      <input type="hidden" name="post_id" value="<?php echo md5(date('Hms').session_id()); ?>" />
      <h1>Password Reset</h1>
      <div class="error"><?php echo $error ?></div>
      <label>New Password</label>
      <input type="password" id="password" name="password" tabindex="1" maxlength="41" />
      <label>Confirm New Password</label>
      <input type="password" id="confirm_password" name="cofirm_password" tabindex="1" maxlength="41" />
      <div class="command"><input type="submit" id="cmd" name="cmd" value="<?php echo CMD_SUBMIT ?>" class="button submit" /></div>
    </form>

  </div>
</div>
<?php  include $layout->footer; ?>
