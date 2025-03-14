<?php
  $html = null;
  $html .= "<ul>";
  if(is_auth()){
    $html .= sprintf("<li><a href=\"/%s/user/home\"%s>Home</a></li>", $locale, ($routeinfo->action == "index")?" class=\"home selected\"":" class=\"home\"");
    $html .= sprintf("<li><a href=\"%s/%s/user/profile\"%s>Profile</a></li>", $secure_url, $locale, ($routeinfo->action == "profile")?" class=\"selected\"":"");
    if(is_admin()) $html .= sprintf("<li><a href=\"%s/%s/admin\" target=\"_admin\"%s>Admin</a></li>", $secure_url, $locale, ($routeinfo->action == "admin")?" class=\"selected\"":"");
    $html .= sprintf("<li><a href=\"%s/%s/user/signout\"%s>Sign Out</a></li>", $site_url, $locale, ($routeinfo->action == "signout")?" class=\"selected\"":"");
  }else{
    $html .= sprintf("<li><a href=\"%s/%s/user/signin\"%s>Sign In</a></li>", $site_url, $locale, ($routeinfo->action == "signin")?" class=\"selected\"":"");
  }
  $html .= "</ul>";
  print $html;
