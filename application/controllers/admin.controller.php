<?php

class AdminController extends BaseController
{
  public function __construct($registry)
  {
    parent::__construct($registry);
    $layout = new stdClass();
    $layout->header = 'application/views/layout/header-admin.php';
    $layout->footer = 'application/views/layout/footer-admin.php';
    $layout->nav = 'application/views/layout/nav-admin.php';
    $this->registry->template->SetLayout($layout);
  }
  public function index()
  {
    if(!is_admin()) $this->redirect("/en/user/signout");
    $this->redirect("/en/admin/user");
  }
  public function cms()
  {
    if(!is_admin()) $this->redirect('/en/user/signout');

    $this->registry->template->class_name = "Cms";
    $this->registry->template->item_id = "cms_id";

    $this->registry->template->table_fields = array('name', 'creation_dtm');
    $this->registry->template->view_fields = array('name', 'creation_dtm');
    $this->registry->template->edit_fields = array('name', 'en'=>'textarea', 'es'=>'textarea');

    $this->registry->template->scripts = array('/js/jquery.validate.js', '/js/jquery.alerts.js', '/js/admin.cms.js');
    $this->registry->template->show('admin.manage.php');
  }
  public function meta()
  {
    if(!is_admin()) $this->redirect('/en/user/signout');

    $this->registry->template->class_name = "Meta";
    $this->registry->template->item_id = "meta_id";

    $fields = array();
    $item = new Meta();

    foreach($item as $key => $value)
    {
      if($key == 'meta_id' || contains($key, 'creation_') || contains($key, 'last_update_') || contains($key, 'full_name')) continue;
      else if(contains($key, '_flag')) $fields[$key] = 'checkbox';
      else $fields[] = $key;
    }

    $this->registry->template->table_fields = array('controller', 'action', 'en_page_title');
    $this->registry->template->view_fields = $fields;
    $this->registry->template->edit_fields = $fields;


    $this->registry->template->scripts = array('/js/jquery.validate.js', '/js/jquery.alerts.js');
    $this->registry->template->show('admin.manage.php');
  }
  public function report($id)
  {
    if(!is_admin()) $this->redirect('/en/user/signout');
    if(preg_match('/^(100)$/', $id)){
      $this->registry->template->styles = array("/css/admin.report.$id.css");
      $this->registry->template->scripts = array("/js/jquery.query-object.js", "/js/admin.report.$id.js");
      $admin_flag = is_admin();
      $this->registry->template->datas = array("admin_flag" => $admin_flag);
      $this->registry->template->show("admin.report.$id.php");
    }else{
      $this->redirect("/en/error404");
    }
  }
  public function user()
  {
    if(!is_admin()) $this->redirect("/en/user/signout");
    $this->registry->template->scripts = array('/js/jquery.validate.js', '/js/jquery.alerts.js', '/js/admin.user.js');
    $this->registry->template->show('admin.user.php');
  }
}
