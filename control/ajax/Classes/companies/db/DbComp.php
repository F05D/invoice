<?php 

require_once ( dirname(__FILE__) . "/../../../../../api/restful/Db.php");

class DbComp extends Db  {
   	
	//DB	
	private $oDB;
	
	function __construct() {
		if(!$this->oDB) $this->oDB = new db(); //DB
	}
	
	protected function getList(){
		$query = "SELECT * FROM companies WHERE ativo = true;";
		return $this->oDB->select ( $query );		
	}
	
	protected function CompCreate($arr_args){
		
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
	
		
}
?>