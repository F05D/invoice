<?php 

if($_SERVER['DOCUMENT_ROOT'] == "/Library/WebServer/Sites") {
	$local_root = $_SERVER['DOCUMENT_ROOT'];
	$local_simbolic = "/invoice";
} else {
	$local_root = $_SERVER['DOCUMENT_ROOT'];
	$local_simbolic = "";
}

require_once ( $local_root . $local_simbolic . "/api/restful/Db.php");

class UserDb extends Db {
   	
	private $oDB;
	private $oSupport;
	
	public function __construct()
	{		
		$this->oDB = Db::singleton();
		
		//TODO:Arrumar esta forma de buscar diretorio
		if($_SERVER['DOCUMENT_ROOT'] == "/Library/WebServer/Sites") {
			$local_root = $_SERVER['DOCUMENT_ROOT'];
			$local_simbolic = "/invoice";
		} else {
			$local_root = $_SERVER['DOCUMENT_ROOT'];
			$local_simbolic = "";
		}
		
		require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/common/Support.php");
		$this->oSupport = New Support(); 
		
		
	}
	
	protected function getByDB($id,$email) {
		$arrUser = array();		
		if(!$id || $email ) return $arrUser;
		
		if($id)    $query = "SELECT * FROM usuarios WHERE id = $id;";		
		if($email) $query = "SELECT * FROM usuarios WHERE email = '$email';";
		
		return $this->oSupport->transcriberToList($this->oDB->select( $query ))[0];
	}
		
	//AUTENTICACAO - PINCODE - {
	protected function searchUserByPincodeDB($md5Pincode) 
	{
		$arrUser = array( 'id' => 0, 'usuario' => '');
		
		$ip = $_SERVER["REMOTE_ADDR"];
		
		$arr_round = $this->getAroundDates( 60 * 24,1);
		
		$query = "SELECT u.id, u.usuario, s.pincode FROM sessions s, usuarios u " . 
			" WHERE s.id_usuario = u.id AND s.ip = '$ip' AND s.date_time BETWEEN '".$arr_round['min']."' ".
			" AND '".$arr_round['max']."';";		
		$result = $this->oDB->select($query);
		
		if ($result->num_rows) {
			while ( $obj = $result->fetch_object () ) {
						
				$md5pincodeNOW = md5($obj->pincode);

				if( $md5Pincode == $md5pincodeNOW ) {
					$arrUser['id'] = $obj->id;
					$arrUser['usuario'] = $obj->usuario;
					break;
				}					
			}
		}
		
		return $arrUser;
	}
	
	protected function getAroundDates($min,$max){		
		date_default_timezone_set("America/Sao_Paulo");
		
		$timeNow = date("Y-m-d G:i:s");
		
		$minNow = date("Y-m-d G:i:s",strtotime("-$min minutes",strtotime($timeNow)));
		$maxNow = date("Y-m-d G:i:s",strtotime("+$max minutes",strtotime($timeNow)));
		
		return array( 
				'min' => $minNow,
				'max' => $maxNow
				
		);
	}
	
	protected function getUserList() {
		$query = "SELECT u.*,c.nome as empresa_nome FROM usuarios u RIGHT JOIN companies_bind_usuarios bind ON bind.id_usuario = u.id RIGHT JOIN companies c ON c.id = bind.id_company WHERE u.ativo = true;";
		$result = $this->oDB->select ( $query );
		return $this->oSupport->transcriberToList($result);
	}
	
	protected function deleteDB($id)
	{		
		$field_values = " ativo = 0 ";
		$where = " id = $id ";
		return $this->oDB->update("usuarios", $field_values, $where);
	}
	
	protected function getLastIdDB()
	{
		$query = "SELECT id FROM usuarios ORDER BY id DESC LIMIT 1;";
		$result = $this->oDB->select ( $query );
		$arr_id = $this->oSupport->transcriberToList($result);
		return $arr_id[0]['id'];
		
	}	
	
	protected function getListPrivilegiosDB()
	{
		$query = "SELECT * FROM aux_privilegios ORDER BY 'desc' ASC;";
		$result = $this->oDB->select ( $query );
		return $this->oSupport->transcriberToList($result);;	
	}	
	
	protected function getListLinguasDB()
	{
		$query = "SELECT * FROM aux_linguas ORDER BY 'lingua' ASC;";
		$result = $this->oDB->select ( $query );
		return $this->oSupport->transcriberToList($result);
	}
		
