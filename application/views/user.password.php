<?php  include $layout->header; ?>
<div class="row">
  <div class="col-md-12">

    <form id="password" action="<?php echo $routeinfo->uri ?>" method="post">
      <input type="hidden" name="post_id" value="<?php echo md5(date('Hms').session_id()); ?>" />
      <h1>Forgot Password</h1>
      <div class="error"><?php echo $error ?></div>
      <label>Email Address</label>
      <input type="text" id="email" name="email" tabindex="1" maxlength="100" />
      <div class="command"><input type="submit" id="cmd" name="cmd" value="<?php echo CMD_SUBMIT ?>" class="button submit" /></div>
    </form>

  </div>
</div>
<?php  include $layout->footer; ?>
