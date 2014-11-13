<?php 

require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
$oValidacoes = new Validacoes();
?>						

	<div class="well widget">
		<!-- widget header -->
		<div class="widget-header">
			<h3 class="title"><?=$oConfigs->get('cadastro_usuario','lista_usuar')?></h3>
		</div>
		<!-- ./ widget header -->
		
		<!-- widget content -->
		<div class="widget-content full" id="lista_dados_f">
			<table class="table table-striped">
				<thead>
					<tr>
						<th><?=$oConfigs->get('cadastro_usuario','listagem_nome')?></th>
						<th><?=$oConfigs->get('cadastro_usuario','listagem_empresa')?></th>
						<th><?=$oConfigs->get('cadastro_usuario','listagem_email')?></th>
						<th><?=$oConfigs->get('cadastro_usuario','listagem_priv')?></th>
						<th><?=$oConfigs->get('cadastro_usuario','listagem_dt_nasc')?></th>
						<th><?=$oConfigs->get('cadastro_usuario','listagem_lingua')?></th>
						<th><?=$oConfigs->get('cadastro_usuario','listagem_acoes')?></th>						
					</tr>
				</thead>
				<tbody>					
					<?php
					
					$arr_result = array();
					$arr_result = $oUser->getUserList();
					
					
					//if(!$mDetect->isMobile() && !$mDetect->isTablet() )
						for($i = 0; $i < sizeof($arr_result); $i++  ) {
							
							print "<tr>";
							print " <td>" . $arr_result[$i]['nome'] . "</td>";
							print " <td>" . $arr_result[$i]['empresa_nome'] . "</td>";
							print " <td>" . $arr_result[$i]['usuario'] . "</td>";
							print " <td>" . $oUser->getAlias_privilegio($arr_result[$i]['privilegios'], $oConfigs) . "</td>";
							print " <td>" . $oValidacoes->convertDBtoData($arr_result[$i]['dt_nascimento'], $user['lingua']) . "</td>";
							print " <td>" .$oUser->getAlias_lingua($arr_result[$i]['lingua'], $oConfigs) . "</td>";
							
							$code_user   = $oUser->getCodeSecurity($oUser->get('id'));
							$code_delete = $oUser::getCodeSecurity($arr_result[$i]['id']);								
							
							print " <td><a href='logon.php?lang=".$oUser->get('lingua')."&p=" . md5("usuarios/editar.php") . "&i=" . $arr_result[$i]['id'] . "'><span class='icon-pencil'></span></a>&nbsp;&nbsp;<a href='#' onclick=\"deletar(". $arr_result[$i]['id']. ",'" .$arr_result[$i]['usuario']. "','".$code_user."','".$code_delete."');\" ><span class='icon-remove'></span></a></td>";								
							print "</tr>";
						}						
					?>					
				</tbody>
			</table>
		</div>
		<div class="widget-content ipad" id="lista_dados_i">
			<!-- TODO:DADOS IPAD -->
		</div>
		<div class="widget-content mobile" id="lista_dados_m">
			<!-- TODO:DADOS CEL -->
		</div>
		<!-- ./ widget content -->
	</div>
	
	<div class="control-group">								
		<div class="controls alert-error">
			<i><span id="msg"></span></i>
		</div>
	</div>
	
	<div class="span4">
		<div class="button-action">
			<a class="btn btn-large btn-primary" onclick="add_user();"><span class="right"><i class="icon-large icon-download-alt"></i></span><?=$oConfigs->get('cadastro_usuario','add_user')?></a>
		</div>
	</div> 



<!-- AJAX CONTROLS -->
<script>

	function deletar(id,usuario,code_user,code_delete)
	{

		var r = confirm("Deseja deletar o usuario '"+usuario+"' ?");
		if (r == false)  return;

		var id_usuario     = "<?=$oUser->get('id')?>";     //PERMANENTE
		var lang           = "<?=$oUser->get('lingua')?>"; //PERMANENTE		

		//VERIFICACAO E CONTROLE
        $.ajax({
            url: 'control/ajax/delegate_to/user/delete.php',
            type: 'POST',
            data: {
	            'id_usuario':id_usuario,//PERMANENTE
	            'lang':lang,//PERMANENTE
	            
	            'id_delete':id,
	            'code_user':code_user,
	            'code_delete':code_delete
			},  
			dataType:"html",         
			success: function (data) {
				console.log(data);
				
				var obj = JSON.parse(data);
				if( obj.transaction == 'OK' ) {			
					window.location.assign("logon.php?p=<?=md5("usuarios/listar.php")?>");							
				} else {
					$("#msg").html(obj.msg);
					$("#msg").scrollToMe();
				}				
	      	},
			error: function (data) {
				console.log(data);
				var obj = JSON.parse(data);
				if(data) { 
					$("#msg").html(obj.msg);
				 	$("#msg").scrollToMe(); 
				 }
			},
			complete: function () {
				//
			}
		});
	}
					
	function add_user(){
		window.location = "logon.php?p=<?=md5("usuarios/cadastrar.php")?>";
	}

	
	</script>