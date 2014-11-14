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
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/user/User.php");
	$oUser = new User();
	
	//ERROS:
	$cache_html = "";
	$error = false;
	
	
	if(!$oUser->getCodeSecurity( $_POST['id_usuario'] . "$!@u*" , $_POST['code_user'] ) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','erro_integridade').".<br>";
		$error = true;
	}
	
	if(!$oUser->getCodeSecurity( $_POST['id_delete'] . "&%h@" , $_POST['code_delete']) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','erro_integridade').".<br>";
		$error = true;
	}
	
	if(!$_POST['id_delete']) {
		$cache_html .= $oConfigs->get('cadastro_companies','erro_id_inesistente').".<br>";
		$error = true;
	}
	
	if($_POST['id_delete'] && !is_numeric($_POST['id_delete'])) {
		$cache_html .= $oConfigs->get('cadastro_companies','erro_id_inesistente').".<br>";
		$error = true;
	}
	
	if( !$_POST['id_usuario'] && !is_numeric($_POST['id_usuario'])) {
		$cache_html .= $oConfigs->get('cadastro_usuario','erro_integridade').".<br>";
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
	
	$result = $oComp->delete($_POST['id_delete']);
	
	if ($result) {
		$arr = array('transaction' => 'OK', 'msg' => $oConfigs->get('cadastro_usuario','delete_usuario_sucesso') );			
	} else {
		$arr = array('transaction' => 'NO', 'msg' => $oConfigs->get('cadastro_usuario','erro_delete_usuario') );		
	}
	
	print json_encode($arr);
	return;
	
?>