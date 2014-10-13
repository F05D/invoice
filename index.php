<?php
session_start();


//CONFIGURACOES E TRADUCOES
require_once ( dirname(__FILE__) . "/control/ajax/Classes/configs/Configs.php");
$oConfigs = new Configs();
$oConfigs->setLanguage($_GET['lang'],true);

$active_men ['login'] = "active";
$active_men ['invoices'] = "";

session_destroy();// Destroy todos os dados de sessao anteriores
?>
<!DOCTYPE html>
<html>
	<head>
	<!-- arquivos de cabecalho  -->
	<meta charset="utf-8">
	<title><?=$oConfigs->get('index','titulo')?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description"
		content="<?=$oConfigs->get('index','descricao')?>">
	<meta name="author"
		content="<?=$oConfigs->get('index','descricao')?>">
	<!-- required styles -->
	<link href="library/assets/css/bootstrap.css" rel="stylesheet">
	<link href="library/assets/css/bootstrap-responsive.css"
		rel="stylesheet">
	<link href="library/css/styles.css" rel="stylesheet">
	</head>
	<body>
		<div>
			<div id="main_content" class="container-fluid">
				<div class="row-fluid">
					<div class="well widget">
						<div class="widget-header">
							<h3 class="title"><?=$oConfigs->get('index','titulo')?></h3>
						</div>
						<div class="form-horizontal">
							<div class="control-group">
								<label class="control-label" for="inputEmail"><?=$oConfigs->get('login','email')?></label>
								<div class="controls">
									<input type="text" id="Email" placeholder="<?=$oConfigs->get('login','email-hint')?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="inputPassword"><?=$oConfigs->get('login','senha')?></label>
								<div class="controls">
									<input type="password" id="Password" placeholder="<?=$oConfigs->get('login','senha-hint')?>">
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button type="button" class="btn btn-cn" onclick="fazerLogin()"><?=$oConfigs->get('login','entrar')?></button>
									<button type="button" class="btn" onclick="reenviarSenha()"><?=$oConfigs->get('login','reenviar')?></button>								
								</div>
							</div>
							<div class="control-group">								
								<div class="controls alert-error">
									<i><span id="msg"></span></i>
								</div>
							</div>							
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	<script src="library/assets/js/jquery.js"></script>
		<script>
			jQuery.fn.extend({
				scrollToMe: function () {
					var x = jQuery(this).offset().top - 100;
					jQuery('html,body').animate({scrollTop: x}, 400);
        	}});
		</script>
	<script>
	function fazerLogin()
	{
		var lang  = '<?=$oConfigs->getLangSTR()?>';
		var email = $('#Email').val();
		var senha = $('#Password').val();

		//VERIFICACAO E CONTROLE
        $.ajax({
            url: 'control/ajax/delegate_to/user/login.php',            
            type: 'POST',
            data: {
	            'lang':lang,
	            'email':email,
	          	'senha':senha
			},
			dataType:"html",         
			success: function (data) {
				console.log(data);
				if( data ) {
					window.location.assign("logon.php?"+data);
				} else {
					$("#msg").html("<?=$oConfigs->get('login','erro_logar')?>");
					$("#msg").scrollToMe(); 
				}
	      	},
			error: function (data) {
				
				if(data) { $("#msg").html("<?=$oConfigs->get('login','erro_logar')?>"); $("#msg").scrollToMe();  }				
			},
			complete: function () {
				//
			}
		});
	}	

	function reenviarSenha()
	{
		var email = $('#Email').val();	
		var lang  = '<?=$oConfigs->getLangSTR()?>';	

		//VERIFICACAO E CONTROLE
        $.ajax({
        	url: 'control/ajax/delegate_to/user/reenviar_senha.php',
            type: 'POST',
            data: {
	            'code':'login_remember',
	            'id_usuario':'',
	            'email':email,
	            'lang':lang
			},  
			dataType:"html",         
			success: function (data) {
				console.log(data);
				$("#msg").html(data);
				$("#msg").scrollToMe();										
	      	},
			error: function (data) {
				console.log(data);
				if(data) { $("#msg").html("<?=$oConfigs->get('login','erro_enviar_senha')?>");$("#msg").scrollToMe(); }				
			},
			complete: function () {
				//
			}
		});
	}	
	</script>
		

</html>
