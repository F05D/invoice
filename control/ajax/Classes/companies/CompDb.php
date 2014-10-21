<?php 

//TODO:Arrumar esta forma de buscar diretorio
if($_SERVER['DOCUMENT_ROOT'] == "/Library/WebServer/Sites") {
	$local_root = $_SERVER['DOCUMENT_ROOT'];
	$local_simbolic = "/invoice";
} else {
	$local_root = $_SERVER['DOCUMENT_ROOT'];
	$local_simbolic = "";
}

require_once ( $local_root . $local_simbolic . "/api/restful/Db.php");

class CompDb extends Db  {
   	
	private $oDB;
	private $oSupport;
	
	public function __construct()
	{
		if(!$this->oDB) $this->oDB = new db(); //DB
		
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
	
	protected function getDB($id)
	{
		$arrResult = array();
	
		$query = "SELECT * FROM companies WHERE ativo = true AND id = $id;";
		$result = $this->oDB->select ( $query );	
		return $this->oSupport->transcriberToList($result);		 
	}
	
	protected function getBindUserDB($id)
	{
		$query = "select u.id,u.nome,u.usuario FROM companies_bind_usuarios bind ".
				 "INNER JOIN usuarios u ON bind.id_usuario = u.id ".
				 "INNER JOIN companies c ON bind.id_company = c.id WHERE c.id = $id";
		$result = $this->oDB->select ( $query );
		return $this->oSupport->transcriberToList($result);
	}
	
	protected function getList($arr_campos=null)
	{
		$arrResult = array();
		
		$fields = ($arr_campos ? $this->oSupport->arrToText($arr_campos) : '*');
		$query = "SELECT $fields FROM companies WHERE ativo = true;";
		$result = $this->oDB->select ( $query );	
		return $this->oSupport->transcriberToList($result);		
	}
	
	protected function createDB($arr_args)
	{
	
		//TODO: SERAH USADO PARA REGISTRO LOG
		//$arr_args['id_usuario']
		//$arr_args['lang']
	
		$field_values  = "";
		$field_values .= " nome = '" .             $arr_args['emp_nome']        . "',";
		$field_values .= " endereco = '" .         $arr_args['emp_end']         . "',";
		$field_values .= " mapa_link = '" .        $arr_args['emp_link_map']    . "',";
		$field_values .= " tel_princ = '" .        $arr_args['emp_tel_p']       . "',";
		$field_values .= " tel_sec = '" .          $arr_args['emp_tel_s']       . "',";
		$field_values .= " cnpj_id = '" .          $arr_args['emp_cnpj_id']     . "',";
		$field_values .= " site = '" .             $arr_args['emp_site']        . "',";
		$field_values .= " email = '" .            $arr_args['emp_email']       . "',";
		$field_values .= " nome_proprietario = '". $arr_args['emp_nome_prop']   . "',";
		$field_values .= " pais = '" .             $arr_args['emp_pais']        . "',";
		$field_values .= " estado = '" .           $arr_args['emp_estado']      . "',";
		$field_values .= " cidade = '" .           $arr_args['emp_cidade']      . "',";
		$field_values .= " comentarios = '" .      $arr_args['emp_comentarios'] . "',";
		$field_values .= " ativo = " .             $arr_args['ativo'];
	
		return $this->oDB->insert("companies", $field_values);
	
	}
	
	protected function updateDB($arr_args)
	{
		
		//TODO: SERAH USADO PARA REGISTRO LOG
		//$arr_args['id_usuario']
		//$arr_args['lang']
		
		$field_values  = "";
		$field_values .= " nome = '" .             $arr_args['emp_nome']        . "',";
		$field_values .= " endereco = '" .         $arr_args['emp_end']         . "',";
		$field_values .= " mapa_link = '" .        $arr_args['emp_link_map']    . "',";
		$field_values .= " tel_princ = '" .        $arr_args['emp_tel_p']       . "',";
		$field_values .= " tel_sec = '" .          $arr_args['emp_tel_s']       . "',";
		$field_values .= " cnpj_id = '" .          $arr_args['emp_cnpj_id']     . "',";
		$field_values .= " site = '" .             $arr_args['emp_site']        . "',";
		$field_values .= " email = '" .            $arr_args['emp_email']       . "',";
		$field_values .= " nome_proprietario = '". $arr_args['emp_nome_prop']   . "',";
		$field_values .= " pais = '" .             $arr_args['emp_pais']        . "',";
		$field_values .= " estado = '" .           $arr_args['emp_estado']      . "',";
		$field_values .= " cidade = '" .           $arr_args['emp_cidade']      . "',";
		$field_values .= " comentarios = '" .      $arr_args['emp_comentarios'] . "',";
		$field_values .= " ativo = " .             $arr_args['ativo'];
		
		$where = " id = ".$arr_args['emp_id'];
		
		return $this->oDB->update("companies", $field_values, $where);
		
	}
	
	protected function verPermissaoUsuarioDB($id_usuario,$code_auth_user)
	{
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
	
	protected function deleteDB($id)
	{		
		$field_values = " ativo = '0' ";
		$where = " id = $id";
	
		return $this->oDB->update("companies", $field_values, $where);
	}
	
		
}
?>