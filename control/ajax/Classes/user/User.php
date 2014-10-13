<?php 

require_once ( dirname(__FILE__) . "/db/UserDB.php");

class User extends DbUser {
   	
	//User DB	
	private $oDbUser;
	
	function __construct() {
		if(!$this->oDbUser) $this->oDbUser = new DbUser(); //User DB New
	}
   
	public function logar($arr_args) {
		return $this->oDbUser->verificarLogin($arr_args['email'],$arr_args['senha'],$arr_args['lang']);
	}
	
	public function reenviar_senha($arr_args, $oConfigs) {
		
		//TODO:ARRUMAR ISSO AQUI
		
		//LOCALIZACAO DE ARQUIVOS {
		if($_SERVER['DOCUMENT_ROOT'] == "/Library/WebServer/Sites") {
			$local_root = $_SERVER['DOCUMENT_ROOT'];
			$local_simbolic = "/www.invoice.artsulgranitos.com.br";
		} else {
			$local_root = $_SERVER['DOCUMENT_ROOT'];
			$local_simbolic = "";
		}
		//LOCALIZACAO DE ARQUIVOS }
		
		require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Emails.php");
		$oEmails = new Emails ();		
		
		$error = false; $cache_html = "";
		
		//Email invalido
		if( !$oEmails->valida_mail($arr_args['email']) ) {
			$cache_html .= $oConfigs->get('login','email_invalido');
			$error = true;
		}
		
		if($error) {
			$cache_html = $oConfigs->get('login','erros_encontrados') . $cache_html;
			return $cache_html;
		}
		
		$result = $this->oDbUser->getInfoUsuario ( $arr_args['email'] );		
		if ($result->num_rows) {
			while ( $obj = $result->fetch_object () ) {					
				$cache_html = $oEmails->reenvioSenhaUsuario($obj->email, $obj->senha, $oConfigs);
			}
		} else {		
			$cache_html = $oConfigs->get('login','email_nao_enviado');
		}		
		
		return $cache_html;
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
	
	public function getUserById($id) {
		$arrResult = array();
		
		$result = $this->oDbUser->getUserById($id);
		
		if( $result->num_rows ) {
			while ( $obj = $result->fetch_object () ) {
				array_push( $arrResult, array(
					'id'            => $obj->id,
					'usuario'       => $obj->usuario,
					'senha'         => $obj->senha,
					'privilegios'   => $obj->privilegios,
					'dica_senha'    => $obj->dica_senha,
					'nome'          => $obj->nome,
					'dt_nascimento' => $obj->dt_nascimento,
					'dica_senha'    => $obj->dica_senha,
					'lingua'        => $obj->lingua
				)
				);
			}
		}
		
		return $arrResult;
	}
	
	public function getUserList(){
		$arrResult = array();
		
		$result = $this->oDbUser->getUserList();
		if( $result->num_rows ) {
			while ( $obj = $result->fetch_object () ) {
				array_push( $arrResult, array(
												'id'            => $obj->id,
												'usuario'       => $obj->usuario,
												'senha'         => $obj->senha,
												'privilegios'   => $obj->privilegios,
												'nome'          => $obj->nome,
												'dt_nascimento' => $obj->dt_nascimento,
												'dica_senha'    => $obj->dica_senha,
												'lingua'        => $obj->lingua
										)
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