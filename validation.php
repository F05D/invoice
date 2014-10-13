<?php
session_start();


//LOCALIZACAO DE ARQUIVOS {
if($_SERVER['DOCUMENT_ROOT'] == "/Library/WebServer/Sites") {
	$local_root = $_SERVER['DOCUMENT_ROOT'];
	$local_simbolic = "/invoice"; 
} else {
	$local_root = $_SERVER['DOCUMENT_ROOT'];
	$local_simbolic = "";	
}
//LOCALIZACAO DE ARQUIVOS }

//CONFIGURACOES E TRADUCOES
require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/configs/Configs.php");
$oConfigs = new Configs();


//DETECTA O DEVICE UTILIZADO
//$mDetect->isMobile();
//$mDetect->isTablet();
require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Mobile_detect.php");
$mDetect = new Mobile_Detect ();


//VERIFICA AUTENCIDADE DO USUARIO
//CLASSE USUARIO
require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/user/User.php");
$oUser = new User();


$active_men ['login'] = "active";
$active_men ['invoices'] = "";

// Destroy todos os dados de sessao anteriores
//session_destroy();

//Obtem os dados do usuario
$user = $oUser->validaUsuario($_GET['c']."" , $_GET['m']);

if( count($user) ) {
	$oConfigs->setLanguage($user['lingua'],false);
	require_once ( $local_root. $local_simbolic . "/view/pages/usuarios/validation.php");
} else {
	$oConfigs->setLanguage('',false);
	$error = $oConfigs->get('login','usuario_ja_atenticado');
	require_once ( $local_root. $local_simbolic . "/view/pages/error.php");
}

?>