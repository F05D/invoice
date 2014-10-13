<?php
	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/configs/Configs.php");
	$oConfigs = new Configs();
	$oConfigs->setLanguage($_POST['lang']);
	
	if( !$_POST['lang'] || !$_POST['email'] ) {
		print $oConfigs->get('login', 'digite_email_reenviar');
		return 0;
	}
	
	//CLASSE USUARIO
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/user/User.php");
	$oUser = new User ();
	

	// LOGIN	
	$arr_args = array(
			'id_usuario' => "",
			'lang'  => $_POST ['lang'],			
			'email' => $_POST ['email']
			
	);
	
	//USAR PRINT COMO RETORNO DE FUNCAO
	print $oUser->reenviar_senha($arr_args, $oConfigs);
?>