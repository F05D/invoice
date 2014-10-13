<?php 

//TODO: Verificar esse link de acesso a classe;
require_once ( dirname(__FILE__) . "/../../../../api/restful/Db.php");

class CompDb extends Db  {
   	
	//DB	
	private $oDB;
	
	public function __construct()
	{
		if(!$this->oDB) $this->oDB = new db(); //DB
	}
	
	protected function getList(){
		$arrResult = array();
		
		$query = "SELECT * FROM companies WHERE ativo = true;";
		$result = $this->oDB->select ( $query );	
		
		if( $result->num_rows ) {
			while ( $obj = $result->fetch_object () ) {
				array_push( $arrResult, array(
				'id'        => $obj->id,
				'nome'      => $obj->nome,
				'end'       => $obj->endereco,
				'map_ln'    => $obj->mapa_link,
				'tel_p'     => $obj->tel_princ,
				'tel_s'     => $obj->tel_sec,
				'cnpj_id'   => $obj->cnpj_id,
				'site'      => $obj->site,
				'email'     => $obj->email,
				'nome_prop' => $obj->nome_proprietario,
				'pais'      => $obj->pais,
				'estado'    => $obj->estado,
				'cidade'    => $obj->cidade,
				'coment'    => $obj->comentarios,
				'ativo'     => $obj->ativo
				)
				);
			}
		}
		return $arrResult;
	}
	
	protected function createDB($arr_args){
		
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
	
	protected function verPermissaoUsuarioDB($id_usuario,$code_auth_user){
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
	
		
}
?>