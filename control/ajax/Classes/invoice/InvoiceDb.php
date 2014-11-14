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
	
		$query = "SELECT * FROM invoices inv INNER JOIN invoices_bind b ON b.invoice_id = inv.id WHERE inv.deletada = 0 AND inv.id = $id;";
		$result = $this->oDB->select ( $query );	
		return $this->oSupport->transcriberToList($result);		 
	}
	
	protected function getDocsDB($id)
	{
		$arrResult = array();	
		$query = "SELECT * FROM invoices_docs WHERE id_invoice = $id ORDER BY doc_type ASC;";		
		$result = $this->oDB->select ( $query );
		$arrDocs = $this->oSupport->transcriberToList($result);
		
		$arrDocsConvert = array();		
		foreach ($arrDocs as $val){		
			$arrDocsConvert[$val['doc_type']] = array(
					'id'             => $val['id'],
					'name'           => $val['name'],
					'locked'         => $val['locked'],
					'type'           => $val['type'],
					'local_name_md5' => $val['local_name_md5'],
					'size'           => $val['size'],
					'id_invoice'     => $val['id_invoice'],
					'visualizado'    => $val['visualizado'],
					'visualizado_dt' => $val['visualizado_dt'],
					'error'          => $val['error'],
					'transaction'    => $val['transaction']
			);
		}
		return $arrDocsConvert;
		
		
	}
	
	protected function getBindUserDB($id)
	{
		$query = "select u.id,u.nome,u.usuario FROM companies_bind_usuarios bind ".
				 "INNER JOIN usuarios u ON bind.id_usuario = u.id ".
				 "INNER JOIN companies c ON bind.id_company = c.id WHERE c.id = $id";
		$result = $this->oDB->select ( $query );
		return $this->oSupport->transcriberToList($result);
	}
	
	protected function getList($arr_campos=null, $limit = null, $search = null, $orderBy = null)
	{
		$arrResult = array();
		
		$orderStr = ' ORDER BY i.id ';
		if($orderBy['o_by'] == 'in') $orderStr = ' ORDER BY i.invoice_nr ';
		if($orderBy['o_by'] == 'po') $orderStr = ' ORDER BY i.po ';
		if($orderBy['o_by'] == 'co') $orderStr = ' ORDER BY i.container ';
		if($orderBy['o_by'] == 'em') $orderStr = ' ORDER BY c.nome ';
		if($orderBy['o_by'] == 've') $orderStr = ' ORDER BY i.data_vencimento ';
		if($orderBy['o_by'] == 'nt') $orderStr = ' ORDER BY i.notificado ';
		if($orderBy['o_by'] == 'vi') $orderStr = ' ORDER BY i.visualizado ';
		if($orderBy['o_by'] == 'st') $orderStr = ' ORDER BY i.status_id ';

		if($orderBy['o_tg'] == 'asc') $orderStr .= ' ASC '; else $orderStr .= ' DESC ';
		
		//ORDER ESPECIAL DE CABECALHO
		if($orderBy['o_by'] == 'pg_ok') $orderStr = ' ORDER BY i.status_id ';
		if($orderBy['o_by'] == 'pg_no') $orderStr = ' ORDER BY i.status_id ';
		if($orderBy['o_by'] == 'pg_ve') $orderStr = ' ORDER BY i.status_id ';

		
		$searchStr = '';
		if($search['s_in']) $searchStr = " WHERE invoice_nr LIKE '%".$search['s_in']."%'";
		if($search['s_po']) $searchStr = " WHERE po         LIKE '%".$search['s_po']."%'";
		if($search['s_co']) $searchStr = " WHERE container  LIKE '%".$search['s_co']."%'";
		
		if($search['s_special'] == 'pg_ok') $searchStr = " WHERE i.status_id = 1 ";
		if($search['s_special'] == 'pg_no') $searchStr = " WHERE i.status_id = 2 ";
		if($search['s_special'] == 'pg_ve') $searchStr = " WHERE i.status_id = 2 AND i.data_vencimento < '".date("Y-m-d")."'";
		if($search['s_special'] == 'pg_tt') $searchStr = " ";
		
		$fields = ($arr_campos ? $this->oSupport->arrToText($arr_campos) : '*');
		$query = "SELECT i.*, c.nome FROM invoices i " . 
				 "INNER JOIN invoices_bind bind ON bind.invoice_id = i.id ". 
				 "INNER JOIN companies c ON bind.company_id = c.id $searchStr $orderStr ";
		
		$query .= ($limit != null ? $limit : '');
		
		$result = $this->oDB->select ( $query );	
		return $this->oSupport->transcriberToList($result);		
	}
	
	protected function numRowsDB($search = null)
	{
		
		$searchStr = '';
		if($search['s_in']) $searchStr = " WHERE invoice_nr LIKE '%".$search['s_in']."%'";
		if($search['s_po']) $searchStr = " WHERE po         LIKE '%".$search['s_po']."%'";
		if($search['s_co']) $searchStr = " WHERE container  LIKE '%".$search['s_co']."%'";
		
		if($search['s_special'] == 'pg_ok') $searchStr = " WHERE i.status_id = 1 ";
		if($search['s_special'] == 'pg_no') $searchStr = " WHERE i.status_id = 2 ";
		if($search['s_special'] == 'pg_ve') $searchStr = " WHERE i.status_id = 2 AND i.data_vencimento < '".date("Y-m-d")."'";
		if($search['s_special'] == 'pg_tt') $searchStr = " ";
		
		$fields = ($arr_campos ? $this->oSupport->arrToText($arr_campos) : '*');
		$query = "SELECT i.id FROM invoices i " .
				"INNER JOIN invoices_bind bind ON bind.invoice_id = i.id ".
				"INNER JOIN companies c ON bind.company_id = c.id $searchStr ";
		
		$result = $this->oDB->select ( $query );
		return $result->num_rows;
	}
	
	protected function createDB($arr_args)
	{
	
		$invoice_lastId = $this->InvoiceInsert($arr_args);		
		if($invoice_lastId)
		{
			$this->RegisterDocs($arr_args, $invoice_lastId);		
			if( $this->BindInvoice($arr_args, $invoice_lastId) ) {
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
		$field_values .= " po                    = '". $arr_args['invoice_po']                   ."',";
		$field_values .= " fatura_valor          =  ". $arr_args['invoice_fatura_valor']         ." ,";
		$field_values .= " data_vencimento       = '". $dt_venc                                  ."',";
		$field_values .= " embarque_data         = '". $dt_emba                                  ."',";
		$field_values .= " embarque_confirmacao  = '". $arr_args['invoice_embarque_confirmacao'] ."',";
		$field_values .= " status_id             = 2,";
		$field_values .= " notificado            = 0,";
		$field_values .= " notificado_dt         = '0000-00-00 00:00:00',";
		$field_values .= " visualizado           = 0,";
		$field_values .= " visualizado_dt        = '0000-00-00 00:00:00',";
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
		$arr_count = 1;
		foreach ($arr_args['arrDocs'] as $val)
		{
			if($val['name'] != '')
			{
				$field_values  = "";
				$field_values .= " name = '".           $val['name'] . "',";
				$field_values .= " type = '".           $val['type'] . "',";
				$field_values .= " error = '".          ($val['error']? '1' : '0') . "',";
				$field_values .= " size = '".           $val['size'] . "',";
				$field_values .= " transaction = '".    $val['transaction'] . "',";
				$field_values .= " local_name_md5 = '". $val['tmp_md5'] . "',";
				$field_values .= " locked = ".          $val['lock'].",";
				$field_values .= " visualizado = 0,";
				$field_values .= " id_invoice = $invoice_lastId,";
				$field_values .= " doc_type = $arr_count";

				$this->oDB->insert("invoices_docs", $field_values);
			}
			$arr_count++;
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
		$id = $this->InvoiceUpdate($arr_args);
		if($id)
		{
			$this->RegisterDocsUpdate($arr_args, $id);
		
			if( $this->BindInvoiceUpdate($arr_args, $id) ) 
			{
				return array('transaction' => 'OK', 'step' => 'bind' );
				
			} else {
				
				return array('transaction' => 'NO', 'step' => 'bind' );
			}
		} else {
			return array('transaction' => 'NO', 'step' => 'insert_invoice' );
		}		
	}
	
	protected function InvoiceUpdate($arr_args)
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
		$field_values .= " po                    = '". $arr_args['invoice_po']                   ."',";
		$field_values .= " fatura_valor          =  ". $arr_args['invoice_fatura_valor']         ." ,";
		$field_values .= " data_vencimento       = '". $dt_venc                                  ."',";
		$field_values .= " embarque_data         = '". $dt_emba                                  ."',";
		$field_values .= " embarque_confirmacao  = '". $arr_args['invoice_embarque_confirmacao'] ."',";
		$field_values .= " status_id             = '". $arr_args['invoice_status']               ."' ";

		$where = " id = " . $arr_args['id_invoice'];
		
		if( $this->oDB->update("invoices", $field_values, $where) ) {
			return $arr_args['id_invoice'];
		} else {
			return 0;
		}
	}
	
	protected function RegisterDocsUpdate($arr_args, $invoice_id)
	{		
		$int_count = 1;
		foreach ($arr_args['arrDocs'] as $val) {
			
			if( $val['action'] == 'change' ) {
				$field_values  = "";
					
				if( $val['name'] != "" ) {
					$field_values .= " name = '". $val['name'] . "',";
					$field_values .= " type = '". $val['type'] . "',";
					$field_values .= " error = '". $val['error'] . "',";
					$field_values .= " size = '". $val['size'] . "',";
					$field_values .= " transaction = '". $val['transaction'] . "',";
					$field_values .= " local_name_md5 = '". $val['tmp_md5'] . "',";
					$field_values .= " doc_type = ". $int_count . ",";
					$field_values .= " id_invoice = ". $invoice_id . ",";
					$field_values .= " visualizado = 0, ";
				}					
				$field_values .= " locked = ".$val['lock'];
				$this->oDB->insertOrUpdate("invoices_docs", $field_values, " id_invoice = $invoice_id AND doc_type = $int_count");
			}
				
			if( $val['action'] == 'remove' ) {
				if($this->oDB->delete("invoices_docs", " id_invoice = $invoice_id AND doc_type = $int_count")) {
					
					if($_SERVER['DOCUMENT_ROOT'] == "/Library/WebServer/Sites") {
						$local_root = $_SERVER['DOCUMENT_ROOT'];
						$local_simbolic = "/invoice";
					} else {
						$local_root = $_SERVER['DOCUMENT_ROOT'];
						$local_simbolic = "";
					}
					
					$fileDel = $local_root . $local_simbolic . "/uploads/" . $val['tmp_md5'];
					if (file_exists($fileDel)) {
						unlink($fileDel);
					} 
				}
			}
			$int_count++;
		}
	}
	
	protected function BindInvoiceUpdate($arr_args, $invoice_id)
	{
		$field_values  = "";
		$field_values .= " log_id       = 0,";
		$field_values .= " company_id   = ". $arr_args['invoice_empresa'] . ",";
		$field_values .= " bancarios_id = ". $arr_args['invoice_banco'];
	
		return $this->oDB->update("invoices_bind", $field_values, " invoice_id = $invoice_id ");
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
		$field_values = " deletada = 1 ";
		$where = " id = $id";
	
		return $this->oDB->update("invoices", $field_values, $where);
	}	
	
	protected function getFileDocsDB($arr_args){
		
		$invoice_id = $arr_args['id'];
		$doc_type   = $arr_args['doc_type'];
		$file_md5   = $arr_args['code'];
		
		$query = "SELECT * FROM invoices_docs WHERE local_name_md5 = '$file_md5' AND id_invoice = $invoice_id AND doc_type = $doc_type";
		
		$result = $this->oDB->select($query);
		return $this->oSupport->transcriberToList($result);
	}
	
	protected function getScoresDB($field)
	{
		switch ($field)
		{
			case 'total':
				$query = "SELECT id FROM invoices WHERE deletada = 0";
				break;
			case 'pagas_ok':
				$query = "SELECT * FROM invoices WHERE deletada = 0 AND status_id = 1;";
				break;
			case 'pagas_no':
				$query = "SELECT * FROM invoices WHERE deletada = 0 AND status_id = 2;";
				break;
			case 'a_vencer':
				date_default_timezone_set("America/Sao_Paulo");
				$dataNow = date("Y-m-d");
				
				$query = "SELECT * FROM invoices WHERE deletada = 0 AND status_id = 2 AND data_vencimento < '$dataNow';";
				break;
		}		
		
		$result = $this->oDB->select($query);
		return $result->num_rows;
	}
	
}
?>