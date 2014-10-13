<?php

class Emails {
	
	//Envia Email de validacao
	public function send_email_para_validacao($email, $code_validacao_email) {
		
		$email_md5 = md5($email);
				
		$url_validacao = "http://localhost/Sites/www.invoice.artsulgranitos.com.br/validation.php?c=$code_validacao_email&m=$email_md5";
		
		print $url_validacao;
		
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

		if ($resMail)
			return true;	
						
		return false;
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
	
}


?>