<?php

	//LOCALIZACAO DE ARQUIVOS {
	if($_SERVER['DOCUMENT_ROOT'] == "/Library/WebServer/Sites") {
		$local_root = $_SERVER['DOCUMENT_ROOT'];
		$local_simbolic = "/invoice";
	} else {
		$local_root = $_SERVER['DOCUMENT_ROOT'];
		$local_simbolic = "";
	}
	//LOCALIZACAO DE ARQUIVOS }

	//Corrige problemas de caracteres que quebram a inclusao no DB
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/common/Support.php");
	$oSupport = new Support();
	$_POST = $oSupport->arrAddslashes($_POST);
	
	//TRADUCAO DOS TEXTOS:
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/configs/Configs.php");
	$oConfigs = new Configs();
	$oConfigs->setLanguage($_POST['lang'], false);
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/invoice/Invoice.php");
	$oInvoice = new Invoice();
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValiacoes = new Validacoes();
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/user/User.php");
	$oUser = new User();
	
	//ERROS:
	$cache_html = "";
	$error = false;
	

	if(!$oUser->userPermission( $_POST['id_usuario']."_D%F%",$_POST['code_user']) ) {
		$cache_html .= "Erro de integridade. Contacte o administrador do sistema.<br>";
		$error = true;
	}
	
	if(!$oUser->userPermission( $oUser->getLastId() . "g$@)" ,$_POST['code_create'] ) ) {
		$cache_html .= "Erro de integridade. Clique no botao 'Cadastrar' novamente.<br>";
		$error = true;
	}
	
	//PASSO PARA VALIDACAO
	if($_POST['step'] == 'create') {
	
		if( !$_POST['id_usuario'] || !is_numeric($_POST['id_usuario'])) {
			$cache_html .= $oConfigs->get('cadastro_invoice','usuario_nao_logado') . "<br>";
			$error = true;
		}
			
		if( !$_POST['lang'] ) {
			$cache_html .= $oConfigs->get('cadastro_invoice','lingua_usu_indefinida') . "<br>";
			$error = true;		
		}
	
		if( !$_POST['invoice_nr'] ) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_nu_invoice') . "<br>";
			$error = true;
		} 
			
		if( !$_POST['invoice_fatura_n'] ) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_nu_fatura') . "<br>";
			$error = true;
		}
		
		if( !$_POST['invoice_fatura_valor']) {		
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_valor_fatura') . "<br>";
			$error = true;
		}
		
		if( $_POST['invoice_fatura_valor'] && !is_numeric($_POST['invoice_fatura_valor'])) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_valor_fatura_numerico') . "<br>";
			$error = true;
		}
	
		if( !$_POST['invoice_data_vencimento']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_data_venc') . "<br>";
			$error = true;
		}
		
		if($_POST['invoice_data_vencimento'] && !$oValiacoes->validaDataFormato($_POST['invoice_data_vencimento'], $_POST['lang'])) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dt_fatura_invalida') . ". ";
			switch ($_POST['lang']) {
				case "pt":
				case "sp":
				default  :
					$cache_html .= $oConfigs->get('cadastro_invoice','form_dt_pt_sp_adequado') . "<br>";
					break;
				case "en":
					$cache_html .= $oConfigs->get('cadastro_invoice','form_dt_en_adequado') . "<br>";
					break;
			}				
			$error = true;
		}
		
		if( !$_POST['invoice_empresa']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_empresa') . "<br>";
			$error = true;
		}

		if( !$_POST['invoice_banco']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_banco') . "<br>";
			$error = true;
		}				
		
		if( !$_POST['invoice_container']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_container') . "<br>";
			$error = true;
		}
	
		if( !$_POST['invoice_booking']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_booking') . "<br>";
			$error = true;
		}
	
		if( !$_POST['invoice_tipo']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_tipo') . "<br>";
			$error = true;
		}
		
		if( !$_POST['invoice_tara']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_tara') . "<br>";
			$error = true;
		}
		if( $_POST['invoice_tara'] && !is_numeric($_POST['invoice_tara'])) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_valor_tara_numerico') . "<br>";
			$error = true;
		}
		
		if( !$_POST['invoice_peso_bruto']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_peso_bruto') . "<br>";
			$error = true;
		}
		
		if( $_POST['invoice_peso_bruto'] && !is_numeric($_POST['invoice_peso_bruto'])) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_valor_peso_bruto_numerico') . "<br>";
			$error = true;
		}
		
		if( !$_POST['invoice_peso_liquido']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_peos_liq') . "<br>";
			$error = true;
		}
		if( $_POST['invoice_peso_liquido'] && !is_numeric($_POST['invoice_peso_liquido'])) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_valor_peso_liquido_numerico') . "<br>";
			$error = true;
		}

		
		if( $_POST['invoice_peso_bruto'] <= $_POST['invoice_peso_liquido']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','peso_bruto_menor_liquido') . "<br>";
			$error = true;
		}		
		
		if( !$_POST['invoice_qnt']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_qnt') . "<br>";
			$error = true;
		}
		if( $_POST['invoice_qnt'] && !is_numeric($_POST['invoice_qnt'])) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_valor_qnt_numerico') . "<br>";
			$error = true;
		}
		
		if( !$_POST['invoice_nota_fiscal']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_nota_fiscal') . "<br>";
			$error = true;
		}
		if( !$_POST['invoice_lacres']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_lacres') . "<br>";
			$error = true;
		}
		if( !$_POST['invoice_embarque_data']) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dig_data_embarque') . "<br>";
			$error = true;
		}
		if($_POST['invoice_embarque_data'] && !$oValiacoes->validaDataFormato($_POST['invoice_embarque_data'], $_POST['lang'])) {
			$cache_html .= $oConfigs->get('cadastro_invoice','dt_embarque_invalida') . ". ";
			switch ($_POST['lang']) {
				case "pt":
				case "sp":
				default  :
					$cache_html .= $oConfigs->get('cadastro_invoice','form_dt_pt_sp_adequado') . "<br>";
					break;
				case "en":
					$cache_html .= $oConfigs->get('cadastro_invoice','form_dt_en_adequado') . "<br>";
					break;
			}
			$error = true;
		}
		
		if( $_POST['invoice_embarque_confirmacao'] == "") {
			$cache_html .= $oConfigs->get('cadastro_invoice','cad_confirmacao') . "<br>";
			$error = true;
		}
		
		if( $_POST['docs_packinglist_size'] && $_POST['docs_packinglist_size'] > 2500000 ) {
			$cache_html .= $oConfigs->get('cadastro_invoice','packinglist_size_maior') . "<br>";
			$error = true;
		}		

		if( $_POST['docs_formei_size'] && $_POST['docs_formei_size'] > 2500000 ) {
			$cache_html .= $oConfigs->get('cadastro_invoice','formei_size_maior') . "<br>";
			$error = true;
		}

		if( $_POST['docs_fumigacao_size'] && $_POST['docs_fumigacao_size'] > 2500000 ) {
			$cache_html .= $oConfigs->get('cadastro_invoice','fumigacao_size_maior') . "<br>";
			$error = true;
		}
		
		if( $_POST['docs_bl_size'] && $_POST['docs_bl_size'] > 2500000 ) {
			$cache_html .= $oConfigs->get('cadastro_invoice','bl_size_maior') . "<br>";
			$error = true;
		}

		if( $_POST['docs_invoice_size'] && $_POST['docs_invoice_size'] > 2500000 ) {
			$cache_html .= $oConfigs->get('cadastro_invoice','invoice_size_maior') . "<br>";
			$error = true;
		}
		
		if( $_POST['docs_ISF_size'] && $_POST['docs_ISF_size'] > 2500000 ) {
			$cache_html .= $oConfigs->get('cadastro_invoice','ISF_size_maior') . "<br>";
			$error = true;
		}		
		
		if( 
			!$oValiacoes->isNum($_POST['docs_packinglist_lock_status']) ||
			!$oValiacoes->isNum($_POST['docs_formei_lock_status']) ||
			!$oValiacoes->isNum($_POST['docs_fumigacao_lock_status']) ||
			!$oValiacoes->isNum($_POST['docs_bl_lock_status']) ||
			!$oValiacoes->isNum($_POST['docs_invoice_lock_status']) ||
			!$oValiacoes->isNum($_POST['docs_ISF_lock_status'])
		) {
			$cache_html .= $oConfigs->get('cadastro_invoice','erro_integridade_lock') . "<br>";
			$error = true;
		}
		
		
		if($error) {
			$cache_html = $oConfigs->get('cadastro_invoice','erros_encontrados') . "<br><br>" . $cache_html;
			$arr = array('transaction' => 'NO', 'msg' => $cache_html );

		} else {
			$arr = array('transaction' => 'OK', 'msg' => '' );
		}
		
		print json_encode($arr);
		die();
	}
	
	//UPLOAD - -----------------
	$uploaddir = $local_root . $local_simbolic . '/uploads/';	
	$arrDocs = array();
	
	$arrDocs['docs_packinglist'] = $oInvoice->copyDoc( 
			$arrDocs['docs_packinglist'], 
			$_FILES['docs_packinglist'],
			$_POST['docs_packinglist_lock_status'],
			$uploaddir
	);
	
	$arrDocs['docs_formei'] = $oInvoice->copyDoc( 
			$arrDocs['docs_formei'], 
			$_FILES['docs_formei'], 
			$_POST['docs_formei_lock_status'],
			$uploaddir
	);
	
	$arrDocs['docs_fumigacao'] = $oInvoice->copyDoc(
			$arrDocs['docs_fumigacao'], 
			$_FILES['docs_fumigacao'], 
			$_POST['docs_fumigacao_lock_status'],
			$uploaddir
	);
	
	$arrDocs['docs_bl'] = $oInvoice->copyDoc( 
			$arrDocs['docs_bl'], 
			$_FILES['docs_bl'], 
			$_POST['docs_bl_lock_status'],
			$uploaddir
	);
	
	$arrDocs['docs_invoice'] = $oInvoice->copyDoc( 
			$arrDocs['docs_invoice'], 
			$_FILES['docs_invoice'], 
			$_POST['docs_invoice_lock_status'],
			$uploaddir
	);

	$arrDocs['docs_ISF'] = $oInvoice->copyDoc( 
			$arrDocs['docs_ISF'], 
			$_FILES['docs_ISF'], 
			$_POST['docs_ISF_lock_status'],
			$uploaddir
	);
		
	$arr_args = array(
			'id_usuario'      => $_POST['id_usuario'],
			'lang'            => $_POST['lang'],
			
			'invoice_nr'                   => $_POST['invoice_nr'],
			'invoice_fatura_n'             => $_POST['invoice_fatura_n'],
			'invoice_fatura_valor'         => $_POST['invoice_fatura_valor'],
			'invoice_data_vencimento'      => $_POST['invoice_data_vencimento'],
			'invoice_empresa'              => $_POST['invoice_empresa'],
			'invoice_container'            => $_POST['invoice_container'],
			'invoice_booking'              => $_POST['invoice_booking'],
			'invoice_tipo'                 => $_POST['invoice_tipo'],
			'invoice_tara'                 => $_POST['invoice_tara'],
			'invoice_peso_bruto'           => $_POST['invoice_peso_bruto'],
			'invoice_peso_liquido'         => $_POST['invoice_peso_liquido'],
			'invoice_qnt'                  => $_POST['invoice_qnt'],
			'invoice_nota_fiscal'          => $_POST['invoice_nota_fiscal'],
			'invoice_lacres'               => $_POST['invoice_lacres'],
			'invoice_embarque_data'        => $_POST['invoice_embarque_data'],
			'invoice_embarque_confirmacao' => $_POST['invoice_embarque_confirmacao'],
			'invoice_banco'                => $_POST['invoice_banco'],
			'arrDocs'                      => $arrDocs
			

	);
	
	$cache_html = "";	
	$return = $oInvoice->create($arr_args);
	
	if ($return['transaction'] == 'OK') {
		$cache_html .= $oConfigs->get('cadastro_invoice','cadastro_sucesso');
		$arr = array('transaction' => 'OK', 'msg' => $cache_html );
		
	} else {
		$cache_html .= $oConfigs->get('cadastro_invoice','erro_ao_cadastrar') . "<br>";
		$cache_html .= $oConfigs->get('cadastro_invoice','erro_na_etapa') . ":" .$return['step'] . "<br>";
		$cache_html .= $oConfigs->get('cadastro_invoice','verifique_adm_do_sistema');		
		$arr = array('transaction' => 'NO', 'msg' => $cache_html );
	}
	
	//USAR PRINT COMO RETORNO DE FUNCAO
	print json_encode($arr);
	return;
?>