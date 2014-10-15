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
		
		$mensagem   = "Olá."."<br>";
		$mensagem  .= "<br><br>";
		$mensagem  .= "Seu login para o INVOICE-PRO está disponível nos seguinte link:";
		$mensagem  .= "<br>";
		$mensagem  .= $url_validacao;
		$mensagem  .= "<br>";
		$mensagem  .= "Clique no link para cadastrar uma senha e entrar no sistema.";
		$mensagem  .= "<br>";
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
	
		$mensagem   = "Olá."."<br>";
		$mensagem  .= "<br><br>";
		$mensagem  .= "Seu login e senha para o INVOICE-PRO é:";
		$mensagem  .= "<br><br>";
		$mensagem  .= "Login:".$email;
		$mensagem  .= "Senha:".$senha;
		$mensagem  .= "<br><br>";
		$mensagem  .= "Link INVOICE-PRO:";
		$mensagem  .= "<br>";
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
	
	function reenvioSenhaUsuario($email, $senha, $oConfigs) {
		
		$ip = $_SERVER['REMOTE_ADDR'];
	
		$mensagem  = $oConfigs->get('login','email_cabecalho1');
		$mensagem .= $oConfigs->get('login','assunto').": $assunto<br><br>";
		$mensagem .= $oConfigs->get('login','email_automatico').": $nome < $email ><br>";
		$mensagem .= $oConfigs->get('login','ip_usuario').": $ip<br>";
	
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
	
}


?>