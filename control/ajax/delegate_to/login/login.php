<?php

	if( !$_POST['lang'] || !$_POST['email'] ||  !$_POST['senha'] ) {		
		print json_encode( array('transaction' => 'NO', 'msg' => 'Digite o email e a senha.' ) );
		die();
	}
		
	require_once ( dirname(__FILE__) . "/../../Classes/login/Login.php");
	$oLogin = new Login ();
	
	require_once ( dirname(__FILE__) . "/../../Classes/configs/Configs.php");
	$oConfigs = new Configs();
	$oConfigs->setLanguage($_POST['lang']);
		
	$arr_args = array(
			'id_usuario' => "",
			'lang'  => $_POST ['lang'],			
			'email' => $_POST ['email'],
			'senha' => $_POST ['senha']
	);
	
	print json_encode( $oLogin->logar($arr_args) );
?>