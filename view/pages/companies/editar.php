<?

//PERMITIDO SOMENTE PARA ADM
if($_arrUserPerm['priv'] == 1 ) return;

//PROCURA DADOS DA EMPRESA
$arr_result = array();

require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/companies/Comp.php");
$oComp = new Comp();

if($_GET['i']) {
	$arr_result = $oComp->get($_GET['i'])[0];
}

?>


<div class="well widget">
	<div class="widget-header">
		<h3 class="title"><?=$oConfigs->get('cadastro_companies','editar_empresa')?>:</h3>
	</div>
	
	<table class="table table-hover">				
		<input type="hidden" id="emp_id" value="<?=$arr_result['id']?>">
		
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_nome')?></td>
			<td><input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_nome')?>"      id="emp_nome"      value="<?=$arr_result['nome']?>"></td>
		</tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_cnpj')?></td>
			<td><input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_cnpj')?>"      id="emp_cnpj_id"   value="<?=$arr_result['cnpj_id']?>"></td>
		</tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_end')?></td>
			<td><input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_end')?>"       id="emp_end"       value="<?=$arr_result['endereco']?>"></td>
		</tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_cidade')?></td>
			<td><input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_cidade')?>"    id="emp_cidade"    value="<?=$arr_result['cidade']?>"></td>
		</tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_estado')?></td>
			<td><input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_estado')?>"    id="emp_estado"    value="<?=$arr_result['estado']?>"></td>
		</tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_pais')?></td>
			<td><input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_pais')?>"      id="emp_pais"      value="<?=$arr_result['pais']?>"></td>
		</tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_tel_p')?></td>
			<td><input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_tel_p')?>"     id="emp_tel_p"     value="<?=$arr_result['tel_princ']?>"></td>
		</tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_tel_s')?></td>
			<td><input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_tel_s')?>"     id="emp_tel_s"     value="<?=$arr_result['tel_sec']?>"></td>
		</tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_email')?></td>
			<td><input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_email')?>"     id="emp_email"     value="<?=$arr_result['email']?>"></td>
		</tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_site')?></td>
			<td><input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_site')?>"      id="emp_site"      value="<?=$arr_result['site']?>"></td>
		</tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_maps')?></td>
			<td><input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_maps')?>"      id="emp_link_map"  value="<?=$arr_result['mapa_link']?>"></td>
		<tr>	
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_nome_prop')?></td>
			<td><input class="input-block-level" type="text" placeholder="<?=$oConfigs->get('cadastro_companies','cad_nome_prop')?>" id="emp_nome_prop" value="<?=$arr_result['nome_proprietario']?>"></td>	
		</tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_companies','cad_coments')?></td>
			<td><textarea class="input-block-level" placeholder="<?=$oConfigs->get('cadastro_companies','cad_coments')?>" rows="4"   id="emp_comentarios"><?=$arr_result['comentarios']?></textarea></td>
		</tr>
	</table>
	<div class="row-fluid">
		<div class="span4">
			<div class="button-action">
				<a class="btn btn-large btn-danger" onclick="ajax_control();">
					<span class="right">
						<i class="icon-large icon-download-alt"></i>
					</span> 
					<?=$oConfigs->get('cadastro_companies','cad_alterar')?>
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
		var id_usuario = "<?=$oUser->get('id')?>"; //PERMANENTE
		var lang       = "<?=$oUser->get('lingua')?>"; //PERMANENTE

		var code_user   = "<?=$oUser->getCodeSecurity( $oUser->get('id').'$H' )?>";
		var code_edit   = "<?=$oUser->getCodeSecurity( $oUser->get('id').'&@' )?>";				
		
		var emp_id          = $("#emp_id").val();		
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
	        url: 'control/ajax/delegate_to/companies/update.php',
	        type: 'POST',
	        data: {
	            'id_usuario':id_usuario,//PERMANENTE
	            'lang':lang,//PERMANENTE
	            
	            'code_user':code_user,
	            'code_edit':code_edit,
	            
	            'emp_id':emp_id,
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
					console.log(data);
					if(data) {
						var obj = JSON.parse(data);
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
	