	protected function getBindCompanyDB($id)
	{
		$query = "select c.id FROM companies_bind_usuarios bind ".
				 "INNER JOIN usuarios u ON bind.id_usuario = u.id ".
		 		 "INNER JOIN companies c ON bind.id_company = c.id WHERE u.id = $id";
		$result = $this->oDB->select ( $query );
		return $this->oSupport->transcriberToList($result)[0];
	}
	
	protected function verificaUserInDB($email,$id = null)
	{
		$strNotIn = ( $id ? " AND id NOT IN($id) " : '' ); 
		$query = "SELECT usuario FROM usuarios WHERE usuario = '$email' $strNotIn;";
		
		$result = $this->oDB->select ( $query );
		return ($result->num_rows > 0 ? true : false);
	}

	protected function updateDB($arr_user) {
	
		//TODO: SERAH USADO PARA REGISTRO LOG
		//$arr_args['id_usuario']
		//$arr_args['lang']
	
		$field_values  = "";
		$field_values .= " nome = '" . $arr_user['user_nome'] . "',";
		$field_values .= " dt_nascimento = '" . $arr_user['user_dt_nasc'] . "',";
		$field_values .= " usuario = '" . $arr_user['user_email'] . "',";
		$field_values .= " senha = '" . $arr_user['user_senha'] . "',";
		$field_values .= " dica_senha = '" . $arr_user['user_dica'] . "',";
		$field_values .= " privilegios = '" . $arr_user['user_privilegio'] . "',";
		$field_values .= " lingua = '" . $arr_user['user_lang'] . "',";
	
		$field_values .= " code_validacao_email = '" . $arr_user['code_validacao_email'] . "', ";
		$field_values .= " code_validado = " . $arr_user['code_validado'] . ",";
		$field_values .= " ativo = " . $arr_user['ativo'] . ",";
		$field_values .= " is_new = " . $arr_user['is_new'];
	
		$where = " id = " . $arr_user['id_alter'];
		
		$result_update_user = $this->oDB->update("usuarios", $field_values, $where);
	
		if($result_update_user && $arr_user['user_empresa'])
		{
			$id = $arr_user['id_alter'];
			$this->oDB->delete("companies_bind_usuarios", " id_usuario = $id ");				
			$result_insert_bind = $this->bind( array('id_usuario'=>$id,'id_company'=>$arr_user['user_empresa'] ),'companies_bind_usuarios');
		}
	
		return array(
				'result_update_user' => $result_update_user,
				'result_insert_bind' => $result_insert_bind
		);
	}
	
	protected function createDB($arr_user) {
	
		//TODO: SERAH USADO PARA REGISTRO LOG
		//$arr_args['id_usuario']
		//$arr_args['lang']
	
		$field_values  = "";
		$field_values .= " nome = '" . $arr_user['user_nome'] . "',";
		$field_values .= " dt_nascimento = '" . $arr_user['user_dt_nasc'] . "',";
		$field_values .= " usuario = '" . $arr_user['user_email'] . "',";
		$field_values .= " senha = '" . $arr_user['user_senha'] . "',";
		$field_values .= " dica_senha = '" . $arr_user['user_dica'] . "',";
		$field_values .= " privilegios = '" . $arr_user['user_privilegio'] . "',";
		$field_values .= " lingua = '" . $arr_user['user_lang'] . "',";
		
		$field_values .= " code_validacao_email = '" . $arr_user['code_validacao_email'] . "', ";
		$field_values .= " code_validado = " . $arr_user['code_validado'] . ",";
		$field_values .= " ativo = " . $arr_user['ativo'] . ",";
		$field_values .= " is_new = " . $arr_user['is_new'];
		
		$result_insert_user = $this->oDB->insert("usuarios", $field_values);
		
		if($result_insert_user && $arr_user['user_empresa'])
		{
			$lastId = $this->oDB->lastId('usuarios','id');
			$result_insert_bind = $this->bind( array('id_usuario'=>$lastId,'id_company'=>$arr_user['user_empresa'] ),'companies_bind_usuarios');						
		}
		
		return array( 
				'result_insert_user' => $result_insert_user,
				'result_insert_bind' => $result_insert_bind
				);
	}
	
	protected function bind($arrBind,$table)
	{		
		$bindKeys = array_keys($arrBind);
		$key01 = $bindKeys[0];$val01 = $arrBind[$key01];
		$key02 = $bindKeys[1];$val02 = $arrBind[$key02];
		
		$query = "SELECT * FROM $table WHERE $key01=$val01 AND $key02=$val02;";	
		$result = $this->oDB->select ( $query );		
		if (!$result->num_rows) {
			$field_values = " $key01=$val01, $key02=$val02 ";			
			return $this->oDB->insert($table, $field_values);
		}
		
		return false;
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