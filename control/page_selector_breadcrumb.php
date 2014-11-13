<?php

$urlStrSelector = $oHtmlSuport->serializeGET(
		array(
				a_page => $_GET['a_page'],
				o_by => $_GET['o_by'],
				o_tg => $_GET['o_tg'],
				s_in => $_GET['s_in'],
				s_po => $_GET['s_po'],
				s_co => $_GET['s_co'],
				s_special => $_GET['s_special'],
				n => $_GET['n'],
				page_n => $_GET['page_n'],
		)
);
print $urlStrSelector;

switch($_GET["p"]){
	case "7a141f837aa0edf25f68220d86787640": //md5("logon.php")
		break;
		
		
		
	//INVOICES {
	case "b5415e05223570d577345d8d9fc10756": //md5("invoices/listar.php")
		print '<li class="text-info">'.$oConfigs->get('cadastro_invoice','selector_listagem').'</li>';
		break;
	
	case "f6d875d5d83a9d10b68255a0e63aa26d": //md5("invoices/editar.php")
		print '<a href="logon.php?lang='.$oUser->get("lingua").'&p='. md5("invoices/listar.php").$urlStrSelector.'">'.
		$oConfigs->get('cadastro_invoice','selector_listagem').'</a>';
		print '<span class="divider"><i class="icon-caret-right"></i></span>';
		print '<li class="text-info">'.
				$oConfigs->get('cadastro_invoice','selector_editar').'</li>';
		break;
	
	case "132907420a3a75b520ddd95d8b0f2bf0": //md5("invoices/cadastrar.php")
		print '<a href="logon.php?lang='.$oUser->get("lingua").'&p='. md5("invoices/listar.php").$urlStrSelector. '">'.
		$oConfigs->get('cadastro_invoice','selector_listagem').'</a>';
		print '<span class="divider"><i class="icon-caret-right"></i></span>';
		print '<li class="text-info">'.
				$oConfigs->get('cadastro_invoice','selector_cadastrar').'</li>';
		break;
	//INVOICES }		
		

	//USUARIOS {
	case "2288f694a0dc334479e1d95c8b762b20": //md5("usuarios/listar.php")
		print '<li class="text-info">'.$oConfigs->get('cadastro_usuario','selector_listagem').$urlStrSelector.'</li>';
		break;
	
	case "60cc11dd4be4922a5264ef44588df210": //md5("usuarios/editar.php")
		print '<a href="logon.php?lang='.$oUser->get("lingua").'&p='. md5("usuarios/listar.php").$urlStrSelector.'">'.
			$oConfigs->get('cadastro_usuario','selector_listagem').'</a>';
		print '<span class="divider"><i class="icon-caret-right"></i></span>';
		print '<li class="text-info">'.
			$oConfigs->get('cadastro_usuario','selector_editar').'</li>';
		break;
		
	case "ac26745f01ce8c8b1aa8765e307ee7bc": //md5("usuarios/cadastrar.php")
		print '<a href="logon.php?lang='.$oUser->get("lingua").'&p='. md5("usuarios/listar.php").$urlStrSelector.'">'.
			$oConfigs->get('cadastro_usuario','selector_listagem').'</a>';
		print '<span class="divider"><i class="icon-caret-right"></i></span>';
		print '<li class="text-info">'.
			$oConfigs->get('cadastro_usuario','selector_cadastrar').'</li>';
		break;
	//USUARIOS }				
		
		
		
		
	//EMPRESAS {
	case "0db3a8b117580a98352b0594752b0bc5": //md5("companies/listar.php")
		print '<li class="text-info">'.$oConfigs->get('cadastro_companies','selector_listagem').$urlStrSelector.'</li>';
		break;
	
	
	case "468dfd162217f0899bfccd4587ec03b0": //md5("companies/editar.php")
		print '<a href="logon.php?lang='.$oUser->get("lingua").'&p='. md5("companies/listar.php").$urlStrSelector.'">'.
			$oConfigs->get('cadastro_companies','selector_listagem').'</a>';
		print '<span class="divider"><i class="icon-caret-right"></i></span>';
		print '<li class="text-info">'.
			$oConfigs->get('cadastro_companies','selector_editar').'</li>';
		break;
		
	case "5259e6e2f05ee90a878ef663ef1e8106": //md5("companies/cadastrar.php")
		print '<a href="logon.php?lang='.$oUser->get("lingua").'&p='. md5("companies/listar.php").$urlStrSelector.'">'.
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




