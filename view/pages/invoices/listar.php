<?php 

	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValidacoes = new Validacoes();
	
	//ORDER BY - INI
	$o_by  = $_GET['o_by'];
	$o_tg  = $_GET['o_tg'];
	$orderBy = array(
		'o_by' => $o_by,
		'o_tg' => $o_tg	
	);
	
	//ORDER BY - FIM
	
	//SEARCH - INI
	
	$search = array(
			's_in' => ($_GET['s_in'] ? $_GET['s_in'] : ''),
			's_po' => ($_GET['s_po'] ? $_GET['s_po'] : ''),
			's_co' => ($_GET['s_co'] ? $_GET['s_co'] : ''),
			's_special' => ($_GET['s_special'] ? $_GET['s_special'] : '')			
	);
	
	//SEARCH - FIM
	$max  = 10;
	$pageNow = ( $_GET['page_n'] ? $_GET['page_n'] : 0);
	$limit = " LIMIT $pageNow,$max ";
	
	$arr_result = array();
	$arr_result = $oInvoice->read(null,$limit,$search,$orderBy, $_arrUserPerm);
	$num_rows   = $oInvoice->numRows($search);
	
	//FOR PAGINATION
	$urlStrAlter = $oHtmlSuport->serializeGET(
			array(
					a_page => $_GET['a_page'],
					o_by => $_GET['o_by'],
					o_tg => $_GET['o_tg'],
					s_in => $_GET['s_in'],
					s_po => $_GET['s_po'],
					s_co => $_GET['s_co'],
					s_special => $_GET['s_special'],
			)
	);	
	
