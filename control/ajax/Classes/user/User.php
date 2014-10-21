<?php 

require_once ( "UserDb.php");

class User extends UserDb {
   	
	private $oDbUser;
	private $user = array();
	
	public function __construct()
	{
		$this->oUserDb = new UserDb();
	}
   
	public function getById($id) {
		$arr_result = $this->oUserDb->getByDB($id,NULL);
		return $arr_result;
	}
	
	public function getByEmail($email) {
		$arr_result = $this->oUserDb->getByDB(NULL,$email);
		return $arr_result;
	}
	
	public function autenticaUsuario($pincode) {
	 	$user = $this->oUserDb->searchUserByPincodeDB($pincode);
	 	
	 	if($user['id']) {
	 		$this->user = $this->getById($user['id'],null);
	 		return true; 			 		
	 	} 
	 	
	 	return false;
	}
	
	public function get($key)
	{
		return $this->user[$key];
	}
	
	public function getUserList()
	{
		return $this->oUserDb->getUserList();
	}
	
	public function getCodeSecurity($id)
	{		
		return md5( Date("d").$id."$@#");
	}

	public function getLastId()
	{
		return $this->oUserDb->getLastIdDB();
	}	
	
	public function userPermission($id,$code)
	{
		return ($code == $this->getCodeSecurity($id) ? TRUE : FALSE);
	}	
	
	public function create($arr_user)
	{
		return $this->oUserDb->createDB($arr_user);
	}
	
	public function update($arr_user)
	{
		return $this->oUserDb->updateDB($arr_user);
	}
	
	public  function delete($id)
	{
		return $this->oUserDb->deleteDB($id);		
	}	
	
	public function verificaUserInDB($email,$id = null)
	{
		return $this->oUserDb->verificaUserInDB($email,$id);
	}
	
	public function getListPrivilegios()
	{
		return $this->oUserDb->getListPrivilegiosDB();
	}
		
	public function getListLinguas()
	{
		return $this->oUserDb->getListLinguasDB();
	}	
	
	public function getBindCompany($id)
	{
		return $this->oUserDb->getBindCompanyDB($id);
	}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//AUTENTICACAO - PINCODE - {
	public function checkPINCODE($md5Pincode) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$result = $this->oDbUser->checkPINCODE ( $md5Pincode, $ip );
		
		$passcode = false;		
		if ($result->num_rows) {
			while ( $obj = $result->fetch_object () ) {
				$pinCheck = md5(trim($obj->pincode));
				if( $pinCheck == $md5Pincode) {
					$passcode = true;
				}
			}
		}		
		return $passcode;
	}	
	
	public function getUser($md5Pincode) {
		
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$result = $this->oDbUser->getUserIP($ip);

		$arrUser = array();
		if ($result->num_rows) {
			while ( $obj = $result->fetch_object () ) {
				
				$pincode2md5 = md5($obj->pincode);
				
				if( $md5Pincode == $pincode2md5 ) {
				
					$arrUser = array(
							'id'            => $obj->id,
							'usuario'       => $obj->usuario,
							'senha'         => $obj->senha,
							'privilegios'   => $obj->privilegios,
							'nome'          => $obj->nome,
							'dt_nascimento' => $obj->dt_nascimento,
							'dica_senha'    => $obj->dica_senha,
							'lingua'        => $obj->lingua
					);
					break;
				}
			
			}
		}
		
		return $arrUser;		
	}

	public function getUserById_nao_ativado($id) {
		$arrResult = array();
	
		$result = $this->oDbUser->getUserById_nao_ativadoDB($id);
	
		if( $result->num_rows ) {
			while ( $obj = $result->fetch_object () ) {
				$arrResult = array(
				'id'            => $obj->id,
				'usuario'       => $obj->usuario,
				'senha'         => $obj->senha,
				'privilegios'   => $obj->privilegios,
				'dica_senha'    => $obj->dica_senha,
				'nome'          => $obj->nome,
				'dt_nascimento' => $obj->dt_nascimento,
				'dica_senha'    => $obj->dica_senha,
				'lingua'        => $obj->lingua
				);
			}
		}
	
		return $arrResult;
	}
	
	public function validaUsuario( $code_validacao_email , $email_md5 ) {
		$user = array();
		$result = $this->oDbUser->validaUsuarioDB( $code_validacao_email );
		
		if( $result->num_rows ) {
			while ( $obj = $result->fetch_object () ) {	
				$emailCheck = md5($obj->usuario);				
				if( $emailCheck == $email_md5 ) {	
					$user = $this->getUserById_nao_ativado($obj->id);
				}
			}
		}
	
		return $user;
	}
	
	public function verificaAuthTrocaSenha($code,$id_usuario) {
		$result = $this->getUserById_nao_ativado($id_usuario);
		if(count($result)) {			
			$validadeCode = md5($result['lingua'].$result['id'].$result['usuario'].$result['dt_nascimento'].$result['nome']);
			if($validadeCode == $code) return true;			
		}  
		
		return false;
			
	}
	
	public function getAlias_privilegio($privilegio, $oConfigs) {
		
		switch ($privilegio) {
			case 1:
				return $oConfigs->get('cadastro_usuario','administrador');
				break;
			case 2:
				return $oConfigs->get('cadastro_usuario','usuario');
				break;
			case 3:
				return $oConfigs->get('cadastro_usuario','cliente');
				break;
			default:
				return $oConfigs->get('cadastro_usuario','indefinido');
				break;
		}
	}

	public function getAlias_lingua($lingua, $oConfigs) {
	
		switch ($lingua) {
			case 'pt':
				return $oConfigs->get('cadastro_usuario','lingua_pt');
				break;
			case 'sp':
				return $oConfigs->get('cadastro_usuario','lingua_pt');
				break;
			case 'en':
				return $oConfigs->get('cadastro_usuario','lingua_pt');
				break;
			default:
				return $oConfigs->get('cadastro_usuario','indefinido');
				break;
		}
	}
	
	
	
	
	
	public function troca_senha_e_logon($arr_args){
		return $this->oDbUser->troca_senha_updateUsuarioDB( $arr_args );
	}
	
}



?>