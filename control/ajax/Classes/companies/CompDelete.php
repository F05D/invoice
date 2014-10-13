<?php 

require_once ( dirname(__FILE__) . "/CompDb.php");

class CompDelete extends CompDb {
   	
	private $oCompDb;
	
	public function __construct()
	{
	}
	
	protected function verPermissaoUsuario( $id_usuario , $code_auth_user ) {
		return $this->oDbComp->verPermissaoUsuarioDB( $id_usuario , $code_auth_user) ;
	}
	
	protected function delete($id_delete) {
		return $this->oDbComp->deleteUserDB($id_delete);
	}
	
	

}
   