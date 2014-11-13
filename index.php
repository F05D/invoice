<?php
session_start();
session_destroy();// Destroy todos os dados de sessao anteriores

//CONFIGURACOES E TRADUCOES
require_once ( dirname(__FILE__) . "/control/ajax/Classes/configs/Configs.php");
$oConfigs = new Configs();
$oConfigs->setLanguage($_GET['lang'],true);

$active_men ['login'] = "active";
$active_men ['invoices'] = "";


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
									<button type="button" class="btn btn-cn" onclick="login()"><?=$oConfigs->get('login','entrar')?></button>&nbsp;&nbsp;
									<a href="#" onclick="reenviar()"><?=$oConfigs->get('login','reenviar')?></a>								
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
	function login()
	{
		var lang  = '<?=$oConfigs->getLangSTR()?>';
		var email = $('#Email').val();
		var senha = $('#Password').val();

		//VERIFICACAO E CONTROLE
        $.ajax({
            url: 'control/ajax/delegate_to/login/login.php',            
            type: 'POST',
            data: {
	            'lang':lang,
	            'email':email,
	          	'senha':senha
			},
			dataType:"html",         
			success: function (data) {
				console.log(data);
				
				var obj = JSON.parse(data);
				if( obj.transaction == 'OK' ) {
					window.location.assign("logon.php?lang=<?=$_GET['lang']?>&pincode="+ obj.pincode);
				} else {
					$("#msg").html( obj.msg );
					$("#msg").scrollToMe(); 
				}
	      	},
			error: function (data) {
				console.log(data);
				
				if(data) { $("#msg").html("<?=$oConfigs->get('login','erro_logar')?>"); $("#msg").scrollToMe();  }				
			},
			complete: function () {
				//
			}
		});
	}	

	function reenviar()
	{
		var email = $('#Email').val();	
		var lang  = '<?=$oConfigs->getLangSTR()?>';	

		//VERIFICACAO E CONTROLE
        $.ajax({
        	url: 'control/ajax/delegate_to/login/reenviar.php',
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
				var obj = JSON.parse(data);
				$("#msg").html( obj.msg );
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
