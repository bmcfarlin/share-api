<?php
class Membership
{
  static function CreateUser($username, $password, $email)
  {
    $user = new User();
    $user->application_id = APP_ID;
    $user->username = $username;
    $user->password = OpenSSL::Encrypt($password);
    $user->email = $email;
    $user->active_flag = 1;
    $user->Save();
    return $user;
  }
  static function DeleteUser($username)
  {
    $user = User::GetByUserName($username);
    if($user) $user->Delete();
  }
  static function GetAllUsers()
  {
    return User::GetAll();
  }
  static function GetUser($user_id)
  {
    return User::GetById($user_id);
  }
  static function GetUserByUserName($username)
  {
    return User::GetByUserName($username);
  }
  static function GetUserNameByEmail($email)
  {
    $value = null;
    $user = User::GetByEmail($email);
    if($user) $value = $user->username;
    return $value;
  }
  static function ValidateUser($username, $password)
  {
    $value = false;
    $user = User::Validate($username, $password);
    if($user) $value = true;
    return $value;
  }
  static function UpdateUser($user)
  {
    $user->Update();
  }
  static function CreateProfile($user)
  {
    $profile = new Profile();
    $profile->user_id = $user->user_id;
    $profile->email = $user->email;
    $profile->Save();
    return $profile;
  }
  static function GetProfile($user)
  {
    return Profile::GetByUser($user->user_id);
  }

}
?>