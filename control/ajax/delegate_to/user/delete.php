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
	$oConfigs->setLanguage($_POST['lang'], false);
		
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/user/User.php");
	$oUser = new User();
	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValiacoes = new Validacoes();
	
	
	//ERROS:
	$cache_html = ""; $error = false;
	
	
	if(!$oUser->userPermission($_POST['id_usuario'],$_POST['code_user']) ) {
		$cache_html .= "Erro de integridade. Contacte o administrador do sistema.<br>";
		$error = true;
	}
	
	if(!$oUser->userPermission($_POST['id_delete'],$_POST['code_delete'] ) ) {
		$cache_html .= "Erro de integridade. Contacte o administrador do sistema.<br>";
		$error = true;
	}
	
	if($_POST['id_delete'] == $_POST['id_usuario']) {
		$cache_html .= "Não é possível deletar seu próprio usuário.<br>";
		$error = true;
	}
	
	if(!$_POST['id_delete']) {
		$cache_html .= "Impossível deletar usuário inesistente.<br>";
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

	$result = $oUser->delete($_POST['id_delete']);
	
	if ($result) {
		$arr = array('transaction' => 'OK', 'msg' => $oConfigs->get('cadastro_usuario','delete_usuario_sucesso') );			
	} else {
		$arr = array('transaction' => 'NO', 'msg' => $oConfigs->get('cadastro_usuario','erro_delete_usuario') );		
	}
	
	print json_encode($arr);
	die();
	
?>