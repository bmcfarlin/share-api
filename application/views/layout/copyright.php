<?php

$html = null;

$site_title = Cms::GetByName('site.title');
if(empty($site_title)) {
  $site_title = APP_NAME;
}

$yyyy = date("Y", strtotime('now'));

$html .= sprintf("<div>Copyright © %s</div><div>%s</div>", $yyyy, $site_title);

print $html;