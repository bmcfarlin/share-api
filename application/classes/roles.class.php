<?php
class Roles
{
  static function AddUsersToRole($usernames, $rolename){
    foreach($usernames as $username){
      Roles::AddUserToRole($username, $rolename);
    }
  }
  static function AddUsersToRoles($usernames, $rolenames){
    foreach($usernames as $username){
      foreach($rolenames as $rolename){
        Roles::AddUserToRole($username, $rolename);
      }
    }
  }
  static function AddUserToRole($username, $rolename){
    $user = User::GetByUserName($username);
    if($user){
      $role = Role::GetByName($rolename);
      if($role){
        Role::AddUserToRole($user->user_id, $role->role_id);
      }
    }
  }
  static function AddUserToRoles($username, $rolenames){
    foreach($rolenames as $rolename) Roles::AddUserToRole($username, $rolename);
  }
  static function CreateRole($rolename){
    $role = new Role();
    $role->application_id = APP_ID;
    $role->role_name = $rolename;
    $role->Save();
  }
  static function DeleteRole($rolename){
    $role = Role::GetByName($rolename);
    if($role) $role->Delete();
  }
  static function GetAllRoles(){
    return Role::GetAll();
  }
  static function GetRolesForUser($username){
    return Role::GetByUser($username);
  }
  static function GetUsersInRole($rolename){
    return User::GetByRole($rolename);
  }
  static function IsUserInRole($username, $rolename){
    $value = false;
    $roles = Role::GetByUser($username);
    foreach($roles as $role){
      if($role->role_name == $rolename){
        $value = true;
        break;
      }
    }
    return $value;
  }
  static function RemoveUserFromRole($username, $rolename){
    $user = User::GetByUserName($username);
    if($user){
      $role = Role::GetByName($rolename);
      if($role){
        Role::RemoveUserFromRole($user->user_id, $role->role_id);
      }
    }
  }
  static function RemoveUserFromRoles($username, $rolenames){
    foreach($rolenames as $rolename){
      Roles::RemoveUserFromRole($username, $rolename);
    }
  }
  static function RemoveUsersFromRole($usernames, $rolename){
    foreach($usernames as $username){
      Roles::RemoveUserFromRole($username, $rolename);
    }
  }
  static function RemoveUsersFromRoles($usernames, $rolenames){
    foreach($usernames as $username){
      foreach($rolenames as $rolename){
        Roles::RemoveUserFromRole($username, $rolename);
      }
    }
  }
  static function RoleExists($rolename){
    $role = Role::GetByName($rolename);
    return ($role != null);
  }
}
?>