<div class="well widget">
	<div class="widget-header">
		<h3 class="title"><?=$oConfigs->get('cadastro_usuario','lista_cadastro_tit')?></h3>
	</div>	
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_nome_comp')?>" id="user_nome">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_dt_nasc')?>" id="user_dt_nasc">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_email')?>" id="user_email">
	<input class="input-block-level" type="password" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_senha')?>" id="user_senha">
	<input class="input-block-level" type="password" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_senha_rep')?>" id="user_senha_ver">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_dica_senha')?>" id="user_dica">
	<select class="input-block-level" id="user_privilegio">
		<option value="" selected>- <?=$oConfigs->get('cadastro_usuario','lista_priv_text')?> -</option>
		<option value="1"><?=$oConfigs->get('cadastro_usuario','lista_priv_adm')?></option>
		<option value="2"><?=$oConfigs->get('cadastro_usuario','lista_priv_usuario')?></option>
		<option value="3" ><?=$oConfigs->get('cadastro_usuario','lista_priv_cliente')?></option>
	</select>
	
	<select class="input-block-level" id="user_lang">
		<option value="" selected>- <?=$oConfigs->get('cadastro_usuario','lista_lang_text')?> -</option>
		<option value="pt"><?=$oConfigs->get('cadastro_usuario','lista_lang_pt')?></option>
		<option value="en"><?=$oConfigs->get('cadastro_usuario','lista_lang_en')?></option>
		<option value="sp"><?=$oConfigs->get('cadastro_usuario','lista_lang_sp')?></option>
	</select>
	
	<select class="input-block-level" id="user_empresa_associada">
		<option value="0" selected>- <?=$oConfigs->get('cadastro_usuario','lista_empresa')?> -</option>
	</select>	
	
	<div class="row-fluid">
		<div class="span4">
			<div class="button-action">
				<a class="btn btn-large btn-danger" onclick="ajax_control();"><span class="right"><i class="icon-large icon-download-alt"></i></span> <?=$oConfigs->get('cadastro_usuario','lista_btn_inserir')?></a>
			</div>
		</div>
	</div>
	<div class="control-group">								
		<div class="controls alert-error">
			<i><span id="msg"></span></i>
		</div>
	</div>
</div>

<!-- AJAX CONTROLS -->
<script>
	function ajax_control() {
		var id_usuario = "<?=$user['id']?>"; //PERMANENTE
		var lang       = "<?=$user['lingua']?>"; //PERMANENTE
	
		var user_nome       = $('#user_nome').val();
		var user_dt_nasc    = $('#user_dt_nasc').val();
		var user_email      = $('#user_email').val();
		var user_senha      = $('#user_senha').val();
		var user_senha_ver  = $('#user_senha_ver').val();
		var user_dica       = $('#user_dica').val();		
		var user_privilegio = $('#user_privilegio :selected').val()
		var user_empresa    = $('#user_empresa_associada :selected').val()
		var user_lang       = $('#user_lang').val();		
		
		//VERIFICACAO E CONTROLE
	    $.ajax({
	        url: 'control/ajax/delegate_to/user/create.php',
	        type: 'POST',
	        data: {
	            'id_usuario':id_usuario,//PERMANENTE
	            'lang':lang,//PERMANENTE
	            
	            'user_nome':user_nome,
	            'user_dt_nasc':user_dt_nasc,
	            'user_email':user_email,
	          	'user_senha':user_senha,
	          	'user_senha_ver':user_senha_ver,
	          	'user_dica':user_dica,
	          	'user_privilegio':user_privilegio,
	          	'user_empresa':user_empresa,
	          	'user_lang':user_lang
			},
			dataType:"html",         
			success: function (data) {
				console.log(data);
				if(data) {
					var obj = JSON.parse(data);
					$("#msg").html(obj.msg);
					$("#msg").scrollToMe();
				}
	      	},
			error: function (data) {
				if(data) $("#msg").html("<?=$oConfigs->get('cadastro_usuario','erro_ao_cadastrar_usua')?>");
			},
			complete: function () {
				//
			}
		});	
	}	
	</script>