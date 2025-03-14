<!doctype html>
<html lang="en">
<head>
<?php

  $html = null;

  // default is public
  $locale = $routeinfo->locale;

  $url = server('REQUEST_URI');
  $site_url = SITE_URL;
  $cdn_url = CDN_URL;
  $css_version = CSS_VERSION;


  $locale_url = preg_replace("/^\/e[ns]\/?/", '/', $url);
  if($locale_url == "/") $locale_url = null;

  $img = sprintf("%s/img/og-image.png", $cdn_url);

  $site_title = Cms::GetByName('site.title');
  if(empty($site_title)) {
    $site_title = APP_NAME;
  }

  if(empty($page_title)){
    $page_title = $site_title;
  }else{
    $page_title = sprintf("%s | %s", $site_title, $page_title);
  }

  if(empty($page_description)){
    $page_description = Cms::GetByName('site.description');
  }

  if(empty($page_img)){
    $page_img = $img;
  }

  if(empty($share_title)){
    $share_title = $page_title;
  }

  if(empty($share_description)){
    $share_description = $page_description;
  }

  if(empty($share_img)){
    $share_img = $page_img;
  }

  if(empty($share_url)){
    $share_url = sprintf("%s%s", $site_url, $url);
  }

$shtml = <<<BLOCK
  <meta charset="utf-8">
  <title>$page_title</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="$page_description">
  <meta name="author" content="Ben McFarlin">
  <link href="https://fonts.googleapis.com/css?family=Oswald:300|Playfair+Display|Open+Sans:300" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link href="$cdn_url/css/common.css?v=$css_version" rel="stylesheet">
BLOCK;
  print $shtml;
?>
<?php

    $html = null;

    if(isset($styles)){
      foreach($styles as $style){
        if(starts_with($style, '/css')) $style = sprintf("%s?v=%s", $style, CSS_VERSION);
        $html .= sprintf("    <link href=\"%s\" rel=\"stylesheet\"/>\n", $style);
      }
    }

    print $html;
?>
</head>
<body>
  <header>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <nav>
            <?php include('nav.php'); ?>
          </nav>
        </div>
      </div>
    </div>
  </header>
  <section>
