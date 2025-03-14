<?php include $layout->header; ?>
<section>
  <div class="container">
    <div class="row justify-content-center align-items-center">
      <div class="col-10 col-sm-8 col-md-7 col-lg-5 col-xl-4">

        <a href="/en/"><div class="logo"></div></a>

        <h1>Member Sign In</h1>
        <div class="error"><?php echo $error ?></div>

        <form id="signin" action="<?php echo $routeinfo->uri ?>" method="post">
        <input type="hidden" name="post_id" value="<?php echo md5(date('Hms').session_id()); ?>" />
          <div class="form-group">
            <label>Email Address</label>
            <input type="text" id="email" name="email" class="form-control" tabindex="1" maxlength="100" />
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" id="password" name="password" class="form-control" tabindex="2" maxlength="36" />
          </div>
          <div class="form-group">
            <input type="submit" id="cmd" name="cmd" value="<?php echo CMD_SIGNIN ?>" class="btn btn-lg" />
          </div>
        </form>

      </div>
    </div>
  </div>
</section>
<?php include $layout->footer; ?>


