<?php

  $site_url = SITE_URL;

  $site_title = Cms::GetByName('site.title');
  if(empty($site_title)) {
    $site_title = APP_NAME;
  }

  $page_description = Cms::GetByName('site.description');
  if(empty($page_description)) {
    $page_description = APP_NAME;
  }

  $user_id = $_SESSION['user_id'];
  $username = $_SESSION['username'];
  $rolename = 'Administrator';

  $prof = Profile::GetByUser($user_id);
  $photo = $prof->photo;

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo $site_title; ?> | Admin</title>
  <meta name="description" content="<?php echo $page_description; ?>">
  <meta name="author" content="Ben McFarlin">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Oswald:300|Playfair+Display|Open+Sans:300" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="<?php echo CDN_URL; ?>/assets/css/icons.min.css?v=1.0">
  <link rel="stylesheet" href="<?php echo CDN_URL; ?>/assets/css/app.min.css?v=1.0" id="light-style">
  <link rel="stylesheet" href="<?php echo CDN_URL; ?>/assets/css/app-dark.min.css?v=1.0" id="dark-style">
  <link rel="stylesheet" href="<?php echo CDN_URL; ?>/css/default-admin.css?v=1.0">
<?php

    $html = null;

    if(isset($styles)){
      foreach($styles as $style){
        if(starts_with($style, '/css')) {
          $html .= sprintf("    <link href=\"%s%s?v=%s\" rel=\"stylesheet\"/>\n", CDN_URL, $style, CSS_VERSION);
        }else{
          $html .= sprintf("    <link href=\"%s\" rel=\"stylesheet\"/>\n", $style);
        }
      }
    }

    print $html;
?>
</head>
<body class="loading">

  <form id="form" action="<?php echo $routeinfo->uri ?>" method="post" enctype="multipart/form-data">
  <input type="hidden" name="post_id" value="<?php echo md5(date('Hms').session_id()); ?>" />

        <!-- Begin wrapper -->
        <div class="wrapper">

          <?php include $layout->nav; ?>

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Topbar Start -->
                    <div class="navbar-custom">
                        <ul class="list-unstyled topbar-right-menu float-right mb-0">


                            <li class="dropdown notification-list">
                                <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                    aria-expanded="false">
                                    <span class="account-user-avatar">
                                        <img src="/assets/images/users/avatar-11.jpg" alt="user-image" class="rounded-circle">
                                    </span>
                                    <span>
                                        <span class="account-user-name"><?php echo $username; ?></span>
                                        <span class="account-position"><?php echo $rolename; ?></span>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown">

                                    <!-- item-->
                                    <a href="/en/user/signout" class="dropdown-item notify-item">
                                        <i class="mdi mdi-logout mr-1"></i>
                                        <span>Logout</span>
                                    </a>

                                </div>
                            </li>

                        </ul>
                        <button class="button-menu-mobile open-left disable-btn">
                            <i class="mdi mdi-menu"></i>
                        </button>

                    </div>
                    <!-- end Topbar -->
