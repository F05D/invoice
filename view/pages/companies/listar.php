<?php 

	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValidacoes = new Validacoes();

	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/companies/Comp.php");
	$oComp = new Comp();
	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/HtmlSuport.php");
	$oHtmlSuport = new HtmlSuport();
	
	
	$arr_result = array();
	$arr_result = $oComp->read();
	
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
							print "<tr class='pointer'>";
							print " <td><span onclick=\"togleTable('table_".$arr_result[$i]['id']."')\">" . $arr_result[$i]['nome'] . "</span></td>";														
							print " <td>" . $arr_result[$i]['pais'] . "</td>";
							print " <td>" . $arr_result[$i]['estado'] . " / " .$arr_result[$i]['cidade'] . "</td>";							
							
							$code_user   = $oUser->getCodeSecurity( $oUser->get('id')."$!@u*");
							$code_delete = $oUser->getCodeSecurity( $arr_result[$i]['id']."&%h@");
							$strArgsFunc = $arr_result[$i]['id'].",'".addslashes($arr_result[$i]['nome'])."','".$code_user."','".$code_delete."'";
							print " <td><a href='logon.php?lang=".$oUser->get('lingua')."&p=" . md5("companies/editar.php") . "&i=" . $arr_result[$i]['id'] . "'><span class='icon-pencil'></span></a>&nbsp;&nbsp;<a href='#' onclick=\"deletar($strArgsFunc);\" ><span class='icon-remove'></span></a></td>";								
							print "</tr>";
														
							$arr = array();
							array_push($arr, array( $oConfigs->get('cadastro_companies','empresa')   ,  $arr_result[$i]['nome']        ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','endereco')  ,  $arr_result[$i]['endereco']         ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','cidade')    ,  $arr_result[$i]['cidade']       ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','estado')    ,  $arr_result[$i]['estado']       ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','pais')      ,  $arr_result[$i]['pais']         ) );							

							if($arr_result[$i]['site']) $link_map = "<a href='".$arr_result[$i]['site'] . "' target='_blank'>" . $arr_result[$i]['site'] . "</a>";
							else $link_map = "<a href='#'> ? </a>";
							array_push($arr, array( $oConfigs->get('cadastro_companies','site'),$link_map ) );								
							
							if($arr_result[$i]['mapa_link']) $link_map = "<a href='".$arr_result[$i]['mapa_link']."' target='_blank'>". "Clique para ver o mapa" ."</a>";
							else $link_map = "<a href='#'> ? </a>";
							array_push($arr, array( $oConfigs->get('cadastro_companies','maps_link') ,  $link_map) );
							
							array_push($arr, array( $oConfigs->get('cadastro_companies','tel_princ') ,  $arr_result[$i]['tel_princ']        ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','tel_sec')   ,  $arr_result[$i]['tel_sec']        ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','cnpj_id')   ,  $arr_result[$i]['cnpj_id']      ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','email')     ,  $arr_result[$i]['email']        ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','nome_prop') ,  $arr_result[$i]['nome_proprietario']    ) );
							array_push($arr, array( $oConfigs->get('cadastro_companies','cometarios') ,  $arr_result[$i]['comentarios']    ) );
							
							$arr_bind = array();
							$arr_bind = $oComp->getBindUser($arr_result[$i]['id']);
							$strUser = "";
							foreach ($arr_bind as $val) {
								$strUser .= $val['nome'] . " (" . $val['usuario'] . ")<br>"; 
							}	
							
							array_push($arr, array( $oConfigs->get('cadastro_companies','usuarios_cadastrados') ,  $strUser    ) );
							
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

	function deletar(id,empresa,code_user,code_delete)
	{
		debugger
		var r = confirm("<?=$oConfigs->get('cadastro_usuario','deseja_deletar_empresa')?> '"+empresa+"' ?");
		if (r == false)  return;

		//VERIFICACAO E CONTROLE
        $.ajax({
            url: 'control/ajax/delegate_to/companies/delete.php',
            type: 'POST',
            data: {
	            'id_usuario':"<?=$oUser->get('id')?>",//PERMANENTE
	            'lang':"<?=$oUser->get('lingua')?>",//PERMANENTE
	            
	            'id_delete':id,
	            'code_user':code_user,
	            'code_delete':code_delete
			},  
			dataType:"html",         
			success: function (data) {
				console.log(data);				
				var obj = JSON.parse(data);
				if( obj.transaction == 'OK' ) {			
					window.location.assign("logon.php?p=<?=md5("companies/listar.php")?>");							
				} else {
					$("#msg").html(obj.msg);
					$("#msg").scrollToMe();
				}				
	      	},
			error: function (data) {
				if(data) { 
					$("#msg").html("Erro ao tentar cadastrar."); 
					$("#msg").scrollToMe(); 
				}
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