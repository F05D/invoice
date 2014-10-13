<?php 




require_once ( dirname(__FILE__) . "/../../../../../api/restful/Db.php");

class DbUser extends Db  {
   	
	//DB	
	private $oDB;
	
	function __construct() {
		if(!$this->oDB) $this->oDB = new db(); //DB
	}
	
	protected function makePINCODE($n) {
		
		//CRIA OBJETO CASO TENHA SIDO HERDADO E NAO INICIADO
		//PARA ERROS: 
		//Fatal error: Call to a member function select() on a non-object in
		if(!$this->oDB) $this->oDB = new db(); 
		
		
		$query = "SELECT primos FROM seguranca ORDER BY RAND() LIMIT $n;";
		
		$result = $this->oDB->select ( $query );
	
		$pincode = 1;
		if ($result->num_rows) {
			while ( $obj = $result->fetch_object () ) {
				$pincode *= intval($obj->primos,10);			
			}
		}
	
		return $pincode;
	}
	
	protected function registerPINCODE ( $id, $lang) {
		
		date_default_timezone_set("America/Sao_Paulo");
		$dataNow = date("Y-m-d G:i:s");
		
		$ip = $_SERVER["REMOTE_ADDR"];
		$valPINCODE = $this->makePINCODE(4);
		
		$field_values = "";
		$field_values .= " id_usuario = $id,";
		$field_values .= " pincode = '$valPINCODE',";
		$field_values .= " ip = '$ip',";
		$field_values .= " date_time = '$dataNow'";
		
		if($this->oDB->insert("sessions", $field_values))
			return $valPINCODE;
		else 
			return false;
	
	}
	
	
	protected function verificarLogin($email, $senha, $lang) {
		
		$query = "SELECT * FROM usuarios WHERE usuario = '".$email."' AND senha = '".$senha."' AND ativo = 1 AND is_new = 0;";
		$result = $this->oDB->select ( $query );
		
		if ($result->num_rows) {
			while ( $obj = $result->fetch_object () ) {
				if($obj->id != NULL)
					$id = $obj->id;
			}
			
			$pingerado = $this->registerPINCODE ( $id ,$lang );
			return md5($pingerado);		
		
		} else {			
			return "";
		}
	}
	
	protected function getInfoUsuario($email){		
			$query = "SELECT * FROM usuarios WHERE usuario = '".$email."' LIMIT 1;";
			return $this->oDB->select ( $query );
	}
	
	//AUTENTICACAO - PINCODE - {
	protected function checkPINCODE($md5Pincode, $ip) {	
		$query = "SELECT pincode FROM sessions WHERE ip = '$ip' ORDER by id DESC LIMIT 1;";
		return $this->oDB->select ( $query );	
	}
	
	//AUTENTICACAO - PINCODE - {
	
	protected function getUserIP($ip) {
		
		$query = "SELECT u.id, u.usuario, u.senha, u.privilegios, u.nome, u.dt_nascimento, u.dica_senha, lingua, ".
		      	" s.pincode " .
				" FROM sessions s, usuarios u WHERE s.ip = '$ip' AND s.id_usuario = u.id AND u.ativo = 1;";

		
		return $this->oDB->select ( $query );
	}
	
	protected function getUserList() {
		$query = "SELECT * FROM usuarios WHERE ativo = true;";
		return $this->oDB->select ( $query );		
	}

	protected function getUserById_nao_ativadoDB($id) {
		$query = "SELECT * FROM usuarios WHERE ativo = 0 AND id = ".$id.";";
		return $this->oDB->select ( $query );
	}
	
	protected function getUserById($id) {
		$query = "SELECT * FROM usuarios WHERE ativo = 1 AND id = ".$id.";";
		return $this->oDB->select ( $query );
	}
	
	protected function updateUserDB($arr_args) {
		
		//TODO: SERAH USADO PARA REGISTRO LOG
		//$arr_args['id_usuario']
		//$arr_args['lang']
		
		$field_values  = "";
		$field_values .= " nome = '" . $arr_args['user_nome'] . "',";
		$field_values .= " dt_nascimento = '" . $arr_args['user_dt_nasc'] . "',";
		$field_values .= " usuario = '" . $arr_args['user_email'] . "',";
		$field_values .= " senha = '" . $arr_args['user_senha'] . "',";
		$field_values .= " dica_senha = '" . $arr_args['user_dica'] . "',";
		$field_values .= " privilegios = '" . $arr_args['user_privilegio'] . "',";
		$field_values .= " lingua = '" . $arr_args['user_lang'] . "',";
		
		$field_values .= " code_validacao_email = '" . $arr_args['code_validacao_email'] . "', ";
		$field_values .= " code_validado = '0',";
		$field_values .= " is_new = '" . $arr_args['is_new'] . "' ";
		
		//TODO:CADASTRO DE EMPRESA
		//$arr_args['user_empresa']
		
		$where =  " id = ".$arr_args['id_alter'];
		
		return $this->oDB->update("usuarios", $field_values, $where);
	}
	
