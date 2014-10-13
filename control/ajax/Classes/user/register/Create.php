<?php 

require_once ( dirname(__FILE__) . "/../db/UserDB.php");

class UserCreate extends DbUser {
   	
	//User	
	private $oDbUser;
	
	function __construct() {
		 if(!$this->oDbUser) $this->oDbUser = new DbUser(); //User DB New
	}
	
	public function makePINCODEValidation($n) {
		
		return $this->oDbUser->makePINCODE($n);
	}
	
	public function createUser($arr_args) {
		
		return $this->oDbUser->createUserDB($arr_args);
		
	}
	
	public function verifica_mail_na_base_usado($email){
		return $this->oDbUser->verifica_mail_na_base_usado($email);
	}
	
	
}
   