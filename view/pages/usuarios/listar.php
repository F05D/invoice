<?php 

	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValidacoes = new Validacoes();



	$arr_result = array();
	$arr_result = $oUser->getUserList();

	

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

					//if(!$mDetect->isMobile() && !$mDetect->isTablet() )
						for($i = 0; $i < sizeof($arr_result); $i++  ) {
							print "<tr>";
							print " <td>" . $arr_result[$i]['nome'] . "</td>";
							print " <td>GOLDSTONE</td>";
							print " <td>" . $arr_result[$i]['usuario'] . "</td>";
							print " <td>" . $oUser->getAlias_privilegio($arr_result[$i]['privilegios'], $oConfigs) . "</td>";
							print " <td>" . $oValidacoes->convertDBtoData($arr_result[$i]['dt_nascimento'], $user['lingua']) . "</td>";
							print " <td>" .$oUser->getAlias_lingua($arr_result[$i]['lingua'], $oConfigs) . "</td>";
							$user_code   =  md5($user['usuario'].$user['id'].$user['lingua']);
							$delete_code = md5( $arr_result[$i]['nome'] . $arr_result[$i]['id'] . $arr_result[$i]['usuario'] . $arr_result[$i]['dt_nascimento'] . $arr_result[$i]['privilegios'] . $arr_result[$i]['lingua'] );
							print " <td><a href='logon.php?lang=".$user['lingua']."&p=" . md5("usuarios/editar.php") . "&i=" . $arr_result[$i]['id'] . "'><span class='icon-pencil'></span></a>&nbsp;&nbsp;<a href='#' onclick=\"deletar_usuario(". $arr_result[$i]['id']. ",'".$delete_code."','" .$arr_result[$i]['usuario']. "','".$user_code."');\" ><span class='icon-remove'></span></a></td>";								
							print "</tr>";
						}						
					?>					
				</tbody>
			</table>
		</div>
		<div class="widget-content ipad" id="lista_dados_i">
			<table class="table table-striped ">
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
					//if( $mDetect->isTablet() )
						for($i = 0; $i < sizeof($arr_result); $i++  ) {
							print "<tr>";
							print " <td>" . substr($arr_result[$i]['nome'],0,10) . "...</td>";
							print " <td>" . substr("GOLDSTONE",0,10) . "...</td>";
							print " <td>" . substr($arr_result[$i]['usuario'],0,10) . "...</td>";
							print " <td>" . substr($arr_result[$i]['privilegios'],0,3) . "...</td>";
							print " <td>" . substr($arr_result[$i]['dt_nascimento'],0,5) . "</td>";
							print " <td>" . substr($arr_result[$i]['lingua'],0,2) . "</td>";
							$user_code   =  md5($user['usuario'].$user['id'].$user['lingua']);
							$delete_code = md5( $arr_result[$i]['nome'] . $arr_result[$i]['id'] . $arr_result[$i]['usuario'] . $arr_result[$i]['dt_nascimento'] . $arr_result[$i]['privilegios'] . $arr_result[$i]['lingua'] );
							print " <td><a href='logon.php?lang=".$user['lingua']."&p=" . md5("usuarios/editar.php") . "&i=" . $arr_result[$i]['id'] . "'><span class='icon-pencil'></span></a>&nbsp;&nbsp;<a href='#' onclick=\"deletar_usuario(". $arr_result[$i]['id']. ",'".$delete_code."','" .$arr_result[$i]['usuario']. "','".$user_code."');\" ><span class='icon-remove'></span></a></td>";								
							print "</tr>";
						}						
					?>
				</tbody>
			</table>
			</div>
			<div class="widget-content mobile" id="lista_dados_m">
			<table class="table table-striped ">
				<thead>
					<tr>
						<th><?=$oConfigs->get('cadastro_usuario','listagem_nome')?></th>
						<th><?=$oConfigs->get('cadastro_usuario','listagem_empresa')?></th>
						<th><?=$oConfigs->get('cadastro_usuario','listagem_email')?></th>
						<th><?=$oConfigs->get('cadastro_usuario','listagem_acoes')?></th>						
					</tr>
				</thead>
				<tbody>
				<?php 			
					//if( $mDetect->isMobile() )
						for($i = 0; $i < sizeof($arr_result); $i++ ) {
							print "<tr>";
							print " <td>" . substr($arr_result[$i]['nome'],0,10) . "...</td>";
							print " <td>" . substr("GOLDSTONE",0,10) . "...</td>";
							print " <td>" . substr($arr_result[$i]['usuario'],0,10) . "...</td>";
							$user_code   =  md5($user['usuario'].$user['id'].$user['lingua']);
							$delete_code = md5( $arr_result[$i]['nome'] . $arr_result[$i]['id'] . $arr_result[$i]['usuario'] . $arr_result[$i]['dt_nascimento'] . $arr_result[$i]['privilegios'] . $arr_result[$i]['lingua'] );
							print " <td><a href='logon.php?lang=".$user['lingua']."&p=" . md5("usuarios/editar.php") . "&i=" . $arr_result[$i]['id'] . "'><span class='icon-pencil'></span></a>&nbsp;&nbsp;<a href='#' onclick=\"deletar_usuario(". $arr_result[$i]['id']. ",'".$delete_code."','" .$arr_result[$i]['usuario']. "','".$user_code."');\" ><span class='icon-remove'></span></a></td>";								
							print "</tr>";
						}						
					?>
				</tbody>
			</table>
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

	function deletar_usuario(id , code , usuario , code_user)
	{

		var r = confirm("Deseja deletar o usuario '"+usuario+"' ?");
		if (r == false)  return;

		var id_usuario     = "<?=$user['id']?>";     //PERMANENTE
		var lang           = "<?=$user['lingua']?>"; //PERMANENTE
		var code_auth      = code;
		var code_auth_user = code_user;

		//VERIFICACAO E CONTROLE
        $.ajax({
            url: 'control/ajax/delegate_to/user/delete.php',
            type: 'POST',
            data: {
	            'id_usuario':id_usuario,//PERMANENTE
	            'lang':lang,//PERMANENTE
	            
	            'id_delete':id,
	            'code_auth':code_auth,
	            'code_auth_user':code_auth_user
			},  
			dataType:"html",         
			success: function (data) {
				var obj = JSON.parse(data);
				if( obj.transaction == 'OK' ) {			
					window.location.assign("logon.php?p=2288f694a0dc334479e1d95c8b762b20");							
				} else {
					$("#msg").html(obj.msg);
					$("#msg").scrollToMe();
				}				
	      	},
			error: function (data) {
				if(data) { $("#msg").html("Erro ao tentar cadastrar."); $("#msg").scrollToMe(); }
			},
			complete: function () {
				//
			}
		});
	}
					
	function add_user(){
		window.location = "logon.php?p=<?=md5("cadastro_usuarios.php")?>";
	}

	
	</script>