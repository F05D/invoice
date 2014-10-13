<?php 

require_once ( dirname(__FILE__) . "/../db/UserDB.php");

class CompDelete extends DbComp {
   	
	//User	
	private $oDbComp;
	
	function __construct() {
		 if(!$this->oDbComp) $this->oDbComp = new DbUser(); //User DB New
	}
	
	public function ver_permissao_usuario( $id_usuario , $code_auth_user ) {
		return $this->oDbComp->ver_permissao_usuario( $id_usuario , $code_auth_user) ;
	}
	
	public function validar_delete_usuario( $id_delete , $code_auth ) {
		return $this->oDbComp->validar_delete_usuario( $id_usuario , $code_auth_user);
	}
	
	public function deleteUser($id_delete) {
		return $this->oDbComp->deleteUserDB($id_delete);
	}
	
	

}
   