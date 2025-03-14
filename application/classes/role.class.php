<?php
class Role{
	public $role_id;
	public $application_id;
	public $role_name;
	public $creation_dtm;
	public $last_update_dtm;

	function __construct(){
		$num_args = func_num_args();
		switch($num_args){
			case 0:
				$this->role_id = 0;
			break;
			case 1:
				$result = func_get_arg(0);
				foreach($result as $key => $value) $this->$key = $value;
			break;
		}
	}

	function __toString(){
		return get_class($this);
	}
	static function GetAll(){
		$values = array();
		$sql = "call sp_role_get";
		$results = MySQL::Execute($sql);
		if($results) foreach($results as $result) $values[] = new Role($result);
		return $values;
	}

	static function GetById($role_id){
		$value = null;
		$sql = "call sp_role_get_by_id(?)";
		$params = array('s', $role_id);
		$results = MySQL::Execute($sql, $params);
		if($results) $value = new Role($results[0]);
		return $value;
	}

	function Create(){
		$value = null;
		$sql = "call sp_role_ins(?, ?)";
		$params = array('ss', $this->application_id, $this->role_name);
		$results = MySQL::Execute($sql, $params);
		if($results){
			$this->role_id = $results[0]->role_id;
			$this->creation_dtm = $results[0]->creation_dtm;
		}
	}

	function Save(){
		if(empty($this->role_id)){
			$this->Create();
		}else{
			$value = null;
			$sql = "call sp_role_upd(?, ?, ?)";
			$params = array('sss', $this->role_id, $this->application_id, $this->role_name);
			$results = MySQL::Execute($sql, $params);
			if($results){
				$this->last_update_dtm = $results[0]->last_update_dtm;
			}
		}
	}

	function Delete(){
		$sql = "call sp_role_del(?)";
		$params = array('s', $this->role_id);
		MySQL::Execute($sql, $params);
	}

	static function GetByName($role_name){
		$value = null;
		$sql = "call sp_role_get_by_name(?)";
		$params = array('s', $role_name);
		$results = MySQL::Execute($sql, $params);
		if($results) $value = new Role($results[0]);
		return $value;
	}

	static function GetByUser($username){
		$values = array();
		$sql = "call sp_role_get_by_user(?)";
		$params = array('s', $username);
		$results = MySQL::Execute($sql, $params);
		if($results) foreach($results as $result) $values[] = new Role($result);
		return $values;
	}

	static function RemoveUserFromRole($user_id, $role_id){
		$sql = "call sp_user_role_del(?, ?)";
		$params = array('ss', $user_id, $role_id);
		MySQL::Execute($sql, $params);
	}

	static function AddUserToRole($user_id, $role_id){
		$sql = "call sp_user_role_ins(?, ?)";
		$params = array('ss', $user_id, $role_id);
		MySQL::Execute($sql, $params);
	}
}
?>