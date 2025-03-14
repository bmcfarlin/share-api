<?php
class ContentController extends BaseController
{
  public function __construct($registry)
  {
    parent::__construct($registry);
  }
  public function index()
  {
  }
  public function offline()
  {
    $this->registry->template->show('content.offline.php');
  }
  public function info()
  {
    $this->registry->template->show('content.info.php');
  }
  public function env()
  {
    $layout = new stdClass();
    $layout->header = 'application/views/layout/header-min.php';
    $layout->footer = 'application/views/layout/footer-min.php';
    $this->registry->template->SetLayout($layout);
    $this->registry->template->show('content.env.php');
  }
  public function test()
  {
    if(!is_auth()) $this->redirect('/user/signin');
    $this->registry->template->styles = array('/css/content.test.css');
    $this->registry->template->scripts = array('/js/jquery.cookie.js', '/js/content.test.js');
    $this->registry->template->show('content.test.php');
  }
  public function about()
  {
    $this->registry->template->styles = array('/css/content.about.css');
    $this->registry->template->scripts = array('/js/content.about.js');
    $this->registry->template->show('content.about.php');
  }
  public function contact()
  {
    $this->registry->template->styles = array('/css/content.contact.css');
    $this->registry->template->scripts = array('/js/content.contact.js');
    $this->registry->template->show('content.contact.php');
  }
  public function terms()
  {
    $this->registry->template->styles = array('/css/content.terms.css');
    $this->registry->template->scripts = array('/js/content.terms.js');
    $this->registry->template->show('content.terms.php');
  }
  public function privacy()
  {
    $this->registry->template->styles = array('/css/content.privacy.css');
    $this->registry->template->scripts = array('/js/content.privacy.js');
    $this->registry->template->show('content.privacy.php');
  }
  public function demo()
  {
    $this->registry->template->styles = array('/css/content.demo.css');
    $this->registry->template->scripts = array('/js/content.demo.js');
    $this->registry->template->show('content.demo.php');
  }
}