	protected function verifica_mail_na_base( $email, $id ) {
		$query = "SELECT usuario FROM usuarios WHERE usuario = '$email' AND id NOT IN ($id);";
		
		if(!$this->oDB) $this->oDB = new db();
		
		$result = $this->oDB->select ( $query );
		
		if ($result->num_rows) {
			return true;
		}
		
		return false;
	} 

	protected function verifica_mail_na_base_usado( $email) {
		$query = "SELECT usuario FROM usuarios WHERE usuario = '$email';";
	
		if(!$this->oDB) $this->oDB = new db();
	
		$result = $this->oDB->select ( $query );
	
		if ($result->num_rows) {
			return true;
		}
	
		return false;
	}
	
	
	
	protected function ver_permissao_usuario($id_usuario,$code_auth_user){
		$query = "SELECT * FROM usuarios WHERE id = '$id_usuario' LIMIT 1";
		
		$result = $this->oDB->select ( $query );
			
		if( $result->num_rows ) {
			while ( $obj = $result->fetch_object () ) {
				$user_code   =  md5($obj->usuario.$obj->id.$obj->lingua);
				
				if( $user_code == $code_auth_user) {
					return $obj->privilegios;
				}
			}
		}
		
		return false;
	}
	
	protected function validar_delete_usuario($id_delete,$code_auth){
		$query = "SELECT * FROM usuarios WHERE id = $id_delete;";
		$result = $this->oDB->select ( $query );
		
		if( $result->num_rows )
			while ( $obj = $result->fetch_object () ) {
				$delete_code = md5( $obj->nome . $obj->id. $obj->usuario. $obj->dt_nascimento . $obj->privilegios . $obj->lingua );
				if($obj->id == $id_delete && $delete_code == $code_auth)
					return true;
			}
		
		return false;				
	}
	
	protected function deleteUserDB($id_delete) {
		$field_values = " ativo = 0 ";
		$where = " id = $id_delete";
		
		return $this->oDB->update("usuarios", $field_values, $where);
	}
	
	protected function createUserDB($arr_args) {
	
		//TODO: SERAH USADO PARA REGISTRO LOG
		//$arr_args['id_usuario']
		//$arr_args['lang']
		
		$field_values  = "";
		$field_values .= " nome = '" . $arr_args['user_nome'] . "',";
		$field_values .= " dt_nascimento = '" . $arr_args['user_dt_nasc'] . "',";
		$field_values .= " usuario = '" . $arr_args['user_email'] . "',";
		$field_values .= " senha = '" . $arr_args['user_senha'] . "',";
		$field_values .= " dica_senha = '" . $arr_args['user_dica'] . "',";
		$field_values .= " privilegios = '" . $arr_args['user_privilegio'] . "',";
		$field_values .= " lingua = '" . $arr_args['user_lang'] . "',";
	
		$field_values .= " code_validacao_email = '" . $arr_args['code_validacao_email'] . "', ";
		$field_values .= " code_validado = " . $arr_args['code_validado'] . ",";
		$field_values .= " ativo = " . $arr_args['ativo'] . ",";
		$field_values .= " is_new = " . $arr_args['is_new'];
	
		//TODO:CADASTRO DE EMPRESA
		//$arr_args['user_empresa']
	
		return $this->oDB->insert("usuarios", $field_values);
	}
	
	protected function validaUsuarioDB( $code_validacao_email ) {
		$query = "SELECT * FROM usuarios WHERE is_new = 1 AND code_validacao_email = '$code_validacao_email';";
		return $this->oDB->select ( $query );
	}
	
	protected function troca_senha_updateUsuarioDB( $arr_args ) {
		
		$field_values  = " senha = '".$arr_args['user_senha']."', ";
		$field_values .= " dica_senha = '".$arr_args['user_dica']."', ";
		$field_values .= " code_validado = 1, ";
		$field_values .= " is_new = 0, ";
		$field_values .= " ativo = 1 ";
		$where = " id = " . $arr_args['id_usuario'] . " AND usuario = '".$arr_args['user_email']."' ";
				
		return $this->oDB->update("usuarios", $field_values, $where);
	}
	
	
	
	
		
}
?>