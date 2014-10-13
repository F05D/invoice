<?php 

require_once ( dirname(__FILE__) . "/../db/UserDB.php");

class UserUpdate extends DbUser {
   	
	//User	
	private $oDbUser;
	
	function __construct() {
		 if(!$this->oDbUser) $this->oDbUser = new DbUser(); //User DB New
	}
	
	public function makePINCODEValidation($n) {
		
		return $this->oDbUser->makePINCODE($n);
	}
	
	public function updateUser($arr_args) {
		
		return $this->oDbUser->updateUserDB($arr_args);
		
	}
	
	
}
   