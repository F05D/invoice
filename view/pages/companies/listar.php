<?php 


	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValidacoes = new Validacoes();

	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/companies/Comp.php");
	$oComp = new Comp();
	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/HtmlSuport.php");
	$oHtmlSuport = new HtmlSuport();
	
	
	$arr_result = array();
	$arr_result = $oComp->getList();

?>						

	<div class="well widget">
		<!-- widget header -->
		<div class="widget-header">
			<h3 class="title"><?=$oConfigs->get('cadastro_companies','listar_empresas')?></h3>
		</div>
		<!-- ./ widget header -->
		
		<!-- widget content -->
		<div class="widget-content" id="lista_dados">
			<table class="table table-striped">
				<thead>
					<tr>
						<th><?=$oConfigs->get('cadastro_companies','empresa')?></th>												
						<th><?=$oConfigs->get('cadastro_companies','pais')?></th>
						<th><?=$oConfigs->get('cadastro_companies','estado')?>/<?=$oConfigs->get('cadastro_companies','cidade')?></th>
						<th><?=$oConfigs->get('cadastro_companies','acoes')?></th>												
					</tr>
				</thead>
				<tbody>					
					<?php

					//if(!$mDetect->isMobile() && !$mDetect->isTablet() )
						for ( $i = 0; $i < sizeof($arr_result); $i++ ) {
							print "<tr>";
							print " <td><span onclick=\"togleTable('table_".$arr_result[$i]['id']."')\">" . $arr_result[$i]['nome'] . "</span></td>";														
							print " <td>" . $arr_result[$i]['pais'] . "</td>";
							print " <td>" . $arr_result[$i]['estado'] . " / " .$arr_result[$i]['cidade'] . "</td>";							
							
							$user_code   =  md5($user['usuario'].$user['id'].$user['lingua']);
							$delete_code = md5( $arr_result[$i]['nome'] . $arr_result[$i]['id'] . $arr_result[$i]['estado'] . $arr_result[$i]['email'] . $arr_result[$i]['site'] . $arr_result[$i]['pais'] );
							print " <td><a href='logon.php?lang=".$user['lingua']."&p=" . md5("companies/editar.php") . "&i=" . $arr_result[$i]['id'] . "'><span class='icon-pencil'></span></a>&nbsp;&nbsp;<a href='#' onclick=\"deletar(". $arr_result[$i]['id']. ",'".$arr_result[$i]['nome'] . "','". $delete_code."','".$user_code."');\" ><span class='icon-remove'></span></a></td>";								
							print "</tr>";
														
							$arr = array();
							array_push($arr, array( $oConfigs->get('cadastro_companies','empresa')   ,  $arr_result[$i]['nome']        ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','endereco')  ,  $arr_result[$i]['end']         ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','cidade')    ,  $arr_result[$i]['cidade']       ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','estado')    ,  $arr_result[$i]['estado']       ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','pais')      ,  $arr_result[$i]['pais']         ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','maps_link') ,  "<a href='".$arr_result[$i]['map_ln']."' target='_blank'>". "Clique para ver o mapa" ."</a>") );
							array_push($arr, array( $oConfigs->get('cadastro_companies','tel_princ') ,  $arr_result[$i]['tel_p']        ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','tel_sec')   ,  $arr_result[$i]['tel_s']        ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','cnpj_id')   ,  $arr_result[$i]['cnpj_id']      ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','site')      ,  "<a href='".$arr_result[$i]['site'] . "' target='_blank'>" . $arr_result[$i]['site'] . "</a>" ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','email')     ,  $arr_result[$i]['email']        ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','nome_prop') ,  $arr_result[$i]['nome_prop']    ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','cometarios') ,  $arr_result[$i]['coment']    ) );
							
							print "<tr>"; 
							print "   <td colspan=6>";
							print "		 <div style='display:none' id='table_".$arr_result[$i]['id']."' >";
							print			 $oHtmlSuport->arraybi2table($arr);
							print "      </div>";
							print "	</td>";
							print "</tr>";
						}						
					?>					
				</tbody>
			</table>
			
			<script type="text/javascript">
				function togleTable (id){
					//alert( document.getElementById(id).style.display );
					if( $("#"+id).css('display') == 'none' ) {
						$("#"+id).css('display','block');
					} else {
						$("#"+id).css('display','none');
					}
				}
					
			</script>
			
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
			<a class="btn btn-large btn-primary" onclick="add();">
				<span class="right">
					<i class="icon-large icon-download-alt"></i>
				</span>
				<?=$oConfigs->get('cadastro_companies','add_company')?>
			</a>
		</div>
	</div> 



<!-- AJAX CONTROLS -->
<script>

	function deletar (id,empresa,code,code_user)
	{

		var r = confirm("<?=$oConfigs->get('cadastro_usuario','deseja_deletar_empresa')?> '"+empresa+"' ?");
		if (r == false)  return;

		var id_usuario     = "<?=$user['id']?>";     //PERMANENTE
		var lang           = "<?=$user['lingua']?>"; //PERMANENTE
		var code_auth      = code;
		var code_auth_user = code_user;

		//VERIFICACAO E CONTROLE
        $.ajax({
            url: 'control/ajax/delegate_to/companies/delete.php',
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
					
	function add(){
		window.location = "logon.php?p=<?=md5("companies/cadastrar.php")?>";
	}

	
	</script>