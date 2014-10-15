<?php 

require_once ( "LoginDb.php");

class Login extends LoginDb {
   	
	private $oLoginDb;
	
	public function __construct()
	{
		$this->oLoginDb = new LoginDb();
	}
	
	public function logar($arr_args) 
	{	
				
		$arr_return = array(
				"lang" => $arr_args['lang'],
				"code" => '',
				'transaction' => 'NO',
				'msg' => ''
		);
		
		$id = $this->oLoginDb->getUserId($arr_args);
		
		if ( $id > 0 ) {
			$pincode = $this->oLoginDb->makePincode(4);			
			if($pincode) {
				if( $this->oLoginDb->regPincode($id, $pincode) ) {
					$encode = $this->oLoginDb->encodePincode($pincode);
					
					if($encode) {												
						$arr_return['pincode'] = $encode;
						$arr_return['transaction'] = 'OK';
						$arr_return['msg'] = 'Logon gerado';						
												
					} else {
						$arr_return['msg'] = 'Erro ao encodar pincode';
					}
				} else {
					$arr_return['msg'] = 'Erro ao registrar pincode';
				}
			} else {
				$arr_return['msg'] = 'Erro criar pincode';
			}
			
		} else {
			$arr_return['msg'] = 'Usuário ou senha inválidos';
		}
				
		return $arr_return;
		
	}
	
	
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
	
	public function reenviar($arr_args, $oConfigs) {
		
		//TODO:ARRUMAR ISSO AQUI
		
		//LOCALIZACAO DE ARQUIVOS {
		if($_SERVER['DOCUMENT_ROOT'] == "/Library/WebServer/Sites") {
			$local_root = $_SERVER['DOCUMENT_ROOT'];
			$local_simbolic = "/invoice";
		} else {
			$local_root = $_SERVER['DOCUMENT_ROOT'];
			$local_simbolic = "";
		}
		//LOCALIZACAO DE ARQUIVOS }
		
		//ARR RETORNO JSON
		$arr_return = array(
				"lang" => $arr_args['lang'],
				"code" => '',
				'transaction' => 'NO',
				'msg' => ''
		);
		
		$error = false;
	
		require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Emails.php");
		$oEmails = new Emails ();		
		
		//Email invalido
		if( !$oEmails->validaMail($arr_args['email']) ) {
			$arr_return['msg'] = $oConfigs->get('login','email_invalido'); 
			return $arr_return;
		}
	
		require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/user/User.php");
		$oUser = new User();
		
		$arr_result = $oUser->getByEmail( $arr_args['email'] );
		
		if ($arr_result['email']) {
			
			if($oEmails->reenvioSenhaUsuario($arr_args['email'], $arr_args['senha'], $oConfigs) ) {
				$arr_return['transaction'] = 'OK';
				$arr_return['msg'] = $oConfigs->get('login','email_sucesso');
			} else {
				$arr_return['transaction'] = 'NO';
				$arr_return['msg'] = $oConfigs->get('login','erro_enviar_email');
			}						
		 
		} else {
			$arr_return['transaction'] = 'NO';
			$arr_return['msg'] = $oConfigs->get('login','email_nao_enviado');
		}	
	
		return $arr_return;
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
	
	public function troca_senha_e_logon($arr_args){
		return $this->oDbUser->troca_senha_updateUsuarioDB( $arr_args );
	}
	
}



?>