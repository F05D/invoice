<?php 
	switch($_GET["p"]) {
		case "7a141f837aa0edf25f68220d86787640": //md5("logon.php")
			require_once ($local_root. $local_simbolic . "/view/pages/home.php");
			break;
			
		//USUARIOS {
		case "2288f694a0dc334479e1d95c8b762b20": //md5("usuarios/listar.php")
			require_once ($local_root. $local_simbolic . "/view/pages/usuarios/listar.php");
			break;
		
		case "60cc11dd4be4922a5264ef44588df210": //md5("usuarios/editar.php")
			require_once ($local_root. $local_simbolic . "/view/pages/usuarios/editar.php");
			break;
				
		case "42249055f2c7108f44650b5a0a0c9834": //md5("usuarios/cadastrar.php")
			require_once ($local_root. $local_simbolic . "/view/pages/usuarios/cadastrar.php");
			break;
		
		//USUARIOS }
			
			
			
		// EMPRESAS	
		case "0db3a8b117580a98352b0594752b0bc5": //md5("companies/listar.php")
			require_once ($local_root. $local_simbolic . "/view/pages/companies/listar.php");
			break;
		
		case "468dfd162217f0899bfccd4587ec03b0": //md5("companies/editar.php")
			require_once ($local_root. $local_simbolic . "/view/pages/companies/editar.php");
			break;
				
		case "5259e6e2f05ee90a878ef663ef1e8106": //md5("companies/cadastrar.php")
			require_once ($local_root. $local_simbolic . "/view/pages/companies/cadastrar.php");
			break;
			
			


		default:
			require_once ($local_root. $local_simbolic . "/view/pages/404.php");
			break;
			
	}


?>