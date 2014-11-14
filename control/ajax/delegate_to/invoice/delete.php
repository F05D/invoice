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

	//TRADUCAO DOS TEXTOS:
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/configs/Configs.php");
	$oConfigs = new Configs();
	$oConfigs->setLanguage($_POST['lang']);
	
	require_once ( dirname(__FILE__) . "/../../Classes/companies/Comp.php");
	$oComp = new Comp();
		
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValiacoes = new Validacoes();
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/common/Emails.php");
	$oEmails = new Emails();
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/user/User.php");
	$oUser = new User();
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/invoice/Invoice.php");
	$oInvoice = new Invoice();
	
	//ERROS:
	$cache_html = "";
	$error = false;
		
	if(!$oUser->getCodeSecurity( $_POST['id_usuario'] . "%#!@X*" , $_POST['code_user'] ) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','erro_integridade').".<br>";
		$error = true;
	}
	
	if(!$oUser->getCodeSecurity( $_POST['id_delete'] . "&OX%h$" , $_POST['code_delete']) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','erro_integridade').".<br>";
		$error = true;
	}
	
	if(!$_POST['id_delete']) {		
		$cache_html .=  $oConfigs->get('cadastro_invoice','del_inv_inesitente') . ".<br>";
		$error = true;
	}
	
	if($_POST['id_delete'] && !is_numeric($_POST['id_delete'])) {
		$cache_html .=  $oConfigs->get('cadastro_invoice','del_inv_inesitente') . ".<br>";
		$error = true;
	}
	
	if( !$_POST['id_usuario'] && !is_numeric($_POST['id_usuario'])) {
		$cache_html .= $oConfigs->get('cadastro_usuario','usuario_nao_logado') . "<br>";
		$error = true;
	}
		
	if( !$_POST['lang'] ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','lingua_usu_indefinida') . "<br>";
		$error = true;		
	}

	if($error) {
		$cache_html = $oConfigs->get('cadastro_usuario','erros_encontrados') . "<br>" . $cache_html;
		$arr = array('transaction' => 0, 'msg' => $cache_html );
		print json_encode($arr);
		return;
	}
	
	$result = $oInvoice->delete($_POST['id_delete']);
	
	if ($result) {
		
		$arr_args = $oInvoice->get($_POST['id_delete'])[0];
		$arr_args['id_usuario'] = $_POST['id_usuario'];
		$emailResult = $oEmails->notificacaoInvoice('delete', $oUser, $oComp, $oConfigs, $arr_args);
				
		$cache_html .= $oConfigs->get('cadastro_invoice','email_enviado') .": ". $emailResult . "\n";
		$cache_html .= $oConfigs->get('cadastro_usuario','delete_usuario_sucesso');
		$arr = array('transaction' => 'OK', 'msg' => $cache_html );		
			
	} else {
		$arr = array('transaction' => 'NO', 'msg' => $oConfigs->get('cadastro_usuario','erro_delete_usuario') );		
	}
	
	print json_encode($arr);
	return;
	
?>