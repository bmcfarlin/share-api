<?php

class UserController extends BaseController
{
  public function index()
  {
    $this->registry->template->show('error404.list.php');
  }
  public function email()
  {
    $this->registry->template->show('user.email.php');
  }
  public function home()
  {
    $this->registry->template->styles = array('/css/user.home.css');
    $this->registry->template->scripts = array('/js/user.home.js');
    $this->registry->template->show('user.home.php');
  }
  private function load_data()
  {
    $states = array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');
    $months = array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
    $years = array('2012'=>'2012','2013'=>'2013','2014'=>'2014','2015'=>'2015','2016'=>'2016','2017'=>'2017','2018'=>'2018','2019'=>'2019','2020'=>'2020','2021'=>'2021');

    $this->registry->template->states = $states;
    $this->registry->template->months = $months;
    $this->registry->template->years = $years;
  }
  public function password()
  {
    global $post_back, $double_post_back;
    $cmd = $_POST['cmd'];

    if($post_back && !$double_post_back)
    {
      $email = $_POST['email'];
      $user = User::GetByEmail($email);
      if($user)
      {
        $user->SendPassword();
        $this->registry->template->show('user.password.success.php');
      }else{
        $this->registry->template->error = 'The email address you supplied does not exist in our system.';
        $this->registry->template->scripts = array('/js/jquery.validate.js','/js/user.password.js');
        $this->registry->template->show('user.password.php');
      }
    }else{
      $this->registry->template->scripts = array('/js/jquery.validate.js','/js/user.password.js');
      $this->registry->template->show('user.password.php');
    }
  }
  public function profile()
  {

    global $post_back, $double_post_back;
    $cmd = $_POST['cmd'];

    $this->load_data();

    $user_id = $_SESSION['user_id'];
    if(!isset($user_id)) $this->redirect('/user/signin');
    $user = User::GetById($user_id);
    $prof = Profile::GetByUser($user_id);
    $this->registry->template->user = $user;
    $this->registry->template->prof = $prof;

    if($post_back && !$double_post_back)
    {
      if($prof)
      {
        foreach($prof as $key=>$value)
        {
          $value = $_POST[$key];
          if(starts_with($key, 'work_') || starts_with($key, 'opt_')) $prof->$key = 0;
          if($value) $prof->$key = $value;
        }
        $prof->Save();
        $this->registry->template->status = 'Profile Saved';
      }
    }
    $this->registry->template->scripts = array('/js/jquery.validate.js','/js/user.profile.js');
    $this->registry->template->show('user.profile.php');
  }
  public function reset($id)
  {
    global $post_back, $double_post_back;
    $cmd = $_POST['cmd'];

    $user_id = OpenSSL::Decrypt(base64_decode($id));
    $user = User::GetById($user_id);

    if($post_back && !$double_post_back)
    {
      $email = $_POST['email'];
      if($user)
      {
        $password = $_POST['password'];
        $user->password = OpenSSL::Encrypt($password);
        $user->Save();
        $this->registry->template->show('user.reset.success.php');
      }else{
        $this->registry->template->show('user.reset.invalid.php');
      }
    }else{
      if($user)
      {
        $this->registry->template->id = $id;
        $this->registry->template->show('user.reset.php');
      }else{
        $this->registry->template->show('user.reset.invalid.php');
      }
    }
  }
  public function signin()
  {
    $layout = new stdClass();
    $layout->header = 'application/views/layout/header-min.php';
    $layout->footer = 'application/views/layout/footer-min.php';
    $this->registry->template->SetLayout($layout);

    global $post_back, $double_post_back;
    $cmd = $_POST['cmd'];
    $view = 'user.signin.php';

    if($post_back && !$double_post_back)
    {
      $email = $_POST['email'];
      $password = $_POST['password'];
      $username = Membership::GetUserNameByEmail($email);
      $b = Membership::ValidateUser($username, $password);
      if($b)
      {
        $user = Membership::GetUserByUserName($username);
        if($user->approved_flag)
        {

          $user_id = $user->user_id;

          $_SESSION['user_id'] = $user_id;
          $_SESSION['username'] = $username;

          $claims = ['user_id' => $user_id, 'username' => $username, 'tme' => time()];
          setcookie(JWT_COOKIE_NAME, Jwt::GetNew($claims), time() + JWT_COOKIE_DURATION, '/', null, JWT_HTTP_ONLY, JWT_HTTP_ONLY);

          $this->redirect('/en/content/test');

          // if(is_admin())
          // {
          //   $this->redirect('/en/admin/user');
          // }else{
          //   $this->redirect('/en/user/home');
          // }

        }else{
          $this->registry->template->error = 'Account is not active.';
        }
      }else{
        $this->registry->template->error = 'Invalid Credentials';
      }
    }
    $this->registry->template->styles = array('/css/user.signin.css');
    $this->registry->template->scripts = array('/js/jquery.validate.js','/js/user.signin.js');
    $this->registry->template->show($view);
  }
  public function signout()
  {
    $_SESSION = array();

    if (isset($_COOKIE[session_name()]))
    {
      setcookie(session_name(), '', time() - 3600, '/');
    }

    if (isset($_COOKIE[JWT_COOKIE_NAME]))
    {
      setcookie(JWT_COOKIE_NAME, '', time() - 3600, '/');
    }

    session_destroy();
    $this->redirect('/');
  }
}

