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
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/companies/Comp.php");
	$oComp = new Comp();
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValiacoes = new Validacoes();
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/user/User.php");
	$oUser = new User();
	
	//ERROS:
	$cache_html = "";
	$error = false;
	
	
	if(!$oUser->userPermission( $_POST['id_usuario'].'$H' , $_POST['code_user'] ) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','erro_integridade').".<br>";
		$error = true;
	}
	
	if(!$oUser->userPermission( $_POST['id_usuario'] . '&@' , $_POST['code_edit'] ) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','erro_integridade').".<br>";
		$error = true;
	}
	
	if( !$_POST['id_usuario'] || !is_numeric($_POST['id_usuario'])) {
		$cache_html .= $oConfigs->get('cadastro_companies','usuario_nao_logado') . "<br>";
		$error = true;
	}
		
	if( !$_POST['lang'] ) {
		$cache_html .= $oConfigs->get('cadastro_companies','lingua_usu_indefinida') . "<br>";
		$error = true;		
	}

	if( !$_POST['emp_id'] ) {
		$cache_html .= $oConfigs->get('cadastro_companies','emp_nao_encontrada') . "<br>";
		$error = true;
	}
	
	if( !$_POST['emp_nome'] ) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_nome') . "<br>";
		$error = true;
	} 
		
	if( !$_POST['emp_end'] ) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_end') . "<br>";
		$error = true;
	}
	
	if( !$_POST['emp_cidade']) {		
		$cache_html .= $oConfigs->get('cadastro_companies','dig_cidade') . "<br>";
		$error = true;
	}

	if( !$_POST['emp_estado']) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_estado') . "<br>";
		$error = true;
	}
	
	if( !$_POST['emp_pais']) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_pais') . "<br>";
		$error = true;
	}

	if( !$_POST['emp_tel_p']) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_tel_p') . "<br>";
		$error = true;
	}

	if( !$_POST['emp_email']) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_email') . "<br>";
		$error = true;
	}

	if( $_POST['emp_email'] && !$oValiacoes->validaMail($_POST['emp_email']) ) {
		$cache_html .= $oConfigs->get('cadastro_companies','email_invalido') . "<br>";
		$error = true;
	}	
	
	if( !$_POST['emp_site']) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_site') . "<br>";
		$error = true;
	}

	if( $_POST['emp_site'] && !$oValiacoes->isURL($_POST['emp_site']) ) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_site_correto') . "<br>";
		$error = true;
	}

	if( $_POST['emp_link_map'] && !$oValiacoes->isURL($_POST['emp_link_map']) ) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_maps_correto') . "<br>";
		$error = true;
	}	
	
	if($error) {
		$cache_html = $oConfigs->get('cadastro_companies','erros_encontrados') . "<br><br>" . $cache_html;
		$arr = array('transaction' => 'NO', 'msg' => $cache_html );
		print json_encode($arr);
		return;
	}
		
	$arr_args = array(
			'id_usuario'       => $_POST['id_usuario'],
			'lang'             => $_POST['lang'],
			
			'emp_id'          => $_POST['emp_id'],
			'emp_nome'        => $_POST['emp_nome'],
			'emp_cnpj_id'     => $_POST['emp_cnpj_id'],
			'emp_end'         => $_POST['emp_end'],
			'emp_cidade'      => $_POST['emp_cidade'],
			'emp_estado'      => $_POST['emp_estado'],
			'emp_pais'        => $_POST['emp_pais'],
			'emp_tel_p'       => $_POST['emp_tel_p'],
			'emp_tel_s'       => $_POST['emp_tel_s'],
			'emp_email'       => $_POST['emp_email'],
			'emp_site'        => $_POST['emp_site'],
			'emp_link_map'    => $_POST['emp_link_map'],
			'emp_nome_prop'   => $_POST['emp_nome_prop'],
			'emp_comentarios' => $_POST['emp_comentarios'],
			'ativo'           => 1
	);
	
	$cache_html = "";
	
	$return = $oComp->update($arr_args);
	
	if ($return) {
		$cache_html .= $oConfigs->get('cadastro_companies','alteracao_sucesso') . "<br>";
		$arr = array('transaction' => 'OK', 'msg' => $cache_html );
		
	} else {
		$cache_html .= $oConfigs->get('cadastro_companies','erro_ao_alterar') . "<br>";
		$arr = array('transaction' => 'NO', 'msg' => $cache_html );
	}
	
	//USAR PRINT COMO RETORNO DE FUNCAO
	print json_encode($arr);
	return;
?>