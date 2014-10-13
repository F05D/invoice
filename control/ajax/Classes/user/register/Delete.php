<?php 

require_once ( dirname(__FILE__) . "/../db/UserDB.php");

class UserDelete extends DbUser {
   	
	//User	
	private $oDbUser;
	
	public function __construct()
{
		 if(!$this->oDbUser) $this->oDbUser = new DbUser(); //User DB New
	}
	
	public function ver_permissao_usuario( $id_usuario , $code_auth_user ) {
		return $this->oDbUser->ver_permissao_usuario( $id_usuario , $code_auth_user) ;
	}
	
	public function validar_delete_usuario( $id_delete , $code_auth ) {
		return $this->oDbUser->validar_delete_usuario( $id_usuario , $code_auth_user);
	}
	
	public function deleteUser($id_delete) {
		return $this->oDbUser->deleteUserDB($id_delete);
	}
	
	

}
   