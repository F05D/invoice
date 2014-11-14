<?php


	require_once ( dirname(__FILE__) . "/../../Classes/configs/Configs.php");
	$oConfigs = new Configs();
	$oConfigs->setLanguage($_POST['lang']);
	
	require_once ( dirname(__FILE__) . "/../../Classes/user/User.php");
	$oUser = new User();
	
	require_once ( dirname(__FILE__) . "/../../Classes/common/Validacoes.php");
	$oValiacoes = new Validacoes();
	
	//ERROS:
	$cache_html = "";
	$error = false;
	$arr = array();
	
	if( !$_POST['id_usuario'] || !is_numeric($_POST['id_usuario'])) {
		$cache_html .= $oConfigs->get('cadastro_usuario','usuario_nao_logado') . "<br>";
		$error = true;
	}
		
	if( !$_POST['lang'] ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','lingua_usu_indefinida') . "<br>";
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
	
	if( !$_POST['code_auth'] || !$oUser->verificaAuthTrocaSenha($_POST['code_auth'],$_POST['id_usuario']) ) {
		$cache_html .= $oConfigs->get('cadastro_usuario','erro_integridade') . "<br>";
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
	
	
	$arr_args = array(
			'id_usuario' => $_POST['id_usuario'],
			'lang'       => $_POST['lang'],
			
			'user_email'      => $_POST['user_email'],
			'user_senha'      => $_POST['user_senha'],
			'user_dica'       => $_POST['user_dica'],
			'code_validado' => 1,
			'is_new' =>  0
	);

	//UPDATE DA SENHA E DICA DE SENHA
	$result = $oUser->troca_senha_e_logon($arr_args) ;
	
	if ($result) {
		
		$pingerado = false;
		$arr_args = null;
		$arr_args = array(
				'id_usuario' => $_POST['id_usuario'],
				'lang'       => $_POST['lang'],
				'email'      => $_POST['user_email'],
				'senha'      => $_POST['user_senha']
		);
		
		//NOVO PIN GERADO PARA 
		$pingerado = $oUser->logar($arr_args);
		
		if($pingerado) {

			$url_code = "lang=". $_POST ['lang'] . "&code=" . $pingerado;
			
			//ENVIA EMAIL COM OS DADOS DE USUARIO E NOVA SENHA PARA USUARIO
			require_once ( dirname(__FILE__) . "/../../Classes/common/Emails.php");
			$oEmails = new Emails();
			
			if($oEmails->send_email_nova_senha($arr_args['email'], $arr_args['senha']) ) {
				$arr = array(
					'transaction' => 'OK', 
					'msg' => $url_code,
					'email_status' => 'OK',
					'email_msg' => $oConfigs->get('login','email_envi_suc')
				);				

			} else {
				$arr = array(
					'transaction' => 'OK',
					'msg' => $url_code,
					'email_status' => 'NO',
					'email_msg' => $oConfigs->get('login','email_prob_enviar')						
				);
			}
			
		} else {
			$arr = array(
				'transaction' => 'NO',
				'msg' => $oConfigs->get('login','erro_tentar_logar'),
				'email_status' => 'NO',
				'email_msg' => $oConfigs->get('login','email_prob_enviar')					
			);
		}
	
	} else {
		$arr = array(
			'transaction' => 'NO', 
			'msg' => $oConfigs->get('login','email_imposs_enviar'),
			'email_status' => 'NO',
			'email_msg' => $oConfigs->get('login','email_prob_enviar')
		);
	}
	
	print json_encode($arr,JSON_PRETTY_PRINT);
	return;	

?>