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
	$_GET = $oSupport->arrAddslashes($_GET);
	
	//TRADUCAO DOS TEXTOS:
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/configs/Configs.php");
	$oConfigs = new Configs();
	$oConfigs->setLanguage($_GET['lang'], false);
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/invoice/Invoice.php");
	$oInvoice = new Invoice();
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValiacoes = new Validacoes();
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/user/User.php");
	$oUser = new User();
	
	//ERROS:
	$cache_html = "";
	$error = false;
	
	
	if(!$oUser->userPermission( $_GET['id_usuario'] . "%34DsX9" , $_GET['code_user']) ) {
		$cache_html .= "Erro de integridade. Contacte o administrador do sistema.<br>";
		$error = true;
	}
	
	if(!$oUser->userPermission( '%34DsX9' . $_GET['code']  ,$_GET['code_download'] ) ) {
		$cache_html .= "Erro de integridade. Clique no botao 'Cadastrar' novamente.<br>";
		$error = true;
	}
	
	if( !$_GET['id_usuario'] || !is_numeric($_GET['id_usuario'])) {
		$cache_html .= $oConfigs->get('cadastro_invoice','usuario_nao_logado') . "<br>";
		$error = true;
	}
		
	if( !$_GET['lang'] ) {
		$cache_html .= $oConfigs->get('cadastro_invoice','lingua_usu_indefinida') . "<br>";
		$error = true;		
	}

	if($error) {
		$cache_html = $oConfigs->get('cadastro_invoice','erros_encontrados') . "<br><br>" . $cache_html;
		$arr = array('transaction' => 'NO', 'msg' => $cache_html );
		print json_encode($arr);
		die();
	}
	
	
	$arr_args = array(
			'id_usuario'      => $_GET['id_usuario'],
			'lang'            => $_GET['lang'],
			
			'id'        => $_GET['id'],						
			'doc_type'  => $_GET['type'],
			'code'      => $_GET['code']
	);
	
	$return_file = array();
	$return_file = $oInvoice->downloadFile($arr_args)[0];
	
	$file = $local_root . $local_simbolic . "/uploads/" . $return_file['local_name_md5'];
	$name = $local_root . $local_simbolic . "/uploads/" . $return_file['name'];
	
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.basename( $name ));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	ob_clean();
	flush();
	readfile($file);
	exit;	
?>