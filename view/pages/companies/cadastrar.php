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
		var id_usuario = "<?=$user['id']?>"; //PERMANENTE
		var lang       = "<?=$user['lingua']?>"; //PERMANENTE

		var emp_nome        = $("#emp_nome").val();
		var emp_cnpj_id     = $("#emp_cnpj_id").val();
		var emp_end         = $("#emp_end").val();
		var emp_cidade      = $("#emp_cidade").val();
		var emp_estado      = $("#emp_estado").val();
		var emp_pais        = $("#emp_pais").val();
		var emp_tel_p       = $("#emp_tel_p").val();
		var emp_tel_s       = $("#emp_tel_s").val();
		var emp_email       = $("#emp_email").val();
		var emp_site        = $("#emp_site").val();
		var emp_link_map    = $("#emp_link_map").val();
		var emp_nome_prop   = $("#emp_nome_prop").val();	
		var emp_comentarios = $("#emp_comentarios").val();

		//var user_privilegio = $('#user_privilegio :selected').val()
		
		//VERIFICACAO E CONTROLE
	    $.ajax({
	        url: 'control/ajax/delegate_to/companies/create.php',
	        type: 'POST',
	        data: {
	            'id_usuario':id_usuario,//PERMANENTE
	            'lang':lang,//PERMANENTE
	            
	            'emp_nome':emp_nome,
	            'emp_cnpj_id':emp_cnpj_id,
	            'emp_end':emp_end,
	            'emp_cidade':emp_cidade,
	            'emp_estado':emp_estado,
	            'emp_pais':emp_pais,
	            'emp_tel_p':emp_tel_p,
	            'emp_tel_s':emp_tel_s,
	            'emp_email':emp_email,
	            'emp_site':emp_site,
	            'emp_link_map':emp_link_map,
	            'emp_nome_prop':emp_nome_prop,
	            'emp_comentarios':emp_comentarios
			},
			dataType:"html",         
			success: function (data) {
				console.log(data);
				if(data) {
					var obj = JSON.parse(data);
					if(obj.transaction == 'OK') {
						alert(obj.msg);
						window.location.assign("logon.php?p=<?=md5('companies/listar.php')?>");
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
	