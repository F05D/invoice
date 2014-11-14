<div class="well widget">
	<div class="widget-header">
		<h3 class="title"><?=$oConfigs->get('cadastro_companies','cadastro_empresas')?>:</h3>
	</div>
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_nome')?>"      id="emp_nome">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_cnpj')?>"      id="emp_cnpj_id">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_end')?>"       id="emp_end">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_cidade')?>"    id="emp_cidade">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_estado')?>"    id="emp_estado">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_pais')?>"      id="emp_pais">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_tel_p')?>"     id="emp_tel_p">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_tel_s')?>"     id="emp_tel_s">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_email')?>"     id="emp_email">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_site')?>"      id="emp_site">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_maps')?>"      id="emp_link_map">
	<input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_nome_prop')?>" id="emp_nome_prop">	
	<textarea class="input-block-level" placeholder="<?=$oConfigs->get('cadastro_companies','cad_coments')?>" rows="4"   id="emp_comentarios"></textarea>

	<div class="row-fluid">
		<div class="span4">
			<div class="button-action">
				<a class="btn btn-large btn-danger" onclick="ajax_control();">
					<span class="right">
						<i class="icon-large icon-download-alt"></i>
					</span> 
					<?=$oConfigs->get('cadastro_companies','cad_inserir')?>
				</a>
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
	    $.ajax({
	        url: 'control/ajax/delegate_to/companies/create.php',
	        type: 'POST',
	        data: {
	            'id_usuario':'<?=$oUser->get('id')?>', //PERMANENTE
	            'lang':'<?=$oUser->get('lingua')?>',   //PERMANENTE
	            'code_user':'<?=$oUser->getCodeSecurity( $oUser->get('id')."$%F*")?>', //PERMANENTE
	            'code_create':'<?=$oUser->getCodeSecurity( $oUser->getLastId()."&%d@")?>', //PERMANENTE	            
	            'emp_nome':$("#emp_nome").val(),
	            'emp_cnpj_id':$("#emp_cnpj_id").val(),
	            'emp_end':$("#emp_end").val(),
	            'emp_cidade':$("#emp_cidade").val(),
	            'emp_estado':$("#emp_estado").val(),
	            'emp_pais':$("#emp_pais").val(),
	            'emp_tel_p':$("#emp_tel_p").val(),
	            'emp_tel_s':$("#emp_tel_s").val(),
	            'emp_email':$("#emp_email").val(),
	            'emp_site':$("#emp_site").val(),
	            'emp_link_map':$("#emp_link_map").val(),
	            'emp_nome_prop':$("#emp_nome_prop").val(),
	            'emp_comentarios':$("#emp_comentarios").val()
			},
			dataType:"html",         
			success: function (data) {
				console.log(data);
				if(data) {
					var obj = JSON.parse(data);
					if(obj.transaction == 'OK') {
						alert(obj.msg);
						window.location.assign("logon.php?p=<?=md5('companies/listar.php')?><?=$urlStrSelector?>");
					} else {
						$("#msg").html(obj.msg);
						$("#msg").html(obj.msg);
						$("#msg").scrollToMe();
					}
				}
	      	},
			error: function (data) {
				if(data) $("#msg").html("<?=$oConfigs->get('cadastro_companies','erro_ao_cadastrar')?>");
			},
			complete: function () {
				//
			}
		});	
	}	
	</script>
	