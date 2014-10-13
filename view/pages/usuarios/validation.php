<!DOCTYPE html>
<html>
	<head>
	<!-- arquivos de cabecalho  -->
		<meta charset="utf-8">
		<title><?=$oConfigs->get('index','titulo')?></title>		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?=$oConfigs->get('index','descricao')?>">
		<meta name="author" content="<?=$oConfigs->get('index','descricao')?>">
		<!-- required styles -->
		<link href="library/assets/css/bootstrap.css" rel="stylesheet">
		<link href="library/assets/css/bootstrap-responsive.css" rel="stylesheet">
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
								<div class="controls">
									<?=$oConfigs->get('login','autenticacao_usuario')?></h4>
								</div>
							</div>
						
							<div class="control-group">
								<label class="control-label" for="inputEmail"><?=$oConfigs->get('login','email')?></label>
								<div class="controls">
									<input type="text" id="user_email" value="<?=$user['usuario']?>" readonly>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="inputPassword"><?=$oConfigs->get('login','senha')?></label>
								<div class="controls">
									<input type="password" id="user_senha" placeholder="<?=$oConfigs->get('login','senha-hint')?>">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="inputPassword"><?=$oConfigs->get('login','senha_rep')?></label>
								<div class="controls">
									<input type="password" id="user_senha_ver" placeholder="<?=$oConfigs->get('login','senha-hint')?>">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="inputEmail"><?=$oConfigs->get('cadastro_usuario','dica_senha')?></label>
								<div class="controls">
									<input type="text" id="user_dica" placeholder="<?=$oConfigs->get('login','dica_senha')?>">
								</div>
							</div>
							
							
							<div class="control-group">
								<div class="controls">
									<button type="button" class="btn btn-cn" onclick="trocar_e_logar()"><?=$oConfigs->get('login','entrar')?></button>																	
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
	function trocar_e_logar()
	{
		var id              = '<?=$user['id']?>';
		var lang            = '<?=$user['lingua']?>';
		var user_email      = $('#user_email').val();
		var user_senha      = $('#user_senha').val();
		var user_senha_ver  = $('#user_senha_ver').val();
		var user_dica       = $('#user_dica').val();
		var code_auth       = '<?=md5($user['lingua'].$user['id'].$user['usuario'].$user['dt_nascimento'].$user['nome'])?>';

		//VERIFICACAO E CONTROLE
        $.ajax({
            url: 'control/ajax/delegate_to/user/change_password.php',
            type: 'POST',
            data: {
	            'id_usuario':id,
	            'lang':lang,
	            'user_email':user_email,
	          	'user_senha':user_senha,
	          	'user_senha_ver':user_senha_ver,
	          	'user_dica':user_dica,
	          	'code_auth':code_auth	          		          		          	
			},  
			dataType:"html",         
			success: function (data) {
				console.log(data);
				var obj = JSON.parse(data);
				if(obj.transaction == 'OK') {
					window.location.assign("logon.php?"+obj.msg);
				} else {
					var msg = obj.msg;
					if( obj.email_status == 'NO' ) {
						 msg = msg + "\n" + obj.email_msg;	
					}
				}
				
				$("#msg").html( msg );
				$("#msg").scrollToMe();					     		
	      	},
			error: function (data) {
				if(data) { $("#msg").html("Erro ao tentar logar"); $("#msg").scrollToMe();  }				
			},
			complete: function () {
				//
			}
		});
	}	
	</script>
		

</html>