<?php
	
	if($_SERVER['DOCUMENT_ROOT'] == "/Library/WebServer/Sites") {
		$local_root = $_SERVER['DOCUMENT_ROOT'];
		$local_simbolic = "/invoice";
	} else {
		$local_root = $_SERVER['DOCUMENT_ROOT'];
		$local_simbolic = "";
	}
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/login/Login.php");
	$oLogin = new Login ();
	
	require_once ( $local_root . $local_simbolic . "/control/ajax/Classes/configs/Configs.php");
	$oConfigs = new Configs();
	$oConfigs->setLanguage($_POST['lang'], FALSE);
	
	if( !$_POST['lang'] || !$_POST['email'] ) {
		print json_encode( array('transaction' => 'NO', 'msg' => 'Digite o email no campo acima.' ) );
		die();
	}
	
	// LOGIN	
	$arr_args = array(
			'id_usuario' => "",
			'lang'  => $_POST ['lang'],			
			'email' => $_POST ['email']
			
	);
	
	//USAR PRINT COMO RETORNO DE FUNCAO
	print json_encode( $oLogin->reenviar($arr_args, $oConfigs) );
?>






