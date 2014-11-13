<?php

class Emails {
	
	//Envia Email de validacao
	public function sendEmailValidacao($email, $code_validacao_email) {
		
		$email_md5 = md5($email);

		//TODO: REMOVER LINK NA VERSAO OFICIAL
		$url_validacao = "http://localhost/invoice/validation.php?c=$code_validacao_email&m=$email_md5";
		
		$headers  = "MIME-Version:1.0\n";
		$headers .= "Content-type:text/html; charset=UTF-8\n";
		$headers .= "From:$email";

		$subject = "ARTSUL INVOICE-PRO - LINK PARA LOGIN";
		
		$mensagem   = "Olá."."\n";
		$mensagem  .= "\n\n";
		$mensagem  .= "Seu login para o INVOICE-PRO está disponível nos seguinte link:";
		$mensagem  .= "\n";
		$mensagem  .= $url_validacao;
		$mensagem  .= "\n";
		$mensagem  .= "Clique no link para cadastrar uma senha e entrar no sistema.";
		$mensagem  .= "\n";
		$mensagem  .= "Obrigado!";
		$mensagem  .= "Att, Art Sul";
		
		$resMail = null;
		$resMail = mail($to, $subject, $mensagem, $headers);

		
		if ($resMail) {
			return array('transaction' => 'OK', 'url' => $url_validacao );			
		} else {
			return array('transaction' => 'NO', 'url' => $url_validacao );;
		} 
	}

	//Envia Email de validacao
	public function send_email_nova_senha($email,$senha) {
	
		$headers  = "MIME-Version:1.0\n";
		$headers .= "Content-type:text/html; charset=UTF-8\n";
		$headers .= "From:$email";
	
		$subject = "ARTSUL INVOICE-PRO - LOGIN E SENHA";
	
		$mensagem   = "Olá."."\n";
		$mensagem  .= "\n\n";
		$mensagem  .= "Seu login e senha para o INVOICE-PRO é:";
		$mensagem  .= "\n\n";
		$mensagem  .= "Login:".$email;
		$mensagem  .= "Senha:".$senha;
		$mensagem  .= "\n\n";
		$mensagem  .= "Link INVOICE-PRO:";
		$mensagem  .= "\n";
		$mensagem  .= "http:://www.invoice-pro.artsulgranitos.comb.br.";
		$mensagem  .= "Obrigado!";
		$mensagem  .= "Att, Art Sul";
	
		$resMail = null;
		$resMail = mail($to, $subject, $mensagem, $headers);
	
		if ($resMail)
			return true;
	
		return false;
	}
	
	//TODO:retirar essa funcao depois - duplicada abaixo:
	public function valida_mail($email){
		return ereg("^([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([a-z,A-Z]){2,3}([0-9,a-z,A-Z])?$", $email);
	}

	public function validaMail($email){
		return ereg("^([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([a-z,A-Z]){2,3}([0-9,a-z,A-Z])?$", $email);
	}
	
	public function reenvioSenhaUsuario($email, $senha, $oConfigs) {
		
		$ip = $_SERVER['REMOTE_ADDR'];
	
		$mensagem  = $oConfigs->get('login','email_cabecalho1');
		$mensagem .= $oConfigs->get('login','assunto').": $assunto\n\n";
		$mensagem .= $oConfigs->get('login','email_automatico').": $nome < $email >\n";
		$mensagem .= $oConfigs->get('login','ip_usuario').": $ip\n";
	
		$to = $email;
	
		$from = $oConfigs->get('login','email_from');
		$subject  = $oConfigs->get('login','email_cabecalho2');
		$headers  = "MIME-Version:1.0\n";
		$headers .= "Content-type:text/html; charset=UTF-8\n";
		$headers .= "From:".$from;
	
		$resMail = null;
		$resMail = mail($to, $subject, $mensagem, $headers);
	
		if($resMail) return true;
		
		return false;
	}
	
	public function notificacaoInvoice($action, $oUser, $oComp, $oConfigs, $arr_data )
	{
		
		//USUARIO REMETENTE		
		$user  = $oUser->getById($arr_data['id_usuario']);		
		$email = $user['nome']."<".$user['usuario'].">";
		
		//USUARIO DESTINO		
		$arr_user = $oComp->getBindUser($arr_data['invoice_empresa'])[0];
		$to    = $arr_user['usuario'];
		$nome  = $arr_user['nome'];		

		$oConfigs->setLanguage($user['lingua'], false);		
		
		$headers  = "MIME-Version:1.0\n";
		$headers .= "Content-type:text/html; charset=UTF-8\n";
		$headers .= "From:$email";
		
		$subject = "ARTSUL INVOICE-PRO [" . $oConfigs->get('cadastro_invoice','email_'.$action) . "]";
		
		$mensagem   = $oConfigs->get('cadastro_invoice','email_create_01') ." ".$nome."\n";
		$mensagem  .= "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','email_create_02') . " '". $arr_data['invoice_nr'] ."' ";
		$mensagem  .= $oConfigs->get('cadastro_invoice','email_create_03');
		$mensagem  .= "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','email_create_04').":";
		$mensagem  .= "\n";
		
		$mensagem  .= $oConfigs->get('cadastro_invoice','cad_invoice_nr')                   .":". $arr_data['invoice_nr'] . "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','cad_invoice_fatura_valor')         .":". $arr_data['invoice_fatura_valor'] . "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','cad_invoice_data_vencimento')      .":". $arr_data['invoice_data_vencimento'] . "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','cad_invoice_container')            .":". $arr_data['invoice_container'] . "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','cad_invoice_booking')              .":". $arr_data['invoice_booking'] . "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','cad_invoice_tipo')                 .":". $arr_data['invoice_tipo'] . "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','cad_invoice_tara')                 .":". $arr_data['invoice_tara'] . "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','cad_invoice_peso_bruto')           .":". $arr_data['invoice_peso_bruto'] . "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','cad_invoice_peso_liquido')         .":". $arr_data['invoice_peso_liquido'] . "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','cad_invoice_qnt')                  .":". $arr_data['invoice_qnt'] . "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','cad_invoice_nota_fiscal')          .":". $arr_data['invoice_nota_fiscal'] . "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','cad_invoice_lacres')               .":". $arr_data['invoice_lacres'] . "\n";
		$mensagem  .= "\n\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','email_create_05').":";
		$mensagem  .= "\n";
		
		foreach ($arr_data['arrDocs'] as $key => $arr) {
			$mensagem  .= (!$arr['lock'] ? '[+]': '[-]').$oConfigs->get('cadastro_invoice',$key) .": ".  $arr['name'] .  "\n";
		}
		
		$mensagem  .= "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','email_create_06').": http://www.invoice.artsulgranitos.com.br";
		$mensagem  .= "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','email_create_07') . "\n";
		$mensagem  .= $oConfigs->get('cadastro_invoice','email_create_08');
		
		$resMail = null;
		$resMail = mail($to, $subject, $mensagem, $headers);
		
		return ($resMail ? 'OK' : 'NO');
	}
	
}


?>