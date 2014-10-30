<?php 

	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValidacoes = new Validacoes();

	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/invoice/Invoice.php");
	$oInvoice = new Invoice();
	
	$arr_result = array();
	$arr_result = $oInvoice->read();
	
?>						

	<div class="well widget">
		<!-- widget header -->
		<div class="widget-header">
			<h3 class="title"><?=$oConfigs->get('cadastro_invoice','selector_listagem')?></h3>
		</div>
		<!-- ./ widget header -->
		
		<!-- widget content -->
		<div class="widget-content" id="lista_dados">
			<table class="table table-striped">
				<thead>
					<tr>
						<th><?=$oConfigs->get('cadastro_invoice','list_invoice')?></th>												
						<th><?=$oConfigs->get('cadastro_invoice','list_empresa')?></th>						
						<th><?=$oConfigs->get('cadastro_invoice','list_dt_vencimento')?></th>
						<th><?=$oConfigs->get('cadastro_invoice','list_enviado_notificacao_pgto')?></th>
						<th><?=$oConfigs->get('cadastro_invoice','list_visualizado')?></th>
						<th><?=$oConfigs->get('cadastro_invoice','list_status')?></th>
						<th><?=$oConfigs->get('cadastro_invoice','list_acoes')?></th>												
					</tr>
				</thead>
				<tbody>					
					<?php

					//if(!$mDetect->isMobile() && !$mDetect->isTablet() )
						for ( $i = 0; $i < sizeof($arr_result); $i++ ) {
							print "<tr>";
							print " <td>" . $arr_result[$i]['invoice_nr'] . "</td>";
							print " <td>" . $arr_result[$i]['nome'] . "</td>";
							print " <td>" . $arr_result[$i]['data_vencimento'] . "</td>";
							print " <td>" . $arr_result[$i]['notificado'] . "</td>";
							print " <td>" . $arr_result[$i]['visualizado'] . "</td>";
							print " <td>" . $arr_result[$i]['status_id']. "</td>";
							
							$code_user   = $oUser->getCodeSecurity( $oUser->get('id')."%#!@X*");
							$code_delete = $oUser->getCodeSecurity( $arr_result[$i]['id']."&OX%h$");
							
							$strArgsFunc = $arr_result[$i]['id'].",'".addslashes($arr_result[$i]['invoice_nr'])."','".$code_user."','".$code_delete."'";
							print " <td><a href='logon.php?lang=".$oUser->get('lingua')."&p=" . md5("invoices/editar.php") . "&i=" . $arr_result[$i]['id'] . "'><span class='icon-pencil'></span></a>&nbsp;&nbsp;<a href='#' onclick=\"deletar($strArgsFunc);\" ><span class='icon-remove'></span></a></td>";								
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
			<a class="btn btn-large btn-primary" onclick="add();">
				<span class="right">
					<i class="icon-large icon-download-alt"></i>
				</span>
				<?=$oConfigs->get('cadastro_invoice','add_invoice')?>
			</a>
		</div>
	</div> 



<!-- AJAX CONTROLS -->
<script>

	function deletar(id,invoice,code_user,code_delete)
	{
		var r = confirm("<?=$oConfigs->get('cadastro_invoice','deseja_deletar_invoice')?> '"+invoice+"' ?");
		if (r == false)  return;

		//VERIFICACAO E CONTROLE
        $.ajax({
            url: 'control/ajax/delegate_to/invoices/delete.php',
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
					window.location.assign("logon.php?p=<?=md5("invoices/listar.php")?>");							
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
		window.location = "logon.php?p=<?=md5("invoices/cadastrar.php")?>";
	}

	
	</script>