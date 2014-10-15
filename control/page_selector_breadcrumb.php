<?php 

switch($_GET["p"]){
	case "7a141f837aa0edf25f68220d86787640": //md5("logon.php")
		break;
		
	//USUARIOS {
	case "2288f694a0dc334479e1d95c8b762b20": //md5("usuarios/listar.php")
		print '<li class="text-info">'.$oConfigs->get('cadastro_usuario','selector_listagem').'</li>';
		break;
	
		
	case "60cc11dd4be4922a5264ef44588df210": //md5("usuarios/editar.php")
		print '<a href="logon.php?lang='.$oUser->get("lingua").'&p='. md5("usuarios/listar.php"). '">'.
			$oConfigs->get('cadastro_usuario','selector_listagem').'</a>';
		print '<span class="divider"><i class="icon-caret-right"></i></span>';
		print '<li class="text-info">'.
			$oConfigs->get('cadastro_usuario','selector_editar').'</li>';
		break;
		
	case "42249055f2c7108f44650b5a0a0c9834": //md5("usuarios/cadastrar.php")
		print '<a href="logon.php?lang='.$oUser->get("lingua").'&p='. md5("usuarios/listar.php"). '">'.
			$oConfigs->get('cadastro_usuario','selector_listagem').'</a>';
		print '<span class="divider"><i class="icon-caret-right"></i></span>';
		print '<li class="text-info">'.
			$oConfigs->get('cadastro_usuario','selector_cadastrar').'</li>';
		break;
	//USUARIOS }				
		
		
	//EMPRESAS {
	case "0db3a8b117580a98352b0594752b0bc5": //md5("companies/listar.php")
		print '<li class="text-info">'.$oConfigs->get('cadastro_companies','selector_listagem').'</li>';
		break;
	
	
	case "468dfd162217f0899bfccd4587ec03b0": //md5("companies/editar.php")
		print '<a href="logon.php?lang='.$oUser->get("lingua").'&p='. md5("companies/listar.php"). '">'.
			$oConfigs->get('cadastro_companies','selector_listagem').'</a>';
		print '<span class="divider"><i class="icon-caret-right"></i></span>';
		print '<li class="text-info">'.
			$oConfigs->get('cadastro_companies','selector_editar').'</li>';
		break;
		
	case "5259e6e2f05ee90a878ef663ef1e8106": //md5("companies/cadastrar.php")
		print '<a href="logon.php?lang='.$oUser->get("lingua").'&p='. md5("companies/listar.php"). '">'.
			$oConfigs->get('cadastro_companies','selector_listagem').'</a>';
		print '<span class="divider"><i class="icon-caret-right"></i></span>';
		print '<li class="text-info">'.
			$oConfigs->get('cadastro_companies','selector_cadastrar').'</li>';
		break;
		//EMPRESAS }
		
		
		
		
			
	default: //'pages/404.php';
		print '<li class="text-info">'.$oConfigs->get('general','page_not_found').'</li>';
		break;
			
}


?>




