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
	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/user/register/Delete.php");
	$oUserDelete = new UserDelete();
	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValiacoes = new Validacoes();
	
	//ERROS:
	$cache_html = "";
	$error = false;
	
	
	if(!$oUserDelete->ver_permissao_usuario($_POST['id_usuario'],$_POST['code_auth_user'] ) ) {
		$cache_html .= "Erro de integridade. Contacte o administrador do sistema.<br>";
		$error = true;
	}
	
	if($oUserDelete->ver_permissao_usuario($_POST['id_usuario'],$_POST['code_auth_user']) != 1 ) {
		$cache_html .= "Usuário do sistema não possui permissão para esta operação.<br>";
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
	
	if($_POST['id_delete'] && !is_numeric($_POST['id_delete'])) {
		$cache_html .= "Usuário não consta na base de dados.<br>";
		$error = true;
	}
	
	if($code_auth && !$oUserDelete->validar_delete_usuario($_POST['id_delete'], $_POST['code_auth'])) {
		$cache_html .= "Problemas de autenticacao, favor contactar seu administrador.<br>";
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
	
	$result = $oUserDelete->deleteUser($_POST['id_delete']);
	
	if ($result) {
		$arr = array('transaction' => 'OK', 'msg' => $oConfigs->get('cadastro_usuario','delete_usuario_sucesso') );			
	} else {
		$arr = array('transaction' => 'NO', 'msg' => $oConfigs->get('cadastro_usuario','erro_delete_usuario') );		
	}
	
	print json_encode($arr);
	return;
	
?>