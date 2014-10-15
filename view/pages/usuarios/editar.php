<?php
 
//PROCURA DADOS DO USUARIO	
$arr_result = array();

if($_GET['i']) { 
	$id = $_GET['i'];
	$arr_result = $oUser->getById($id);
} else {
	$id = 0;
}

require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/companies/Comp.php");
$oComp = new Comp();

$arr_comp = array();
$arr_comp = $oComp->read(array('id','nome'));

//Validacoes
require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
$oV = new Validacoes();
$dt_format = $oV->convertDBtoData($arr_result['dt_nascimento'], $arr_result['lingua']);

$arrIdBindCompany = $oUser->getBindCompany($id);

?>

<div class="well widget">
	<div class="widget-header">
		<h3 class="title">Cadastro de Empresas:</h3>
	</div>		
	<input class="input-block-level" type="text"     placeholder="<?=$oConfigs->get('cadastro_usuario','lista_nome_comp')?>"     id="user_nome"       value="<?=$arr_result['nome']?>">
	<input class="input-block-level" type="text"     placeholder="<?=$oConfigs->get('cadastro_usuario','lista_dt_nasc')?>"       id="user_dt_nasc"    value="<?=$dt_format?>" >
	<input class="input-block-level" type="text"     placeholder="<?=$oConfigs->get('cadastro_usuario','lista_email')?>"         id="user_email"      value="<?=$arr_result['usuario']?>"    readonly onclick="alterar_email();">
	<input class="input-block-level" type="password" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_senha')?>"         id="user_senha"      value="<?=$arr_result['senha']?>"      readonly onclick="alert_troca_senha()">
	<input class="input-block-level" type="password" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_senha_rep')?>"     id="user_senha_ver"  value="<?=$arr_result['senha']?>"      readonly onclick="alert_troca_senha()">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_usuario','lista_dica_senha')?>"        id="user_dica"       value="<?=$arr_result['dica_senha']?>" readonly onclick="alert_troca_senha()">
	<select class="input-block-level" id="user_privilegio">
		<option value="" selected>- <?=$oConfigs->get('cadastro_usuario','lista_priv_text')?> -</option>
		<? foreach ( $oUser->getListPrivilegios() as $val) { ?> 
			<option value="<?=$val['id']?>" <?=($val['id'] == $arr_result['privilegios'] ? ' selected ' : '')?>  ><?=$oConfigs->get('cadastro_usuario',$val['desc'])?></option>
		<? } ?>
	</select>
	
	<select class="input-block-level" id="user_lang">
		<option value="" selected>- <?=$oConfigs->get('cadastro_usuario','lista_lang_text')?> -</option>
		<? foreach ( $oUser->getListLinguas() as $val) { ?>
			<option value="<?=$val['lingua']?>" <?=($val['lingua'] == $arr_result['lingua'] ? ' selected ' : '')?> > <?=$oConfigs->get('cadastro_usuario','desc_'.$val['lingua'])?></option>
		<? } ?>
	</select>
	
	<select class="input-block-level" id="user_empresa_associada">
		<option value="0" selected>- <?=$oConfigs->get('cadastro_usuario','lista_empresa')?> -</option>
		<? foreach ( $arr_comp as $val) { ?> 
			<option value="<?=$val['id']?>" <?=($val['id'] == $arrIdBindCompany['id'] ? ' selected ' : '')?> ><?=$val['nome']?></option>
		<? } ?>
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
<script>
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
		var id_usuario = "<?=$oUser->get('id')?>"; //PERMANENTE
		var lang       = "<?=$oUser->get('lingua')?>"; //PERMANENTE

		var code_user   = "<?=$oUser->getCodeSecurity( $oUser->get('id').'*%' )?>";
		var code_edit   = "<?=$oUser->getCodeSecurity( $id.'#!'  )?>";		

		var id_alter        = '<?=$id?>';
		var user_nome       = $('#user_nome').val();
		var user_dt_nasc    = $('#user_dt_nasc').val();
		var user_email      = $('#user_email').val();
		var user_senha      = $('#user_senha').val();
		var user_senha_ver  = $('#user_senha_ver').val();
		var user_dica       = $('#user_dica').val();		
		var user_privilegio = $('#user_privilegio :selected').val();
		var user_empresa    = $('#user_empresa_associada :selected').val();
		var user_lang       = $('#user_lang').val();		
		
		//VERIFICACAO E CONTROLE
        $.ajax({
            url: 'control/ajax/delegate_to/user/update.php',
            type: 'POST',
            data: {
	            'id_usuario':id_usuario,//PERMANENTE
	            'lang':lang,//PERMANENTE

	            'code_user':code_user,
	            'code_edit':code_edit,
	            
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
				console.log(data);
				if(data) {
					var obj = JSON.parse(data);
					$("#msg").html(obj.msg);
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