<?php

  global $post_back, $double_post_back;

  $page_no = 1;
  $resultcount  = 12;
  $key_types = array('text', 'select', 'textarea', 'checkbox', 'file', 'richtextbox');

  $cmd = request("cmd");
  if(empty($cmd)) $cmd = CMD_LIST;

  $cmd_arg = request("cmd_arg");

  if($cmd == CMD_EXPORT){
    $html = "\"Last Name\",\"First Name\",\"Email Address\",\"Creation DateTime\"\n";
    $rmethod = new ReflectionMethod($class_name, 'GetAll');
    $items = $rmethod->invoke(null);
    foreach($items as $item){
      $html .= sprintf("\"%s\",\"%s\",\"%s\",\"%s\"\n", $item->last_name, $item->first_name, $item->email, $item->creation_dtm);
    }
    header("Content-Type: text/plain");
    echo $html;
    die;
  }
  if($cmd == CMD_PURGE){
    $rmethod = new ReflectionMethod($class_name, 'PurgeAll');
    $items = $rmethod->invoke(null);
    $cmd = CMD_LIST;
  }
  if($cmd == CMD_DELETE){
    if(!$double_post_back) {
      $rmethod = new ReflectionMethod($class_name, 'GetById');
      $item = $rmethod->invoke(null, $cmd_arg);
      $item->Delete();

      if($class_name == 'Resource'){
        $full_destination = sprintf("%s\%s.%s", FLE_UNC, $item->resource_id, $item->ext);
        if(file_exists($full_destination)) unlink($full_destination);
      }
    }
    $cmd = CMD_LIST;
  }
  if($cmd == CMD_BACK){
    $cmd = CMD_LIST;
  }
  if($cmd == CMD_CANCEL){
    $cmd = CMD_LIST;
  }
  if($cmd == CMD_SAVE){
    if(!$double_post_back){
      $rclass = new ReflectionClass($class_name);
      if(empty($cmd_arg)){
        $item = $rclass->newInstance();
      }else{
        $rmethod = new ReflectionMethod($class_name, 'GetById');
        $item = $rmethod->invoke(null, $cmd_arg);
      }

      foreach($edit_fields as $key => $key_type){
        trace("$key:$key_type");
        if(array_search($key_type, $key_types) === false) $key = $key_type;
        if($key == "photo" || $key == "audio" || $key == "video") $key_type = "file";

        if(ends_with($key, 'audio') && $key_type == 'file'){

          if($item->$key){
            $reset_key = sprintf("%s_reset", $key);
            $reset = $_REQUEST[$reset_key];
            trace("$reset_key:$reset");
            if($reset){
              $full_destination = sprintf("%s/%s.mp3", FLE_UNC, $item->$key);
              if(file_exists($full_destination)) unlink($full_destination);
              $item->$key = null;
            }
          }

          if($_FILES  && !empty($_FILES[$key]["name"]) && !empty($_FILES[$key]["size"])){

            if(empty($item->$key))  $item->$key = Uuid::GetNew();

            trace($_FILES);

            $file_error = $_FILES[$key]["error"];
            $file_size = $_FILES[$key]["size"];
            $file_type = $_FILES[$key]["type"];
            $file_types = array("audio/mpeg"=>"mp3", "audio/mp3"=>"mp3");
            $file_ext = $file_types[$file_type];

            if($file_error){
              if($file_error != 4) $valsum .= "<div>Error uploading file.</div>";
            }else if(!array_key_exists($file_type, $file_types)){
              $valsum .= "<div>Invalid file type: $file_type. Only .mp3 allowed.</div>";
            }else if($file_size > MAX_FILE_UPLOAD_SIZE){
              $valsum .= sprintf("<div>File too large. Maximum file size is %s.</div>", MAX_FILE_UPLOAD_NAME);
            }else{
              $file_source = $_FILES[$key]["tmp_name"];
              if(property_exists($item, 'ext')) $item->ext = $file_ext;

              $full_destination = sprintf("%s/%s.%s", FLE_UNC, $item->$key, $file_ext);
              trace("full_destination:$full_destination");
              if(file_exists($full_destination)) unlink($full_destination);
              copy($file_source, $full_destination);
            }
          }
        }else if(ends_with($key, 'video') && $key_type == 'file'){

          if($item->$key){
            $reset_key = sprintf("%s_reset", $key);
            $reset = $_REQUEST[$reset_key];
            trace("$reset_key:$reset");
            if($reset){
              $full_destination = sprintf("%s/%s.mp4", FLE_UNC, $item->$key);
              if(file_exists($full_destination)) unlink($full_destination);
              $item->$key = null;
            }
          }

          if($_FILES  && !empty($_FILES[$key]["name"]) && !empty($_FILES[$key]["size"])){

            if(empty($item->$key))  $item->$key = Uuid::GetNew();

            trace($_FILES);

            $file_error = $_FILES[$key]["error"];
            $file_size = $_FILES[$key]["size"];
            $file_type = $_FILES[$key]["type"];
            $file_types = array("video/mp4"=>"mp4");
            $file_ext = $file_types[$file_type];

            if($file_error){
              if($file_error != 4) $valsum .= "<div>Error uploading file.</div>";
            }else if(!array_key_exists($file_type, $file_types)){
              $valsum .= "<div>Invalid file type: $file_type. Only .mp4 allowed.</div>";
            }else if($file_size > MAX_FILE_UPLOAD_SIZE){
              $valsum .= sprintf("<div>File too large. Maximum file size is %s.</div>", MAX_FILE_UPLOAD_NAME);
            }else{
              $file_source = $_FILES[$key]["tmp_name"];
              if(property_exists($item, 'ext')) $item->ext = $file_ext;

              $full_destination = sprintf("%s/%s.%s", FLE_UNC, $item->$key, $file_ext);
              trace("full_destination:$full_destination");
              if(file_exists($full_destination)) unlink($full_destination);
              copy($file_source, $full_destination);
            }
          }
        }else if( (ends_with($key, 'photo') || ends_with($key, 'image')) && $key_type == 'file'){

          if($item->$key){
            $reset_key = sprintf("%s_reset", $key);
            $reset = $_REQUEST[$reset_key];
            trace("$reset_key:$reset");
            if($reset){
              $full_destination = sprintf("%s/%s.png", PHOTO_UNC, $item->$key);
              if(file_exists($full_destination)) unlink($full_destination);
              $item->$key = null;
            }
          }

          if($_FILES  && !empty($_FILES[$key]["name"]) && !empty($_FILES[$key]["size"])){
            if(empty($item->$key))  $item->$key = Uuid::GetNew();

            $file_error = $_FILES[$key]["error"];
            $file_size = $_FILES[$key]["size"];
            $file_type = $_FILES[$key]["type"];
            $file_types = array("image/jpeg"=>"jpg", "image/gif"=>"gif", "image/x-png"=>"png", "image/pjpeg"=>"jpg", "image/png"=>"png");
            $file_ext = $file_types[$file_type];

            if($file_error){
              if($file_error != 4) $valsum .= "<div>Error uploading file.</div>";
            }else if(!array_key_exists($file_type, $file_types)){
              $valsum .= "<div>Invalid file type: $file_type. Only .jpg, .gif, .png allowed.</div>";
            }else if($file_size > MAX_FILE_UPLOAD_SIZE){
              $valsum .= sprintf("<div>File too large. Maximum file size is %s.</div>", MAX_FILE_UPLOAD_NAME);
            }else{
              // process the image
              $file_source = $_FILES[$key]["tmp_name"];
              $full_destination = sprintf("%s/%s.png", PHOTO_UNC, $item->$key);

              $image = new Imagick();
              $image->readImage($file_source);
              $width = $image->getImageWidth();
              $height = $image->getImageHeight();

              if($width > $height){
                $image->thumbnailImage(PHOTO_WIDTH_FULL, 0);
                $full_width = $image->getImageWidth();
                $full_height = $image->getImageHeight();
                trace("write1:$full_destination");
                $image->writeImage($full_destination);
              }else{
                $image->thumbnailImage(0, PHOTO_WIDTH_FULL);
                $full_width = $image->getImageWidth();
                $full_height = $image->getImageHeight();
                trace("write2:$full_destination");
                $image->writeImage($full_destination);
              }

              $image->clear();
              $image->destroy();
              trace("unlink:$file_source");
              if(file_exists($file_source)) unlink($file_source);

              $item->full_width = $full_width;
              $item->full_height = $full_height;
            }
          }
        }else{
          $item->$key = request($key);
        }
      }

      if(empty($valsum)) {
        $item->Save();
      }
    }

    $cmd = empty($valsum)? CMD_LIST : CMD_EDIT;
  }
  if($cmd == CMD_NEW){
    $rclass = new ReflectionClass($class_name);
    $item = $rclass->newInstance();
  }
  if($cmd == CMD_EDIT){
    $rmethod = new ReflectionMethod($class_name, 'GetById');
    $item = $rmethod->invoke(null, $cmd_arg);
  }
  if($cmd == CMD_VIEW){
    $rmethod = new ReflectionMethod($class_name, 'GetById');
    $item = $rmethod->invoke(null, $cmd_arg);
  }
  if($cmd == CMD_PAGE){
    $page_no = $cmd_arg;
    $items = $_SESSION["items"];
  }
  if($cmd == CMD_LIST){
    $rmethod = new ReflectionMethod($class_name, 'GetAll');
    $items = $rmethod->invoke(null);
  }

  $_SESSION["page_no"] = $page_no;
  $_SESSION["items"] = $items;

  include $layout->header;


        switch($cmd){
          case CMD_LIST:
          case CMD_PAGE:
            $pagedResults = new Paginated($items, $resultcount, $page_no);
            $pagedResults->setLayout(new MbLayout());
            $page_html = $pagedResults->fetchPagedNavigationHtml();

            $start_rec = (($page_no-1) * $resultcount) + 1;
            $end_rec = $start_rec + $resultcount - 1;
            $item_count = count($items);
            if($end_rec > $item_count) $end_rec = $item_count;

 // class="table table-centered mb-0"

            $html = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" data-table-id=\"table-262\" class=\"table table-centered mb-0\">";
            eval("\$ctl_lnk_value = \"$ctl_lnk\";");

            //$link_button_value = LinkButton::GetHtml('New', CMD_NEW, null, null, 'new');
            $link_button_value = "<a href=\"javascript:__postback('New','','','');\" class=\"action-icon\"><i class=\"mdi mdi-new-box\"></i></a>";

            if(!is_admin()){
              $ctl_lnk_value = null;
              $link_button_value = null;
            }

            $html .= sprintf("<tr><td><h2>%s</h2></td><td style=\"text-align:right;\">%s%s</td></tr>", label($class_name), $ctl_lnk_value, $link_button_value);
            $html .= "</table>";
            if($item_count == 0){
              $html .= "<div>No records found.</div>";
            }else{
              $html .= "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\" data-table-id=\"table-278\" class=\"table table-striped table-centered mb-0\">";
              $html .= "<thead>";
              $html .= "<tr class=\"item-header\">";
              foreach($table_fields as $key) $html .= sprintf("<th>%s</th>", label($key));
              $html .= "<th></th>";
              $html .= "</tr>";
              $html .= "</thead>";

              $n = 0;
              while($item = $pagedResults->fetchPagedRow()){
                $class = ($n%2 == 0)? "item":"alt-item";
                $html .= sprintf("<tr class=\"%s\">", $class);
                foreach($table_fields as $key){
                  $value = $item->$key;
                  if(ends_with($key, '_cd') || ends_with($key, '_id')){
                    $values = ${'g_'.$key.'_values'};
                    if($values) $value = $values[$value];
                  }else if(ends_with($key, '_flag')){
                    $value = yes_no($value);
                  }else if(ends_with($key, '_dtm') || ends_with($key, '_date')){
                    $value = to_date($value);
                  }
                  $html .= sprintf("<td>%s</td>", $value);
                }
                eval("\$cmd_lnk_value = \"$cmd_lnk\";");

                // $delete_lnk_value = LinkButton::GetHtml('Delete', CMD_DELETE, $item->$item_id, '', '', true);
                // $edit_lnk_value = LinkButton::GetHtml('Edit', CMD_EDIT, $item->$item_id);
                // $view_lnk_value = LinkButton::GetHtml('View', CMD_VIEW, $item->$item_id);

                $edit_lnk_value = sprintf("<a href=\"javascript:__postback('Edit','%s','','');\" class=\"action-icon\"> <i class=\"mdi mdi-pencil\"></i></a>", $item->$item_id);
                $delete_lnk_value = sprintf("<a href=\"javascript:__postback('Delete','%s','','');\" class=\"action-icon\" onclick=\"return jConfirmDelete(this)\"> <i class=\"mdi mdi-delete\"></i></a>", $item->$item_id);
                $view_lnk_value = sprintf("<a href=\"javascript:__postback('View','%s','','');\" class=\"action-icon\"> <i class=\"mdi mdi-eye\"></i></a>", $item->$item_id);

                $view_lnk_value = null;

                if(!is_admin()){
                  $cmd_lnk_value = $view_lnk_value;
                  $delete_lnk_value = null;
                  $edit_lnk_value = null;
                }

                $html .= sprintf("<td style=\"text-align:right;white-space:nowrap;\">%s%s &nbsp; %s &nbsp; %s</td>", $cmd_lnk_value, $edit_lnk_value, $delete_lnk_value, $view_lnk_value);
                $html .= "</tr>";
                $n++;
              }
              $html .= "</table>";
              $html .= $page_html;
            }
            echo $html;
          break;
          case CMD_VIEW:
            $html = sprintf("<h2>View %s</h2>", label($class_name));
            $html .= "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\" data-table-id=\"table-322\" class=\"table table-centered mb-0\">";
            $n = 0;
            foreach($view_fields as $key){
              $class = ($n%2 == 0)? "item":"alt-item";
              $value = $item->$key;
              if(ends_with($key, '_cd') || ends_with($key, '_id')){
                $values = ${'g_'.$key.'_values'};
                if($values) $value = $values[$value];
              }else if(ends_with($key, '_dtm') || ends_with($key, '_date')){
                $value = to_display_date($value);
              }else if(ends_with($key, '_flag')){
                $value = yes_no($value);
              }else if(ends_with($key, 'height')){
                $value = to_height($value);
              }else if(ends_with($key, 'weight')){
                $value = to_weight($value);
              }else if(contains($key, 'phone')){
                $value = to_phone($value);
              }else if(contains($key, 'balance')){
                $value = to_money($value);
              }else if(contains($key, 'photo')){
                $value = sprintf("<a href=\"/ph/%s.png\" target=\"_blank\">View</a>", $value);
              }
              $html .= sprintf("<tr class=\"%s\">", $class);
              $html .= sprintf("<td><span class=\"label\">%s</span></td><td>%s</td>", label($key), $value);
              $html .= "</tr>";
              $n++;
            }
            $html .= "</table>";


            $html .= "<div style=\"text-align:right;\" class=\"command-float\">";
            $html .= "<div class=\"xpan-canvas\"></div>";
            $html .= "<div class=\"xpan-content\">";
            $html .= "<a href=\"javascript:__postback('List','','','');\" class=\"action-icon\"><i class=\"mdi mdi-keyboard-backspace \"></i></a>";
            $html .= "</div>";
            $html .= "</div>";
            $html .= "</div>";

            echo $html;
          break;
          case CMD_EDIT:
          case CMD_NEW:
            $html = sprintf("<h2>%s %s</h2>", $cmd, label($class_name));

            $n = 0;

            foreach($edit_fields as $key => $key_type){
              if(array_search($key_type, $key_types) === false){
                $key = $key_type;
                $key_type = "text";
              }
              if(ends_with($key, '_cd') || ends_with($key, '_id')) $key_type = "select";
              if($key == 'photo') $key_type = 'file';

              $class = ($n%2 == 0)? "item":"alt-item";
              $value = $item->$key;
              if($key_type == "select"){
                $values = ${'g_'.$key.'_values'};

                // this line of code just to default the drop down to 90 days for Kevin
                if($key == "expiration_type_id"  && empty($value)) $value = 3;

                $element = Select::GetHtml($key, $values, $value, '{"class":"form-control"}');
                $html .= "<div class=\"form-group\">";
                $html .= sprintf("<label>%s</label>", label($key));
                $html .= $element;
                $html .= "</div>";
              }else if($key_type == "file"){

                $element = FileUpload::GetHtml($key, '{"class":"form-control-file"}', '{"margin-bottom":"10px"}');
                $element .= "<div class=\"custom-control custom-checkbox\">";
                $element .= sprintf("<input type=\"checkbox\" id=\"%s_reset\" name=\"%s_reset\" class=\"reset custom-control-input\" />", $key, $key);
                $element .= sprintf("<label class=\"custom-control-label\" for=\"%s_reset\">Delete</label>", $key);
                $element .= "</div>";

                $html .= "<div class=\"form-group\">";
                $html .= sprintf("<label>%s</label>", label($key));
                $html .= $element;
                $html .= "</div>";


              }else if($key_type == "checkbox"){
                $checked = $value? "checked" : null;
                $html .= "<div class=\"form-group\">";
                $html .= "<div class=\"custom-control custom-checkbox\">";
                $html .= sprintf("<input type=\"checkbox\" id=\"%s\" name=\"%s\" class=\"custom-control-input\" %s />", $key, $key, $checked);
                $html .= sprintf("<label class=\"custom-control-label\" for=\"%s\">%s</label>", $key, label($key));
                $html .= "</div>";
                $html .= "</div>";
              }else{
                //if(ends_with($key, '_dtm') || ends_with($key, '_date')) $value = to_date($value);

                if($key == "html" && ($item->$item_id == 'cb6407e1-fe67-102c-95b1-f61275160e04' || $item->$item_id == 'd4366b70-fe67-102c-95b1-f61275160e04')){
                  $key_type = "textarea";
                }

                if($key_type == "richtextbox"){
                  $element = sprintf("<textarea id=\"%s\" name=\"%s\" class=\"form-control\" rows=\"5\">%s</textarea>", $key, $key, $value);
                }else if($key_type == "textarea"){
                  $element = sprintf("<textarea id=\"%s\" name=\"%s\" class=\"form-control\" rows=\"5\">%s</textarea>", $key, $key, $value);
                }else{
                  if(ends_with($key, '_dtm') || ends_with($key, '_date')) $value = to_date($value);
                  $element = sprintf("<input type=\"text\" id=\"%s\" name=\"%s\" value=\"%s\" class=\"form-control\" />", $key, $key, $value);

                  if($key == 'lat'){
                    $element = sprintf("%s <a class=\"latlon\" href=\"javascript:void(0)\">Generate</a>", $element);
                  }
                }
                $html .= "<div class=\"form-group\">";
                $html .= sprintf("<label>%s</label>", label($key));
                $html .= $element;
                $html .= "</div>";
              }
              $n++;
            }

            $html .= "<div id=\"valsum\" class=\"error\">$valsum</div>";

            $html .= "<div style=\"text-align:right;\" class=\"command-float\">";
            $html .= "<div class=\"xpan-canvas\"></div>";
            $html .= "<div class=\"xpan-content\">";
            $html .= "<a href=\"javascript:__postback('Cancel','','','');\" class=\"action-icon\"><i class=\"mdi mdi-keyboard-backspace \"></i></a>";
            $html .= "  &nbsp; ";
            $html .= sprintf("<a href=\"javascript:__postback('Save','%s','','');\" class=\"action-icon\"><i class=\"mdi mdi-content-save\"></i></a>", $item->$item_id);
            $html .= "</div>";
            $html .= "</div>";
            $html .= "</div>";
            echo $html;
          break;
        }


      include $layout->footer;

      ?>



