<?php
	

	//TRADUCAO DOS TEXTOS:
	require_once ( dirname(__FILE__) . "/../../Classes/configs/Configs.php");
	$oConfigs = new Configs();
	$oConfigs->setLanguage($_POST['lang'], false);
	
	require_once ( dirname(__FILE__) . "/../../Classes/user/register/Create.php");
	$oUserCreate = new UserCreate();
	
	require_once ( dirname(__FILE__) . "/../../Classes/common/Validacoes.php");
	$oValiacoes = new Validacoes();
	
	//ERROS:
	$cache_html = "";
	$error = false;
	
	
	if( !$_POST['id_usuario'] && !is_numeric($_POST['id_usuario'])) {
		$cache_html .= $oConfigs->get('cadastro_usuario','usuario_nao_logado') . "<br>";
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

	if($_POST['user_dt_nasc'] && !$oValiacoes->valida_dataFormato($_POST['user_dt_nasc'], $_POST['user_lang'])) {
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
	
	if( $_POST['user_email'] && !$oValiacoes->valida_mail($_POST['user_email']) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','email_invalido') . "<br>";
		$error = true;
	}
	
	if( $_POST['user_email'] && $oUserCreate->verifica_mail_na_base_usado($_POST['user_email']) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','email_ja_existente') . "<br>";
		$error = true;
	}
	
	if( !$_POST['user_senha']) {
		$cache_html .= $oConfigs->get('cadastro_usuario','dig_senha') . "<br>";
		$error = true;
	}
	
	if( $_POST['user_senha'] && !$oValiacoes->valida_senha($_POST['user_senha']) ) {
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
	
	//TODO:COLOCAR A EMPRESA
	//if( !$_POST['user_empresa']) {
	//	$cache_html .= "Usuário não logado." . "<br>";
	//	$error = true;		
	//}
	
	if($error) {
		$cache_html = $oConfigs->get('cadastro_usuario','erros_encontrados') . "<br><br>" . $cache_html;
		$arr = array('transaction' => 'NO', 'msg' => $cache_html );
		print json_encode($arr);
		return;
	}
	
	
	
	$code_validacao_email = md5( $oUserCreate->makePINCODEValidation(5) );
	
	$arr_args = array(
			'id_usuario' => $_POST['id_usuario'],
			'lang'       => $_POST['lang'],
			
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
			'code_validado' => 0,
			'is_new' =>  1,
			'ativo' =>  0
	);
	$cache_html = "";
	
	$return = $oUserCreate->createUser($arr_args);
	
	if ($return) {
		$cache_html .= $oConfigs->get('cadastro_usuario','criacao_sucesso') . "<br>";
		
		require_once ( dirname(__FILE__) . "/../../Classes/common/Emails.php");
		$oEmails = new Emails();

		$arr_envio = $oEmails->send_email_para_validacao($_POST['user_email'],$code_validacao_email);
		
		if($arr_envio['transaction']) {
			
			$cache_html .= 'URL: '.$arr_envio['url'] . "<br>";
			$cache_html .= "Email enviado para ".$_POST['user_email']." para validação." . "<br>";
			$cache_html .= $oConfigs->get('cadastro_usuario','email_envi_para_validacao') . " " . "<br>";
			$cache_html .= $_POST['user_email'];
			
			$arr = array('transaction' => 'OK', 'msg' => $cache_html );
			
		} else {
			$cache_html .= 'URL: '.$arr_envio['url'] . "<br>";
			$cache_html .= $oConfigs->get('cadastro_usuario','email_nao_enviado_validacao') . " '".$_POST['user_email'] . "'.<br>";			
			$cache_html .= $oConfigs->get('cadastro_usuario','verifique_adm_do_sistema');
			
			$arr = array('transaction' => 'NO', 'msg' => $cache_html ); 
		}
		
	} else {
		$cache_html .= $oConfigs->get('cadastro_usuario','erro_ao_editar') . "<br>";
		$cache_html .= $oConfigs->get('cadastro_usuario','verifique_adm_do_sistema');
		
		$arr = array('transaction' => 'NO', 'msg' => $cache_html );	
	}
	
	//USAR PRINT COMO RETORNO DE FUNCAO
	print json_encode($arr);
	return;
?>