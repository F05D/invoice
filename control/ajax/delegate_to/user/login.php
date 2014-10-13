<?php

	if( !$_POST['lang'] || !$_POST['email'] ||  !$_POST['senha'] ) 
		return "";

	//CLASSE USUARIO
	require_once ( dirname(__FILE__) . "/../../Classes/user/User.php");
	$oUser = new User ();
	
	require_once ( dirname(__FILE__) . "/../../Classes/configs/Configs.php");
	$oConfigs = new Configs();
	$oConfigs->setLanguage($_POST['lang']);
	
	// LOGIN	
	$arr_args = array(
			'id_usuario' => "",
			'lang'  => $_POST ['lang'],			
			'email' => $_POST ['email'],
			'senha' => $_POST ['senha']
	);
	
	//USAR PRINT COMO RETORNO DE FUNCAO
	
	$return = $oUser->logar($arr_args);
	
	if($return)
		print "lang=". $_POST ['lang'] . "&code=" . $return;
	else 
		print "";
	
	
	

?>