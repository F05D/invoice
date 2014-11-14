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
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/user/User.php");
	$oUser = new User();
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValiacoes = new Validacoes();
	
	//ERROS:
	$cache_html = "";$error = false;
	
	if(!$oUser->userPermission($_POST['id_usuario'].'*%',$_POST['code_user']) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','erro_integridade') . "<br>";
		$error = true;
	}
	
	if(!$oUser->userPermission($_POST['id_alter'].'#!',$_POST['code_edit'] ) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','erro_integridade') . "<br>";
		$error = true;
	}
	
	if( !$_POST['id_usuario'] || !is_numeric($_POST['id_usuario'])) {
		$cache_html .= $oConfigs->get('cadastro_usuario','usuario_nao_logado') . "<br>";
		$error = true;
	}
	
	if( !$_POST['id_alter'] || !is_numeric($_POST['id_alter'])) {
		$cache_html .= $oConfigs->get('cadastro_usuario','erro_usuario_localizar') . "<br>";
		$error = true;
	}
		
	if( !$_POST['lang'] ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','lingua_usu_indefinida') . "<br>";
		$error = true;		
	}

	if( !$_POST['user_nome'] ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','dig_nome_usua') . "<br>";
		$error = true;
	} 
		
	if( !$_POST['user_lang'] ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','def_lingua_usu') . "<br>";
		$error = true;
	}
	
	if( !$_POST['user_dt_nasc']) {		
		$cache_html .= $oConfigs->get('cadastro_usuario','dig_data_nasc') . "<br>";
		$error = true;
	}

	if($_POST['user_dt_nasc'] && !$oValiacoes->validaDataFormato($_POST['user_dt_nasc'], $_POST['user_lang'])) {
		$cache_html .= $oConfigs->get('cadastro_usuario','dt_nasc_invalida') . "<br>";
		switch ($_POST['user_lang']) {
			case "pt":
			case "sp":
			default  :
				$cache_html .= $oConfigs->get('cadastro_usuario','form_dt_pt_sp_adequado') . "<br>";
				break;
			case "en":
				$cache_html .= $oConfigs->get('cadastro_usuario','form_dt_en_adequado') . "<br>";
				break;
		}
			
		$error = true;
	}
	
	if( !$_POST['user_email']) {
		$cache_html .= $oConfigs->get('cadastro_usuario','dig_email') . "<br>";
		$error = true;
	}
	
	if( $_POST['user_email'] && !$oValiacoes->validaMail($_POST['user_email']) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','email_invalido') . "<br>";
		$error = true;
	}
	
	if( $_POST['user_email'] && $oUser->verificaUserInDB( $_POST['user_email'] , $_POST['id_alter'] )) {
		$cache_html .= $oConfigs->get('cadastro_usuario','email_ja_existente') . "<br>";
		$error = true;
	}
	
	if( !$_POST['user_senha']) {
		$cache_html .= $oConfigs->get('cadastro_usuario','dig_senha') . "<br>";
		$error = true;
	}
	
	if( $_POST['user_senha'] && !$oValiacoes->validaSenha($_POST['user_senha']) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','formato_senha') . "<br>";
		$error = true;
	}
	
	if( $_POST['user_senha'] && $_POST['user_senha'] != $_POST['user_senha_ver']) {		
		$cache_html .= $oConfigs->get('cadastro_usuario','senhas_identicas') . "<br>";
		$error = true;
	}	
	
	if( !$_POST['user_dica']) {
		$cache_html .= $oConfigs->get('cadastro_usuario','dig_dica_senha') . "<br>";
		$error = true;		
	}
	
	if( !$_POST['user_privilegio']) {
		$cache_html .= $oConfigs->get('cadastro_usuario','sel_privilegios') . "<br>";
		$error = true;		
	}
		
	if( $_POST['user_privilegio'] > 1 && !$_POST['user_empresa']) {
		$cache_html .= $oConfigs->get('cadastro_usuario','user_pert_empresa') . "<br>";	
		$error = true;		
	}
	
	if($error) {
		$cache_html = $oConfigs->get('cadastro_usuario','erros_encontrados') . "<br><br>" . $cache_html;
		$arr = array('transaction' => 'NO', 'msg' => $cache_html );
		print json_encode($arr);
		die();
	}
	
	$code_validacao_email = md5( $oUser->getCodeSecurity($_POST['user_senha']) );
	
	$arr_args = array(
			'id_usuario' => $_POST['id_usuario'],
			'lang'       => $_POST['lang'],
			
			'id_alter'        => $_POST['id_alter'],
			'user_nome'       => $_POST['user_nome'],
			'user_dt_nasc'    => $oValiacoes->convertDataToDB($_POST['user_dt_nasc'], $_POST['user_lang']),
			'user_email'      => $_POST['user_email'],
			'user_senha'      => $_POST['user_senha'],
			'user_senha_ver'  => $_POST['user_senha_ver'],
			'user_dica'       => $_POST['user_dica'],
			'user_privilegio' => $_POST['user_privilegio'],
			'user_empresa'    => $_POST['user_empresa'],
			'user_lang'       => $_POST['user_lang'],
			
			'code_validacao_email' => $code_validacao_email,
			'code_validado' => 1,
			'is_new' =>  0,
			'ativo' =>  1
	);
	$cache_html = "";
	
	$return = $oUser->update($arr_args);
	
	if ($return['result_update_user'])
		$arr = array('transaction' => 'OK', 'msg' => $oConfigs->get('cadastro_usuario','alter_sucesso') . "<br>" );
		
	else
		$arr = array('transaction' => 'NO', 'msg' => $oConfigs->get('cadastro_usuario','nao_foi_possivel_alterar') );	
	
	//USAR PRINT COMO RETORNO DE FUNCAO
	print json_encode($arr);
	return;
?>