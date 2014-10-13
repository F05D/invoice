<?php
 
//PROCURA DADOS DO USUARIO	
$arr_result = array();

if($_GET['i']) { 
	$arr_result = $oUser->getUserById($_GET['i']);
}

//Validacoes
require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
$validacoes = new Validacoes ();

$dt_formatada = $validacoes->convertDBtoData($arr_result[0]['dt_nascimento'], $arr_result[0]['lingua']);

?>

<div class="well widget">
	<div class="widget-header">
		<h3 class="title">Cadastro de Empresas:</h3>
	</div>		
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_nome_comp')?>" id="user_nome" value="<?=$arr_result[0]['nome']?>">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_dt_nasc')?>" value="<?=$dt_formatada?>" id="user_dt_nasc">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_email')?>" id="user_email" value="<?=$arr_result[0]['usuario']?>" readonly onclick="alterar_email();">
	<input class="input-block-level" type="password" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_senha')?>" id="user_senha"  value="<?=$arr_result[0]['senha']?>" readonly onclick="alert_troca_senha()">
	<input class="input-block-level" type="password" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_senha_rep')?>" id="user_senha_ver"  value="<?=$arr_result[0]['senha']?>" readonly onclick="alert_troca_senha()">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_dica_senha')?>" id="user_dica"  value="<?=$arr_result[0]['dica_senha']?>" readonly onclick="alert_troca_senha()">
	<select class="input-block-level" id="user_privilegio">
		<option value="" selected>- <?=$oConfigs->get('cadastro_usuario','lista_priv_text')?> -</option>
		<option value="1" <?=($arr_result[0]['privilegios'] == 1 ? " selected " : "") ?> ><?=$oConfigs->get('cadastro_usuario','lista_priv_adm')?></option>
		<option value="2" <?=($arr_result[0]['privilegios'] == 2 ? " selected " : "") ?>><?=$oConfigs->get('cadastro_usuario','lista_priv_usuario')?></option>
		<option value="3" <?=($arr_result[0]['privilegios'] == 3 ? " selected " : "") ?>><?=$oConfigs->get('cadastro_usuario','lista_priv_cliente')?></option>
	</select>
	
	<select class="input-block-level" id="user_lang">
		<option value="" selected>- <?=$oConfigs->get('cadastro_usuario','lista_lang_text')?> -</option>
		<option value="pt" <?=($arr_result[0]['lingua'] == "pt" ? " selected " : "") ?>><?=$oConfigs->get('cadastro_usuario','lista_lang_pt')?></option>
		<option value="en" <?=($arr_result[0]['lingua'] == "en" ? " selected " : "") ?>><?=$oConfigs->get('cadastro_usuario','lista_lang_en')?></option>
		<option value="sp" <?=($arr_result[0]['lingua'] == "sp" ? " selected " : "") ?>><?=$oConfigs->get('cadastro_usuario','lista_lang_sp')?></option>
	</select>
	
	<select class="input-block-level" id="user_empresa_associada">
		<option value="0" <?=($arr_result[0]['lingua'] == 0 ? " selected " : "") ?> >- <?=$oConfigs->get('cadastro_usuario','lista_empresa')?> -</option>
		
	</select>	
	
	<div class="row-fluid">
		<div class="span4">
			<div class="button-action">
				<a class="btn btn-large btn-danger" onclick="ajax_control();"><span class="right"><i class="icon-large icon-download-alt"></i></span><?=$oConfigs->get('cadastro_usuario','lista_btn_editar')?>EDITAR</a>
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
<script language=javascript>

	function alterar_email()
	{	
		if($('#user_email').attr('readonly')) {	
			var r = confirm("Deseja alterar a email deste usuário ?\nO email do usuário é o próprio login de acesso, caso altere, o usuário deverá ser informado da mudança, deseja alterar mesmo assim?");
			if (r == true) {
				$('#user_email').attr('readonly', false);
			}
		} 
	}

	function alert_troca_senha()
	{	
		if($('#user_senha').attr('readonly')) {	
			var r = confirm("Deseja alterar a senha e a dica de senha deste usuário ?\nUm email com as novas alterações será enviado para este usuário, deseja alterar mesmo assim?");
			if (r == true) {
				$('#user_senha').attr('readonly', false);
				$('#user_senha_ver').attr('readonly', false);
				$('#user_dica').attr('readonly', false);
			}
		} 
	}
	
	function ajax_control()
	{
		var id_usuario = "<?=$user['id']?>"; //PERMANENTE
		var lang       = "<?=$user['lingua']?>"; //PERMANENTE

		var id_alter        = '<?=$_GET['i']?>';		
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
            url: 'control/ajax/delegate_to/user/update.php',
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
	          	'user_lang':user_lang,
	          	'id_alter':id_alter
			},  
			dataType:"html",         
			success: function (data) {
				if( data ) { 
					$("#msg").html(data);
					$("#msg").scrollToMe();			
				} else { 
					$("#msg").html("Não foi possível processar esta operação. Favor contactar o administrador.");
					$("#msg").scrollToMe();
				}
	      	},
			error: function (data) {
				if(data) $("#msg").html("Erro ao tentar cadastrar.");
			},
			complete: function () {
				//
			}
		});
		
	}	
	</script>