?>						
	<div class="well widget">	
		<!-- widget header -->
		<div class="widget-header">
			<h3 class="title"><?=$oConfigs->get('cadastro_invoice','selector_listagem')?></h3>
		</div>
		
		<!-- //TODO: Paginacao: perguntar se eh necessario 
		<div class="pagination pagination-primary pagination-mini">
			
			<center><ul><?=$oHtmlSuport->pagination($num_rows,$max,$_GET['n'],$oUser->get('lingua'),$urlStrAlter)?></ul></center>
		</div>
		 -->
		
		<!-- ./ widget header -->
		
		<!-- widget content -->
		<div class="widget-content" id="lista_dados">
			<table class="table table-striped">
				<thead>
					<tr>
						<th><span onclick="orderBy('in');" class="pointer"><?=$oConfigs->get('cadastro_invoice','list_invoice')?></span> <?=$oHtmlSuport->orderIcon('in',$o_by,$o_tg)?></th>
						<th><span onclick="orderBy('po');" class="pointer"><?=$oConfigs->get('cadastro_invoice','list_po')?> </span> <?=$oHtmlSuport->orderIcon('po',$o_by,$o_tg)?></th>
						<th><span onclick="orderBy('co');" class="pointer"><?=$oConfigs->get('cadastro_invoice','list_container')?> </span> <?=$oHtmlSuport->orderIcon('co',$o_by,$o_tg)?></th>																		
						<th><span onclick="orderBy('em');" class="pointer"><?=$oConfigs->get('cadastro_invoice','list_empresa')?> </span> <?=$oHtmlSuport->orderIcon('em',$o_by,$o_tg)?></th>			
						<th><span onclick="orderBy('ve');" class="pointer center"><?=$oConfigs->get('cadastro_invoice','list_dt_vencimento')?> </span> <?=$oHtmlSuport->orderIcon('ve',$o_by,$o_tg)?></th>
						<?php if($_arrUserPerm['priv'] == 1 ) { ?><th><span onclick="orderBy('nt');" class="pointer center"><?=$oConfigs->get('cadastro_invoice','list_enviado_notificacao_pgto')?> </span> <?=$oHtmlSuport->orderIcon('nt',$o_by,$o_tg)?></th><?php }?>
						<?php if($_arrUserPerm['priv'] == 1 ) { ?><th><span onclick="orderBy('vi');" class="pointer center"><?=$oConfigs->get('cadastro_invoice','list_visualizado')?> </span> <?=$oHtmlSuport->orderIcon('vi',$o_by,$o_tg)?></th><?php }?>
						<th><span onclick="orderBy('st');" class="pointer center"><?=$oConfigs->get('cadastro_invoice','list_status')?></span> <?=$oHtmlSuport->orderIcon('st',$o_by,$o_tg)?></th>
						<th><?=$oConfigs->get('cadastro_invoice','list_acoes')?></th>									
					</tr>
				</thead>
				<tbody>					
					<?php

					//if(!$mDetect->isMobile() && !$mDetect->isTablet() )
						foreach ( $arr_result as $val ) {
							
							$code_user   = $oUser->getCodeSecurity( $oUser->get('id') . "%#!@X*");
							$code_delete = $oUser->getCodeSecurity( $val['id'] . "&OX%h$");
							$strArgsFunc = $val['id'].",'".addslashes($val['invoice_nr'])."','".$code_user."','".$code_delete."'";
							
							print "<tr>";
							print " <td>" . $val['invoice_nr'] . "</td>";
							print " <td>" . $val['po'] . "</td>";
							print " <td>" . $val['container'] . "</td>";
							print " <td>" . $val['nome'] . "</td>";
							print " <td class=center><center>" . $oValidacoes->convertDBtoData($val['data_vencimento'], $oUser->get('lingua')) . "</center></td>";
							
							if($_arrUserPerm['priv'] == 1 ) { 
								print " <td class=center><center><img src='library/images/icons/notification_". ($val['notificado'] == 1 ? "ok" : 'no' ).".png' ".($val['status_id'] == 1 ? "" : " class='pointer' onclick=\"sendNot($strArgsFunc)\" " ).">".($val['notificado'] == 1 ? '<br>'.$oValidacoes->convertDBtoDataHr($val['notificado_dt'], $oUser->get('lingua')) : '' ) . "</center></td>";							
								print " <td class=center><center><img src='library/images/icons/view_". ($val['visualizado'] == 1 ? "ok" : 'no' ).".png' >".($val['visualizado'] == 1 ? '<br>'.$oValidacoes->convertDBtoDataHr($val['visualizado_dt'], $oUser->get('lingua')) : '') . "</center></td>";
							}
							
							print " <td class=center><center><img src='library/images/icons/pago_". ($val['status_id'] == 1 ? "ok" : 'no' ).".png' ></center></td>";							
							print " <td class=center><center>";
							print       "<a href='logon.php?lang=".$oUser->get('lingua')."&p=" . md5("invoices/editar.php") . "&i=" . $val['id'] . $urlStrSelector . "'><span class='icon-pencil'></span></a>&nbsp;&nbsp;";
							
							if($_arrUserPerm['priv'] == 1 )
								print	"<span onclick=\"deletar($strArgsFunc);\" class='icon-remove pointer'></span></center></td>";								
							
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
			<?=$oHtmlSuport->pagination($num_rows,$max,$_GET['n'],$oUser->get('lingua'),$urlStrAlter)?>				
		</ul>
		</center>
	</div>
	
	
	<div class="control-group">								
		<div class="controls alert-error">
			<i><span id="msg"></span></i>
		</div>
	</div>
	<?php if($_arrUserPerm['priv'] == 1 ) {?>
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
	<?php }?>



<!-- AJAX CONTROLS -->
<script>
	
	function orderBy(order_field) {
		<?php
			$urlStrAlter = $oHtmlSuport->serializeGET(
					array(
							a_page => $_GET['a_page'],
							o_tg => ( $_GET['o_tg'] == 'asc' ? 'desc' : 'asc'),
							s_in => $_GET['s_in'],
							s_po => $_GET['s_po'],
							s_co => $_GET['s_co'],
							s_special => $_GET['s_special'],
							n => $_GET['n'],
							page_n => $_GET['page_n'],
					)
			);
		?>
		var order = "&o_by=" + order_field + "<?=$urlStrAlter?>";
		window.location.assign("logon.php?p=<?=md5("invoices/listar.php")?>" + order);
	}
					
	<?php if($_arrUserPerm['priv'] == 1 ) { ?>
	
	function deletar(id,invoice,code_user,code_delete)
	{
	
		var r = confirm("<?=$oConfigs->get('cadastro_invoice','deseja_deletar_invoice')?> '"+invoice+"' ?");
		if (r == false)  return;

		//VERIFICACAO E CONTROLE
        $.ajax({
            url: 'control/ajax/delegate_to/invoice/delete.php',
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
					window.location.assign("logon.php?p=<?=md5("invoices/listar.php")?><?=$urlStrSelector?>");							
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
		window.location = "logon.php?p=<?=md5("invoices/cadastrar.php")?><?=$urlStrSelector?>";
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
					window.location.assign("logon.php?p=<?=md5("invoices/listar.php")?><?=$urlStrSelector?>");							
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
	<?php }?>
	</script>