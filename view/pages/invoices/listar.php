<?php 

	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValidacoes = new Validacoes();

	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/invoice/Invoice.php");
	$oInvoice = new Invoice();
	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/HtmlSuport.php");
	$oHtmlSuport = new HtmlSuport();
	
	//Paginacao
	$num_rows = $oInvoice->NumRows();
	$page = 0;
	$pageNow = ( $_GET['page_n'] ? $_GET['page_n'] : 0);
	$max  = 10;
	$n_atual  = $_GET['n'];
	
	$limit = " LIMIT $pageNow,$max ";
	
	$arr_result = array();
	$arr_result = $oInvoice->read(null,$limit);
?>						
	<div class="pagination pagination-primary pagination-mini"><center>
		<ul>				
			<?=$oHtmlSuport->pagination($num_rows,$max,$n_atual,$oUser->get('lingua'))?>				
		</ul>
		</center>
	</div>
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
						<th class=center><center><?=$oConfigs->get('cadastro_invoice','list_dt_vencimento')?></center></th>
						<th class=center><center><?=$oConfigs->get('cadastro_invoice','list_enviado_notificacao_pgto')?></center></th>
						<th class=center><center><?=$oConfigs->get('cadastro_invoice','list_visualizado')?></center></th>
						<th class=center><center><?=$oConfigs->get('cadastro_invoice','list_status')?></center></th>
						<th class=center><center><?=$oConfigs->get('cadastro_invoice','list_acoes')?></center></th>												
					</tr>
				</thead>
				<tbody>					
					<?php

					//if(!$mDetect->isMobile() && !$mDetect->isTablet() )
						foreach ( $arr_result as $val ) {
							
							$code_user   = $oUser->getCodeSecurity( $oUser->get('id')."%#!@X*");
							$code_delete = $oUser->getCodeSecurity( $arr_result[$i]['id']."&OX%h$");
							$strArgsFunc = $val['id'].",'".addslashes($val['invoice_nr'])."','".$code_user."','".$code_delete."'";
								
							
							print "<tr>";
							print " <td>" . $val['invoice_nr'] . "</td>";
							print " <td>" . $val['nome'] . "</td>";
							print " <td class=center><center>" . $oValidacoes->convertDBtoData($val['data_vencimento'], $oUser->get('lingua')) . "</center></td>";
							print " <td class=center><center><img src='library/images/icons/notification_". ($val['notificado'] == 1 ? "ok" : 'no' ).".png' ".($val['status_id'] == 1 ? "" : " class='pointer' onclick=\"sendNot($strArgsFunc)\" " ).">".($val['notificado'] == 1 ? '<br>'.$oValidacoes->convertDBtoDataHr($val['notificado_dt'], $oUser->get('lingua')) : '' ) . "</center></td>";
							print " <td class=center><center><img src='library/images/icons/view_". ($val['visualizado'] == 1 ? "ok" : 'no' ).".png' >".($val['visualizado'] == 1 ? '<br>'.$oValidacoes->convertDBtoDataHr($val['visualizado_dt'], $oUser->get('lingua')) : '') . "</center></td>";
							print " <td class=center><center><img src='library/images/icons/pago_". ($val['status_id'] == 1 ? "ok" : 'no' ).".png' ></center></td>";							
							print " <td class=center><center><a href='logon.php?n=".$n_atual."&page_n=".$pageNow."&lang=".$oUser->get('lingua')."&p=" . md5("invoices/editar.php") . "&i=" . $val['id'] . "'><span class='icon-pencil'></span></a>&nbsp;&nbsp;<a href='#' onclick=\"deletar($strArgsFunc);\" ><span class='icon-remove'></span></a></center></td>";								
							print "</tr>";
						}						
					?>					
				</tbody>
			</table>
		</div>
		<!-- ./ widget content -->
	</div>
	<div class="pagination pagination-primary pagination-mini"><center>
		<ul>				
			<?=$oHtmlSuport->pagination($num_rows,$max,$n_atual,$oUser->get('lingua'))?>				
		</ul>
		</center>
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

	function sendNot(id,invoice,code_user,code_delete)
	{
		var r = confirm("<?=$oConfigs->get('cadastro_invoice','deseja_enviar_notificacao')?> '"+invoice+"' ?");
		if (r == false)  return;
		
		$.ajax({
            url: 'control/ajax/delegate_to/common/send_notif.php',
            type: 'POST',
            data: {
	            'id_usuario':"<?=$oUser->get('id')?>",//PERMANENTE
	            'lang':"<?=$oUser->get('lingua')?>",//PERMANENTE
	            
	            'id':id,
	            'code_user':code_user
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

	
	</script>