<?php
	
	//TRADUCAO DOS TEXTOS:
	require_once ( dirname(__FILE__) . "/../../Classes/configs/Configs.php");
	$oConfigs = new Configs();
	$oConfigs->setLanguage($_POST['lang'], false);
	
	require_once ( dirname(__FILE__) . "/../../Classes/companies/register/Create.php");
	$oCompCreate = new CompCreate();
	
	require_once ( dirname(__FILE__) . "/../../Classes/common/Validacoes.php");
	$oValiacoes = new Validacoes();
	
	//ERROS:
	$cache_html = "";
	$error = false;
	
	
	if( !$_POST['id_usuario'] && !is_numeric($_POST['id_usuario'])) {
		$cache_html .= $oConfigs->get('cadastro_companies','usuario_nao_logado') . "<br>";
		$error = true;
	}
		
	if( !$_POST['lang'] ) {
		$cache_html .= $oConfigs->get('cadastro_companies','lingua_usu_indefinida') . "<br>";
		$error = true;		
	}

	if( !$_POST['emp_nome'] ) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_nome') . "<br>";
		$error = true;
	} 
		
	if( !$_POST['emp_end'] ) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_end') . "<br>";
		$error = true;
	}
	
	if( !$_POST['emp_cidade']) {		
		$cache_html .= $oConfigs->get('cadastro_companies','dig_cidade') . "<br>";
		$error = true;
	}

	if( !$_POST['emp_estado']) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_estado') . "<br>";
		$error = true;
	}
	
	if( !$_POST['emp_pais']) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_pais') . "<br>";
		$error = true;
	}
	

	if( !$_POST['emp_tel_p']) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_tel_p') . "<br>";
		$error = true;
	}

	if( !$_POST['emp_email']) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_email') . "<br>";
		$error = true;
	}

	if( $_POST['emp_email'] && !$oValiacoes->valida_mail($_POST['emp_email']) ) {
		$cache_html .= $oConfigs->get('cadastro_companies','email_invalido') . "<br>";
		$error = true;
	}	
	
	if( !$_POST['emp_site']) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_site') . "<br>";
		$error = true;
	}

	if( $_POST['emp_site'] && !$oValiacoes->isURL($_POST['emp_site']) ) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_site_correto') . "<br>";
		$error = true;
	}

	if( $_POST['emp_link_map'] && !$oValiacoes->isURL($_POST['emp_link_map']) ) {
		$cache_html .= $oConfigs->get('cadastro_companies','dig_maps_correto') . "<br>";
		$error = true;
	}	
	
	if($error) {
		$cache_html = $oConfigs->get('cadastro_companies','erros_encontrados') . "<br><br>" . $cache_html;
		$arr = array('transaction' => 'NO', 'msg' => $cache_html );
		print json_encode($arr);
		return;
	}
		
	$arr_args = array(
			'id_usuario'       => $_POST['id_usuario'],
			'lang'             => $_POST['lang'],
			
			'emp_nome'        => $_POST['emp_nome'],
			'emp_cnpj_id'     => $_POST['emp_cnpj_id'],
			'emp_end'         => $_POST['emp_end'],
			'emp_cidade'      => $_POST['emp_cidade'],
			'emp_estado'      => $_POST['emp_estado'],
			'emp_pais'        => $_POST['emp_pais'],
			'emp_tel_p'       => $_POST['emp_tel_p'],
			'emp_tel_s'       => $_POST['emp_tel_s'],
			'emp_email'       => $_POST['emp_email'],
			'emp_site'        => $_POST['emp_site'],
			'emp_link_map'    => $_POST['emp_link_map'],
			'emp_nome_prop'   => $_POST['emp_nome_prop'],
			'emp_comentarios' => $_POST['emp_comentarios'],
			'ativo'           => 1
	);
	
	$cache_html = "";
	
	$return = $oCompCreate->create($arr_args);
	
	if ($return) {
		$cache_html .= $oConfigs->get('cadastro_companies','cadastro_sucesso') . "<br>";
		$arr = array('transaction' => 'OK', 'msg' => $cache_html );
		
	} else {
		$cache_html .= $oConfigs->get('cadastro_companies','erro_ao_cadastrar') . "<br>";
		$cache_html .= $oConfigs->get('cadastro_companies','verifique_adm_do_sistema');		
		$arr = array('transaction' => 'NO', 'msg' => $cache_html );
	}
	
	//USAR PRINT COMO RETORNO DE FUNCAO
	print json_encode($arr);
	return;
?>