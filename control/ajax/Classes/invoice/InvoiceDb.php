<?php 

if($_SERVER['DOCUMENT_ROOT'] == "/Library/WebServer/Sites") {
	$local_root = $_SERVER['DOCUMENT_ROOT'];
	$local_simbolic = "/invoice";
} else {
	$local_root = $_SERVER['DOCUMENT_ROOT'];
	$local_simbolic = "";
}

require_once ( $local_root . $local_simbolic . "/api/restful/Db.php");

class InvoiceDb extends Db {
   	
	private $oDB;
	private $oSupport;
	private $oValiacoes;
	
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
		
		require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
		$this->oValiacoes = new Validacoes();
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
		$query = "SELECT $fields FROM invoices ORDER BY id ASC";
		$query = "SELECT $fields FROM invoices i " . 
				 "INNER JOIN invoices_bind bind ON bind.invoice_id = i.id ". 
				 "INNER JOIN companies c ON bind.company_id = c.id ORDER BY i.id ASC";
		
		$result = $this->oDB->select ( $query );	
		return $this->oSupport->transcriberToList($result);		
	}
	
	protected function createDB($arr_args)
	{
	
		$invoice_lastId = $this->InvoiceInsert($arr_args);
		
		if($invoice_lastId)
		{
			$this->RegisterDocs($arr_args, $invoice_lastId);
		
			if( $this->BindInvoice($arr_args, $invoice_lastId) ) 
			{
				return array('transaction' => 'OK', 'step' => 'bind' );
				
			} else {
				
				return array('transaction' => 'NO', 'step' => 'bind' );
			}
		} else {
			return array('transaction' => 'NO', 'step' => 'insert_invoice' );
		}
	}
	
	protected function InvoiceInsert($arr_args)
	{
		date_default_timezone_set("America/Sao_Paulo");
		$dataNow = date("Y-m-d G:i:s");
		
		$dt_venc = $this->oValiacoes->convertDataToDB( $arr_args['invoice_data_vencimento'], $arr_args['lang'] );
		$dt_emba = $this->oValiacoes->convertDataToDB( $arr_args['invoice_embarque_data'], $arr_args['lang'] );
		
		
		$field_values  = "";
		$field_values .= " data_registro_invoice = '". $dataNow                                  ."',";
		$field_values .= " invoice_nr            = '". $arr_args['invoice_nr']                   ."',";
		$field_values .= " container             = '". $arr_args['invoice_container']            ."',";
		$field_values .= " booking               = '". $arr_args['invoice_booking']              ."',";
		$field_values .= " tipo                  = '". $arr_args['invoice_tipo']                 ."',";
		$field_values .= " tara                  =  ". $arr_args['invoice_tara']                 ." ,";
		$field_values .= " peso_bruto            =  ". $arr_args['invoice_peso_bruto']           ." ,";
		$field_values .= " peso_liquido          =  ". $arr_args['invoice_peso_liquido']         ." ,";
		$field_values .= " qnt                   =  ". $arr_args['invoice_qnt']                  ." ,";
		$field_values .= " nota_fiscal           = '". $arr_args['invoice_nota_fiscal']          ."',";
		$field_values .= " lacres                = '". $arr_args['invoice_lacres']               ."',";
		$field_values .= " fatura_n              = '". $arr_args['invoice_fatura_n']             ."',";
		$field_values .= " fatura_valor          =  ". $arr_args['invoice_fatura_valor']         ." ,";
		$field_values .= " data_vencimento       = '". $dt_venc                                  ."',";
		$field_values .= " embarque_data         = '". $dt_emba                                  ."',";
		$field_values .= " embarque_confirmacao  = '". $arr_args['invoice_embarque_confirmacao'] ."',";
		$field_values .= " status_id             = 0,";
		$field_values .= " notificado            = 0,";
		$field_values .= " visualizado           = 0,";
		$field_values .= " deletada              = 0 ";
		
		if( $this->oDB->insert("invoices", $field_values) ) {
			$result = $this->oDB->lastId("invoices",'id');
			return $this->oSupport->transcriberToList($result)[0]['id'];
		} else {
			return 0;
		}
	}

	protected function RegisterDocs($arr_args, $invoice_lastId)
	{
		
		if($arr_args['arrDocs']['docs_packinglist']['tmp_md5'] != "") 
		{
			$arr = $arr_args['arrDocs']['docs_packinglist'];				
			$field_values  = "";
			$field_values .= " name = '". $arr['name'] . "',";
			$field_values .= " type = '". $arr['type'] . "',";
			$field_values .= " error = '". $arr['error'] . "',";
			$field_values .= " size = '". $arr['size'] . "',";
			$field_values .= " transaction = '". $arr['transaction'] . "',";
			$field_values .= " local_name_md5 = '". $arr['tmp_md5'] . "',";		
			$field_values .= " locked = 1,";
			$field_values .= " visualizado = 0,";
			$field_values .= " id_invoice = $invoice_lastId,";
			$field_values .= " doc_type = 1"; // 1 PARA docs_packinglist
			$this->oDB->insert("invoices_docs", $field_values);
		}
		
		if($arr_args['arrDocs']['docs_formei']['tmp_md5'] != "")
		{		
			$arr =  $arr_args['arrDocs']['docs_formei'];
			$field_values  = "";
			$field_values .= " name = '". $arr['name'] . "',";
			$field_values .= " type = '". $arr['type'] . "',";
			$field_values .= " error = '". $arr['error'] . "',";
			$field_values .= " size = '". $arr['size'] . "',";
			$field_values .= " transaction = '". $arr['transaction'] . "',";
			$field_values .= " local_name_md5 = '". $arr['tmp_md5'] . "',";		
			$field_values .= " locked = 1,";
			$field_values .= " visualizado = 0,";
			$field_values .= " id_invoice = $invoice_lastId,";
			$field_values .= " doc_type = 2"; // 1 PARA docs_formei
			$this->oDB->insert("invoices_docs", $field_values);		
		}
		
		if($arr_args['arrDocs']['docs_fumigacao']['tmp_md5'] != "")
		{
			$arr =  $arr_args['arrDocs']['docs_fumigacao'];
			$field_values  = "";
			$field_values .= " name = '". $arr['name'] . "',";
			$field_values .= " type = '". $arr['type'] . "',";
			$field_values .= " error = '". $arr['error'] . "',";
			$field_values .= " size = '". $arr['size'] . "',";
			$field_values .= " transaction = '". $arr['transaction'] . "',";
			$field_values .= " local_name_md5 = '". $arr['tmp_md5'] . "',";		
			$field_values .= " locked = 1,";
			$field_values .= " visualizado = 0,";
			$field_values .= " id_invoice = $invoice_lastId,";
			$field_values .= " doc_type = 3"; // 3 PARA docs_fumigacao
			$this->oDB->insert("invoices_docs", $field_values);
		}
		
		if($arr_args['arrDocs']['docs_bl']['tmp_md5'] != "")
		{
			$arr =  $arr_args['arrDocs']['docs_bl'];
			$field_values  = "";
			$field_values .= " name = '". $arr['name'] . "',";
			$field_values .= " type = '". $arr['type'] . "',";
			$field_values .= " error = '". $arr['error'] . "',";
			$field_values .= " size = '". $arr['size'] . "',";
			$field_values .= " transaction = '". $arr['transaction'] . "',";
			$field_values .= " local_name_md5 = '". $arr['tmp_md5'] . "',";		
			$field_values .= " locked = 1,";
			$field_values .= " visualizado = 0,";
			$field_values .= " id_invoice = $invoice_lastId,";
			$field_values .= " doc_type = 4"; // 4 PARA docs_bl
			$this->oDB->insert("invoices_docs", $field_values);
		}
		
		if($arr_args['arrDocs']['docs_invoice']['tmp_md5'] != "")
		{
			$arr =  $arr_args['arrDocs']['docs_invoice'];
			$field_values  = "";
			$field_values .= " name = '". $arr['name'] . "',";
			$field_values .= " type = '". $arr['type'] . "',";
			$field_values .= " error = '". $arr['error'] . "',";
			$field_values .= " size = '". $arr['size'] . "',";
			$field_values .= " transaction = '". $arr['transaction'] . "',";
			$field_values .= " local_name_md5 = '". $arr['tmp_md5'] . "',";		
			$field_values .= " locked = 1,";
			$field_values .= " visualizado = 0,";
			$field_values .= " id_invoice = $invoice_lastId,";
			$field_values .= " doc_type = 5"; // 5 PARA docs_invoices
			$this->oDB->insert("invoices_docs", $field_values);
		}
		
		if($arr_args['arrDocs']['docs_ISF']['tmp_md5'] != "")
		{
			$arr =  $arr_args['arrDocs']['docs_ISF'];
			$field_values  = "";
			$field_values .= " name = '". $arr['name'] . "',";
			$field_values .= " type = '". $arr['type'] . "',";
			$field_values .= " error = '". $arr['error'] . "',";
			$field_values .= " size = '". $arr['size'] . "',";
			$field_values .= " transaction = '". $arr['transaction'] . "',";
			$field_values .= " local_name_md5 = '". $arr['tmp_md5'] . "',";		
			$field_values .= " locked = 1,";
			$field_values .= " visualizado = 0,";
			$field_values .= " id_invoice = $invoice_lastId,";
			$field_values .= " doc_type = 6"; // 6 PARA docs_ISF
			$this->oDB->insert("invoices_docs", $field_values);
		}
		
		
	}
	
	protected function BindInvoice($arr_args, $invoice_lastId)
	{
		$field_values  = "";
		$field_values .= " log_id       = 0,";
		$field_values .= " company_id   = ". $arr_args['invoice_empresa'] . ",";
		$field_values .= " bancarios_id = ". $arr_args['invoice_banco'] . ",";
		$field_values .= " invoice_id   = $invoice_lastId";
		
		return $this->oDB->insert("invoices_bind", $field_values);
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