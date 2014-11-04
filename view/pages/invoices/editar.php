<? 

	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Validacoes.php");
	$oValidacoes = new Validacoes();

	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/common/Support.php");
	$oSupport = new Support();

	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/invoice/Invoice.php");
	$oInvoice = new Invoice();

	if($_GET['i']) {
		$arr_result = $oInvoice->get($_GET['i'])[0];
	}
	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/companies/Comp.php");
	$oComp = new Comp();
	
	$arr_result_empresas = array();
	$arr_result_empresas = $oComp->read(array('id','nome'));
	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/banks/Bank.php");
	$oBank = new Bank();
	
	$arr_result_bancarios = array();
	$arr_result_bancarios = $oBank->read(array('id','banco_nome','swift_code'));
	
	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/invoice/Invoice.php");
	$oInvoice = new Invoice();
		
	$arrDocs = array();
	$arrDocs = $oInvoice->getDocs($_GET['i']);
	
	$urlPagination = "page_n=".$_GET['page_n']."&n=".$_GET['n'];

?>


<div class="well widget">
	<div class="widget-header">
		<h3 class="title"><?=$oConfigs->get('cadastro_invoice','cadastro_empresas')?>Dados da Invoice:</h3>
	</div>
	<table class="table table-hover">
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_empresa')?></td>                         
			<td>
				<select class="input-block-level" id="invoice_empresa">
					<option value="">- <?=$oConfigs->get('cadastro_invoice','cad_empresa')?> -</option>
					<? foreach ( $arr_result_empresas as $val) { ?> 
						<option value="<?=$val['id']?>" <?=($arr_result['company_id'] == $val['id'] ? 'selected' : '')?> ><?=$val['nome']?></option>
					<? } ?>
				</select>	
			</td>
		</tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_nr')?></td>                      <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_nr')?>"                   id="invoice_nr"              value="<?=$arr_result['invoice_nr']?>"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_fatura_n')?></td>                <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_fatura_n')?>"             id="invoice_fatura_n"        value="<?=$arr_result['fatura_n']?>"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_fatura_valor')?></td>            <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_fatura_valor')?>"         id="invoice_fatura_valor"    value="<?=$arr_result['fatura_valor']?>"></td></tr>
		
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_data_vencimento')?></td>         <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_data_vencimento')?>"      id="invoice_data_vencimento" value="<? print $oValidacoes->convertDBtoData( $arr_result['data_vencimento'], $oUser->get('lingua'));?>"></td></tr>		
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_banco')?></td>                         
			<td>
				<select class="input-block-level" id="invoice_banco">
					<option value="">- <?=$oConfigs->get('cadastro_invoice','cad_banco')?> -</option>
					<? foreach ( $arr_result_bancarios as $val) { ?> 
						<option value="<?=$val['id']?>"  <?=($arr_result['bancarios_id'] == $val['id'] ? 'selected' : '')?> ><?=$val['banco_nome']?> (<?=$val['swift_code']?>)</option>
					<? } ?>
				</select>	
			</td>
		</tr>		
	</table>
</div>


<div class="well widget">
	<div class="widget-header">
		<h3 class="title"><?=$oConfigs->get('cadastro_invoice','invoice_status')?>:</h3>
	</div>
	<table class="table table-hover">
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','update_status')?></td>                         
			<td>
				<select class="input-block-level" id="invoice_status">
						<option value="" <?=($arr_result['status_id'] == "" ? 'selected' : '')?> > - <?=$oConfigs->get('cadastro_invoice','status_invoice')?> - </option>
						<option value="1" <?=($arr_result['status_id'] == "1" ? 'selected' : '')?> > <?=$oConfigs->get('cadastro_invoice','status_id_1_pago')?> </option>
						<option value="2" <?=($arr_result['status_id'] == "2" ? 'selected' : '')?> > <?=$oConfigs->get('cadastro_invoice','status_id_2_nao_pago')?> </option>
				</select>
			</td>
		</tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','up_notificado')?></td>   <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','up_notificado')?>"  readonly value="<?=($arr_result['notificado'] == 1 ? $oConfigs->get('cadastro_invoice','sim') . " - " . $arr_result['notificado_dt'] : $oConfigs->get('cadastro_invoice','nao') ) ?>"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','up_vizualidado')?></td>  <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','up_vizualidado')?>" readonly value="<?=($arr_result['visualizado'] == 1 ? $oConfigs->get('cadastro_invoice','sim') . " - " . $arr_result['visualizado_dt']  : $oConfigs->get('cadastro_invoice','nao') ) ?>"></td></tr>	
	</table>
</div>


<div class="well widget">
	<div class="widget-header">
		<h3 class="title">Dados do Container:</h3>
	</div>
	<table class="table table-hover">	
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_container')?></td>               <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_container')?>"            id="invoice_container"     value="<?=$arr_result['container']?>"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_booking')?></td>                 <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_booking')?>"              id="invoice_booking"       value="<?=$arr_result['booking']?>"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_tipo')?></td>                    <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_tipo')?>"                 id="invoice_tipo"          value="<?=$arr_result['tipo']?>"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_tara')?></td>                    <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_tara')?>"                 id="invoice_tara"          value="<?=$arr_result['tara']?>"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_peso_bruto')?></td>              <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_peso_bruto')?>"           id="invoice_peso_bruto"    value="<?=$arr_result['peso_bruto']?>"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_peso_liquido')?></td>            <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_peso_liquido')?>"         id="invoice_peso_liquido"  value="<?=$arr_result['peso_liquido']?>"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_qnt')?></td>                     <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_qnt')?>"                  id="invoice_qnt"           value="<?=$arr_result['qnt']?>"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_nota_fiscal')?></td>             <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_nota_fiscal')?>"          id="invoice_nota_fiscal"   value="<?=$arr_result['nota_fiscal']?>"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_lacres')?></td>                  <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_lacres')?>"               id="invoice_lacres"        value="<?=$arr_result['lacres']?>"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_embarque_data')?></td>           <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_embarque_data')?>"        id="invoice_embarque_data" value="<? print $oValidacoes->convertDBtoData( $arr_result['embarque_data'], $oUser->get('lingua'));?>" ></td></tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_embarque_confirmacao')?></td>    
			<td>
				<select class="span8"  id="invoice_embarque_confirmacao">
					<option value=""   >- <?=$oConfigs->get('cadastro_invoice','cad_confirmacao')?> -</option>
					<option value="0" <?=($arr_result['embarque_confirmacao'] == 0 ? 'selected' : '')?> ><?=$oConfigs->get('cadastro_invoice','cad_invoice_embarque_confirmacao_no')?></option>
					<option value="1" <?=($arr_result['embarque_confirmacao'] == 1 ? 'selected' : '')?> ><?=$oConfigs->get('cadastro_invoice','cad_invoice_embarque_confirmacao_ok')?></option>
				</select>
			</td>
		</tr>
	</table>
</div>

<div class="well widget">
	<div class="widget-header">
		<h3 class="title">Documentos:</h3>
	</div>
	<form enctype="multipart/form-data">	
		<table class="table table-hover">
			<tr>
				<td><h5><?=$oConfigs->get('cadastro_invoice','doc_documento')?></h5></td>
			    <td><h5><?=$oConfigs->get('cadastro_invoice','doc_arquivo_rem_lock')?></h5></td>
			    <td><h5><?=$oConfigs->get('cadastro_invoice','doc_visual')?></h5></td>
			    <td><h5><?=$oConfigs->get('cadastro_invoice','doc_download')?></h5></td>							    		  
			</tr>	
			<tr>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','docs_packinglist')?></td>
			    <td>
			    	<?php $docType = 1; ?>
			    	<span class="lock" id="docs_packinglist_lock" onclick="toggleLock('docs_packinglist_lock','docs_packinglist_lock_status','docs_packinglist_status_changes')">
			    		<img src="library/images/icons/<?=($arrDocs[$docType]['locked'] == 1 ? '' : 'un')?>lock.png" height="25" width="25">
			    	</span>			    	
			    	<input type="hidden" id="docs_packinglist_lock_status" value="<?=$arrDocs[$docType]['locked']?>">
			    				    
			    	<span onclick="removeClear('docs_packinglist','docs_packinglist_info')" class="remove_text"><?=$oConfigs->get('cadastro_invoice','remover')?></span>
			    	<input class="input_file" onchange="change('docs_packinglist','docs_packinglist_info')" type="file" placeholder="<?=$oConfigs->get('cadastro_invoice','docs_packinglist')?>" id="docs_packinglist">
			    	<br>
			    	<span id="docs_packinglist_info">
				    	<?php
				    		print $oSupport->getReadableFileSizeString($arrDocs[$docType]['size'])." -> ". $arrDocs[$docType]['type'] . "<br>";
				    		print $arrDocs[$docType]['name'];
				    	?>
			    	</span>
			    	<input id="docs_packinglist_status_changes" type="hidden" value="no_changes">
			    	<input id="docs_packinglist_name" type="hidden" value="<?=$arrDocs[$docType]['local_name_md5']?>">
			    </td>
			    <td><? print ($arrDocs[$docType]['visualizado'] == 1 ? $oValidacoes->convertDBtoDataHr( $arrDocs[$docType]['visualizado_dt'],$oUser->get('lingua') ) : $oConfigs->get('cadastro_invoice','nao') )?></td>
				
				<?php 				
				$code_user     = $oUser->getCodeSecurity( $oUser->get('id') . '%34DsX9' );
				$code_download = $oUser->getCodeSecurity( '%34DsX9'.$arrDocs[$docType]['local_name_md5'] );				
				if($arrDocs[$docType]['local_name_md5']) { ?> 
					<td><span class="icon-download-alt lock" onclick="download('<?=$arrDocs[$docType]['local_name_md5']?>','<?=$docType?>','<?=$code_user?>','<?=$code_download?>');"></span></td>
				<?php } else { ?>
					<td><span class="micon-blocked"></span></td>
				<?php } ?>
			    
			</tr>		 
			<tr>
				<?php $docType = 2; ?>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','docs_formei')?></td>
				<td>
					<span class="lock" id="docs_formei_lock" onclick="toggleLock('docs_formei_lock','docs_formei_lock_status','docs_formei_status_changes')">
					<img src="library/images/icons/<?=($arrDocs[$docType]['locked'] == 1 ? '' : 'un')?>lock.png" height="25" width="25"></span>
					<input type="hidden" id="docs_formei_lock_status" value="<?=$arrDocs[$docType]['locked']?>">
					<span onclick="removeClear('docs_formei','docs_formei_info')" class="remove_text"><?=$oConfigs->get('cadastro_invoice','remover')?></span>
					<input class="input_file" onchange="change('docs_formei','docs_formei_info')" type="file" placeholder="<?=$oConfigs->get('cadastro_invoice','docs_formei')?>" id="docs_formei">
					<br>
					<span id="docs_formei_info">
						<?php
				    		print $oSupport->getReadableFileSizeString($arrDocs[$docType]['size'])." -> ". $arrDocs[$docType]['type'] . "<br>";
				    		print $arrDocs[$docType]['name'];
				    	?>
			    	</span>
			    	<input id="docs_formei_status_changes" type="hidden" value="no_changes">
			    	<input id="docs_formei_name" type="hidden" value="<?=$arrDocs[$docType]['local_name_md5']?>">
				</td>
				<td><? print ($arrDocs[$docType]['visualizado'] == 1 ? $oValidacoes->convertDBtoDataHr( $arrDocs[$docType]['visualizado_dt'],$oUser->get('lingua') ) : $oConfigs->get('cadastro_invoice','nao') )?></td>
				
				<?php 				
				$code_user     = $oUser->getCodeSecurity( $oUser->get('id') . '%34DsX9' );
				$code_download = $oUser->getCodeSecurity( '%34DsX9'.$arrDocs[$docType]['local_name_md5'] );				
				if($arrDocs[$docType]['local_name_md5']) { ?> 
					<td><span class="icon-download-alt lock" onclick="download('<?=$arrDocs[$docType]['local_name_md5']?>','<?=$docType?>','<?=$code_user?>','<?=$code_download?>');"></span></td>
				<?php } else { ?>
					<td><span class="micon-blocked"></span></td>
				<?php } ?>
				
			</tr>
			<tr>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','docs_fumigacao')?></td>
				<td>
					<?php $docType = 3; ?>				
					<span class="lock" id="docs_fumigacao_lock" onclick="toggleLock('docs_fumigacao_lock','docs_fumigacao_lock_status','docs_fumigacao_status_changes')">
					<img src="library/images/icons/<?=($arrDocs[$docType]['locked'] == 1 ? '' : 'un')?>lock.png" height="25" width="25"></span>
					<input type="hidden" id="docs_fumigacao_lock_status" value="<?=$arrDocs[$docType]['locked']?>">
					<span onclick="removeClear('docs_fumigacao','docs_fumigacao_info')" class="remove_text"><?=$oConfigs->get('cadastro_invoice','remover')?></span>
					<input class="input_file" onchange="change('docs_fumigacao','docs_fumigacao_info')" type="file" placeholder="<?=$oConfigs->get('cadastro_invoice','docs_formei')?>" id="docs_fumigacao">
					<br>
					<span id="docs_fumigacao_info">
						<?php
				    		print $oSupport->getReadableFileSizeString($arrDocs[$docType]['size'])." -> ". $arrDocs[$docType]['type'] . "<br>";
				    		print $arrDocs[$docType]['name'];
				    	?>					
					</span>
					<input id="docs_fumigacao_status_changes" type="hidden" value="no_changes">
					<input id="docs_fumigacao_name" type="hidden" value="<?=$arrDocs[$docType]['local_name_md5']?>">
				</td>
				<td><? print ($arrDocs[$docType]['visualizado'] == 1 ? $oValidacoes->convertDBtoDataHr( $arrDocs[$docType]['visualizado_dt'],$oUser->get('lingua') ) : $oConfigs->get('cadastro_invoice','nao') )?></td>
				
				<?php 				
				$code_user     = $oUser->getCodeSecurity( $oUser->get('id') . '%34DsX9' );
				$code_download = $oUser->getCodeSecurity( '%34DsX9'.$arrDocs[$docType]['local_name_md5'] );				
				if($arrDocs[$docType]['local_name_md5']) { ?> 
					<td><span class="icon-download-alt lock" onclick="download('<?=$arrDocs[$docType]['local_name_md5']?>','<?=$docType?>','<?=$code_user?>','<?=$code_download?>');"></span></td>
				<?php } else { ?>
					<td><span class="micon-blocked"></span></td>
				<?php } ?>
				
			</tr>
			<tr>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','docs_bl')?></td>
				<td>
					<?php $docType = 4; ?>
					<span class="lock" id="docs_bl_lock" onclick="toggleLock('docs_bl_lock','docs_bl_lock_status','docs_bl_status_changes')">
					<img src="library/images/icons/<?=($arrDocs[$docType]['locked'] == 1 ? '' : 'un')?>lock.png" height="25" width="25"></span>
					<input type="hidden" id="docs_bl_lock_status" value="<?=$arrDocs[$docType]['locked']?>">
					<span onclick="removeClear('docs_bl','docs_bl_info')" class="remove_text"><?=$oConfigs->get('cadastro_invoice','remover')?></span>
					<input class="input_file" onchange="change('docs_bl','docs_bl_info')" type="file" placeholder="<?=$oConfigs->get('cadastro_invoice','docs_formei')?>" id="docs_bl">
					<br>
					<span id="docs_bl_info">
						<?php
				    		print $oSupport->getReadableFileSizeString($arrDocs[$docType]['size'])." -> ". $arrDocs[$docType]['type'] . "<br>";
				    		print $arrDocs[$docType]['name'];
				    	?>
					</span>
					<input id="docs_bl_status_changes" type="hidden" value="no_changes">
					<input id="docs_bl_name" type="hidden" value="<?=$arrDocs[$docType]['local_name_md5']?>">
				</td>
				<td><? print ($arrDocs[$docType]['visualizado'] == 1 ? $oValidacoes->convertDBtoDataHr( $arrDocs[$docType]['visualizado_dt'],$oUser->get('lingua') ) : $oConfigs->get('cadastro_invoice','nao') )?></td>
				
				<?php 				
				$code_user     = $oUser->getCodeSecurity( $oUser->get('id') . '%34DsX9' );
				$code_download = $oUser->getCodeSecurity( '%34DsX9'.$arrDocs[$docType]['local_name_md5'] );				
				if($arrDocs[$docType]['local_name_md5']) { ?> 
					<td><span class="icon-download-alt lock" onclick="download('<?=$arrDocs[$docType]['local_name_md5']?>','<?=$docType?>','<?=$code_user?>','<?=$code_download?>');"></span></td>
				<?php } else { ?>
					<td><span class="micon-blocked"></span></td>
				<?php } ?>				
			</tr>
			<tr>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','docs_invoice')?></td>
				<td>
					<?php $docType = 5; ?>
					<span class="lock" id="docs_invoice_lock" onclick="toggleLock('docs_invoice_lock','docs_invoice_lock_status','docs_invoice_status_changes')">
					<img src="library/images/icons/<?=($arrDocs[$docType]['locked'] == 1 ? '' : 'un')?>lock.png" height="25" width="25"></span>
					<input type="hidden" id="docs_invoice_lock_status" value="<?=$arrDocs[$docType]['locked']?>">
					<span onclick="removeClear('docs_invoice','docs_invoice_info')" class="remove_text"><?=$oConfigs->get('cadastro_invoice','remover')?></span>
					<input class="input_file" onchange="change('docs_invoice','docs_invoice_info')" type="file" placeholder="<?=$oConfigs->get('cadastro_invoice','docs_formei')?>" id="docs_invoice">
					<br>
					<span id="docs_invoice_info">
						<?php
				    		print $oSupport->getReadableFileSizeString($arrDocs[$docType]['size'])." -> ". $arrDocs[$docType]['type'] . "<br>";
				    		print $arrDocs[$docType]['name'];
				    	?>
					</span>
					<input id="docs_invoice_status_changes" type="hidden" value="no_changes">
					<input id="docs_invoice_name" type="hidden" value="<?=$arrDocs[$docType]['local_name_md5']?>">
				</td>
				<td><? print ($arrDocs[$docType]['visualizado'] == 1 ? $oValidacoes->convertDBtoDataHr( $arrDocs[$docType]['visualizado_dt'],$oUser->get('lingua') ) : $oConfigs->get('cadastro_invoice','nao') )?></td>
				
				<?php 				
				$code_user     = $oUser->getCodeSecurity( $oUser->get('id') . '%34DsX9' );
				$code_download = $oUser->getCodeSecurity( '%34DsX9'.$arrDocs[$docType]['local_name_md5'] );				
				if($arrDocs[$docType]['local_name_md5']) { ?> 
					<td><span class="icon-download-alt lock" onclick="download('<?=$arrDocs[$docType]['local_name_md5']?>','<?=$docType?>','<?=$code_user?>','<?=$code_download?>');"></span></td>
				<?php } else { ?>
					<td><span class="micon-blocked"></span></td>
				<?php } ?>
			</tr>
			<tr>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','docs_ISF')?></td>
				<td>
					<?php $docType = 6; ?>
					<span class="lock" id="docs_ISF_lock" onclick="toggleLock('docs_ISF_lock','docs_ISF_lock_status','docs_ISF_status_changes')">
					<img src="library/images/icons/<?=($arrDocs[$docType]['locked'] == 1 ? '' : 'un')?>lock.png" height="25" width="25"></span>
					<input type="hidden" id="docs_ISF_lock_status" value="<?=$arrDocs[$docType]['locked']?>">
					<span onclick="removeClear('docs_ISF','docs_ISF_info')" class="remove_text"><?=$oConfigs->get('cadastro_invoice','remover')?></span>
					<input class="input_file" onchange="change('docs_ISF','docs_ISF_info')" type="file" class="span12"   id="docs_ISF">
					<br>
					<span id="docs_ISF_info">
						<?php
				    		print $oSupport->getReadableFileSizeString($arrDocs[$docType]['size'])." -> ". $arrDocs[$docType]['type'] . "<br>";
				    		print $arrDocs[$docType]['name'];
				    	?>
					</span>
					<input id="docs_ISF_status_changes" type="hidden" value="no_changes">
					<input id="docs_ISF_name" type="hidden" value="<?=$arrDocs[$docType]['local_name_md5']?>">
				</td>
				<td><? print ($arrDocs[$docType]['visualizado'] == 1 ? $oValidacoes->convertDBtoDataHr( $arrDocs[$docType]['visualizado_dt'],$oUser->get('lingua') ) : $oConfigs->get('cadastro_invoice','nao') )?></td>
				
				<?php 				
				$code_user     = $oUser->getCodeSecurity( $oUser->get('id') . '%34DsX9' );
				$code_download = $oUser->getCodeSecurity( '%34DsX9'.$arrDocs[$docType]['local_name_md5'] );				
				if($arrDocs[$docType]['local_name_md5']) { ?> 
					<td><span class="icon-download-alt lock" onclick="download('<?=$arrDocs[$docType]['local_name_md5']?>','<?=$docType?>','<?=$code_user?>','<?=$code_download?>');"></span></td>
				<?php } else { ?>
					<td><span class="micon-blocked"></span></td>
				<?php } ?>
			</tr>
			<tr>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','size_total')?></td>
				<td>					
					<span id="docs_size_total_info"></span>
				</td>
				<td></td>
			</tr>
		</table>
	</form>
</div>


	<div class="well widget">					
		<!-- ** widget header ** -->
		<div class="widget-header">
			<h3 class="title">Progresso:</h3>
		</div>		
		<!-- ** ./ widget header ** -->
		<div class="progress progress-striped active">
			<div class="bar" style="width: 0%" id="progress_status"></div>
		</div>	
	</div>


	<div class="row-fluid">
		<div class="span4">
			<div class="button-action">
				<a class="btn btn-large btn-bm" onclick="ajax_control();">
					<span class="right">
						<i class="icon-large icon-download-alt"></i>
					</span> 
					<?=$oConfigs->get('cadastro_invoice','update_salvar')?>
				</a>
			</div>
		</div>	
		<div class="span4">
			<div class="button-action">
				<a class="btn btn-large btn-danger" onclick="reload();">
					<span class="right">
						<i class="icon-large icon-info-sign"></i>
					</span> 
					<?=$oConfigs->get('cadastro_invoice','update_desfazer')?>
				</a>
			</div>
		</div>		
	</div>
	
	<div class="control-group">								
		<div class="controls alert-error">
			<i><span id="msg"></span></i>
		</div>
	</div>

<!-- AJAX CONTROLS -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script lang="javascript">

	function download(code,type,code_user,code_download){

		var url = "";
		url += "code="+code+"&";
		url += "id_usuario=<?=$oUser->get('id')?>&";
		url += "id=<?=$_GET['i']?>&";
		url += "lang=<?=$oUser->get('lingua')?>&";
		url += "type="+type+"&";
		url += "code_user="+code_user+"&";
		url += "code_download="+code_download;

		window.location.assign("control/ajax/delegate_to/invoice/downloadFile.php?"+url);
	}
				    	
	function toggleLock(idImg,idLock,statusChanges){		
		if( $('#'+idLock).val() == 0 ) { 
			$('#'+idLock).val(1);			
			$('#'+idImg).html('<img src="library/images/icons/lock.png" height="25" width="25">');
		} else {
			$('#'+idLock).val(0);
			$('#'+idImg).html('<img src="library/images/icons/unlock.png" height="25" width="25">');
		}
		
		$('#'+statusChanges).val('change');		
		console.log($('#'+idLock).val());
		
	}

	function getReadableFileSizeString(fileSizeInBytes) {
	
	    var i = -1;
	    var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
	    do {
	        fileSizeInBytes = fileSizeInBytes / 1024;
	        i++;
	    } while (fileSizeInBytes > 1024);
	
	    return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
	}

	function removeClear(strID,span){
				
		$('#'+strID+'_status_changes').val('remove');
		clearInput(strID,span);
	}
	
	function clearInput(strID,span) {
		$('#'+strID).replaceWith( $("#"+ strID).clone() );
		 $('#'+span).html("");
		 sumSizers();
	}
	
	 function change(fileStr,span) {
	    var file = $('#'+fileStr).get(0).files[0];
	    var size = file.size;
	    var name = file.name;
	    var type = file.type;

	    if(!type)
		{
		    alert("<?=$oConfigs->get('cadastro_invoice','formato_invalido')?>");
		    clearInput(fileStr,span)
	    } else {
	    	$('#'+span).html( getReadableFileSizeString(size) + " -> " + type + "<br>" + name );
	    }

		//Nome nao serah utilizado na gravacao do arquivo, somente a titulo de validacao
	    setNameMd5(file.name,fileStr+'_name');
	    
	    $('#'+fileStr+'_status_changes').val('change');
	    sumSizers();	    	  	    
	 }
	 
	 function sumSizers(){
		
         var sum1 = ( $('#docs_packinglist').get(0).files[0] ? $('#docs_packinglist').get(0).files[0]['size'] : 0 );
         var sum2 = ( $('#docs_formei').get(0).files[0] ? $('#docs_formei').get(0).files[0]['size'] : 0 );
         var sum3 = ( $('#docs_fumigacao').get(0).files[0] ? $('#docs_fumigacao').get(0).files[0]['size'] : 0 );
         var sum4 = ( $('#docs_bl').get(0).files[0] ? $('#docs_bl').get(0).files[0]['size'] : 0 );
         var sum5 = ( $('#docs_invoice').get(0).files[0] ? $('#docs_invoice').get(0).files[0]['size'] : 0 );
         var sum6 = ( $('#docs_ISF').get(0).files[0] ? $('#docs_ISF').get(0).files[0]['size'] : 0 );

		var sum = sum1 + sum2 + sum3 + sum4 + sum5 + sum6;
         
		 $('#docs_size_total_info').html( getReadableFileSizeString(sum) );
	 }
	 
	function ajax_control() {

	    $.ajax({
	        url: 'control/ajax/delegate_to/invoice/update.php',
	        type: 'POST',	       
	        // Form data
	      	data: function(){
                var data = new FormData();

                data.append('step','update');
                
                data.append('id_usuario','<?=$oUser->get('id')?>');
                data.append('lang', '<?=$oUser->get('lingua')?>');                
                data.append('code_user', '<?=$oUser->getCodeSecurity( $oUser->get('id')."!_D(F*" )?>' );                
                data.append('code_update', '<?=$oUser->getCodeSecurity( $_GET['i'] . "X#$_)" )?>' );
                
                data.append('id_invoice', '<?=$_GET['i']?>');
                data.append('invoice_nr', $('#invoice_nr').val());
                data.append('invoice_fatura_n', $('#invoice_fatura_n').val());
                data.append('invoice_fatura_valor', $('#invoice_fatura_valor').val());
                data.append('invoice_data_vencimento', $('#invoice_data_vencimento').val());
                data.append('invoice_empresa', $('#invoice_empresa').val());
                data.append('invoice_container', $('#invoice_container').val());
                data.append('invoice_booking', $('#invoice_booking').val());
                data.append('invoice_tipo', $('#invoice_tipo').val());
                data.append('invoice_tara', $('#invoice_tara').val());                
                data.append('invoice_peso_bruto', $('#invoice_peso_bruto').val());
                data.append('invoice_peso_liquido', $('#invoice_peso_liquido').val());
                data.append('invoice_qnt', $('#invoice_qnt').val());                
                data.append('invoice_nota_fiscal', $('#invoice_nota_fiscal').val());
                data.append('invoice_lacres', $('#invoice_lacres').val());            
                data.append('invoice_embarque_data', $('#invoice_embarque_data').val());
                data.append('invoice_embarque_confirmacao', $('#invoice_embarque_confirmacao').val() );
                data.append('invoice_banco', $('#invoice_banco').val() );
                data.append('invoice_status', $('#invoice_status').val() );
                
                if($('#docs_packinglist').get(0).files[0]) {
                    data.append('docs_packinglist_size',$('#docs_packinglist').get(0).files[0]['size']);
                }
                data.append('docs_packinglist_lock_status',$('#docs_packinglist_lock_status').val());
                data.append('docs_packinglist_status_changes',$('#docs_packinglist_status_changes').val());
            
                
                if($('#docs_formei').get(0).files[0]) { 
                    data.append('docs_formei_size',$('#docs_formei').get(0).files[0]['size']);
                }
                data.append('docs_formei_lock_status',$('#docs_formei_lock_status').val());
                data.append('docs_formei_status_changes',$('#docs_formei_status_changes').val());
                
                if($('#docs_fumigacao').get(0).files[0]) {
                    data.append('docs_fumigacao_size',$('#docs_fumigacao').get(0).files[0]['size']);
                }                
                data.append('docs_fumigacao_lock_status',$('#docs_fumigacao_lock_status').val());
                data.append('docs_fumigacao_status_changes',$('#docs_fumigacao_status_changes').val());
                
                if($('#docs_bl').get(0).files[0]) {
                    data.append('docs_bl_size',$('#docs_bl').get(0).files[0]['size']);
                }
                data.append('docs_bl_lock_status',$('#docs_bl_lock_status').val());
                data.append('docs_bl_status_changes',$('#docs_bl_status_changes').val());
                
                if($('#docs_invoice').get(0).files[0]) {
                    data.append('docs_invoice_size',$('#docs_invoice').get(0).files[0]['size']);
                }
                data.append('docs_invoice_lock_status',$('#docs_invoice_lock_status').val());
                data.append('docs_invoice_status_changes',$('#docs_invoice_status_changes').val());
                
                if($('#docs_ISF').get(0).files[0]) {
                    data.append('docs_ISF_size',$('#docs_ISF').get(0).files[0]['size']);
                }                
                data.append('docs_ISF_lock_status',$('#docs_ISF_lock_status').val());                
                data.append('docs_ISF_status_changes',$('#docs_ISF_status_changes').val());

                data.append('docs_packinglist_name',$('#docs_packinglist_name').val());
                data.append('docs_formei_name',$('#docs_formei_name').val());
                data.append('docs_fumigacao_name',$('#docs_fumigacao_name').val());
                data.append('docs_bl_name',$('#docs_bl_name').val());
                data.append('docs_invoice_name',$('#docs_invoice_name').val());
                data.append('docs_ISF_name',$('#docs_ISF_name').val());                
                
                
                return data;
            }(),
			success: function (data) {
				console.log(data);
				if(data) {
					var obj = JSON.parse(data);
					if(obj.transaction == 'OK') {
						upload();
					} else {
						$("#msg").html(obj.msg);
						$("#msg").html(obj.msg);
						$("#msg").scrollToMe();
					}
				}	
	      	},
			error: function (data) {
								
			},
			complete: function () {
				//
			},
			cache: false,
	        contentType: false,
	        processData: false
		});	
	}	

	function upload() 
	{
		var strFiles = "";
		if( !$('#docs_packinglist_name').val() )  strFiles += " * Packinglist\n"; 
		if( !$('#docs_formei_name').val() )       strFiles += " * Formei\n";
		if( !$('#docs_fumigacao_name').val() )    strFiles += " * Fumigação\n";
		if( !$('#docs_bl_name').val() )           strFiles += " * BL.\n";
		if( !$('#docs_invoice_name').val() )      strFiles += " * Invoice\n";
		if( !$('#docs_ISF_name').val() )          strFiles += " * ISF\n";

		if(strFiles != "") 
		{
			var r = confirm("<?=$oConfigs->get('cadastro_invoice','sem_docs_tem_certeza')?>? \n\n" + strFiles + "\n ???");
			if (r == false)  return;
		}	

		$("#progress_status").scrollToMe();	

	    $.ajax({
	        url: 'control/ajax/delegate_to/invoice/update.php',
	        type: 'POST',
	        xhr: function() {  // Custom XMLHttpRequest
	            var myXhr = $.ajaxSettings.xhr();
	            if(myXhr.upload){ // Check if upload property exists
	                myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
	            }
	            return myXhr;
	        },
	        // Form data
	      	data: function(){
                var data = new FormData();
                
                data.append('id_usuario','<?=$oUser->get('id')?>');
                data.append('lang', '<?=$oUser->get('lingua')?>');                
                data.append('code_user', '<?=$oUser->getCodeSecurity( $oUser->get('id')."_D%F%")?>' );
                data.append('code_create', '<?=$oUser->getCodeSecurity( $oUser->getLastId()."g$@)")?>' );

                data.append('docs_packinglist',$('#docs_packinglist').get(0).files[0]);
                data.append('docs_formei',$('#docs_formei').get(0).files[0]);
                data.append('docs_fumigacao',$('#docs_fumigacao').get(0).files[0]);
                data.append('docs_bl',$('#docs_bl').get(0).files[0]);
                data.append('docs_invoice',$('#docs_invoice').get(0).files[0]);
                data.append('docs_ISF',$('#docs_ISF').get(0).files[0]);

                data.append('docs_packinglist_lock_status',$('#docs_packinglist_lock_status').val());
                data.append('docs_formei_lock_status',$('#docs_formei_lock_status').val());
                data.append('docs_fumigacao_lock_status',$('#docs_fumigacao_lock_status').val());
                data.append('docs_bl_lock_status',$('#docs_bl_lock_status').val());
                data.append('docs_invoice_lock_status',$('#docs_invoice_lock_status').val());
                data.append('docs_ISF_lock_status',$('#docs_ISF_lock_status').val());

                data.append('docs_packinglist_status_changes',$('#docs_packinglist_status_changes').val());
                data.append('docs_formei_status_changes',$('#docs_formei_status_changes').val());
                data.append('docs_fumigacao_status_changes',$('#docs_fumigacao_status_changes').val());
                data.append('docs_bl_status_changes',$('#docs_bl_status_changes').val());
                data.append('docs_invoice_status_changes',$('#docs_invoice_status_changes').val());
                data.append('docs_ISF_status_changes',$('#docs_ISF_status_changes').val());  

                data.append('docs_packinglist_name',$('#docs_packinglist_name').val());
                data.append('docs_formei_name',$('#docs_formei_name').val());
                data.append('docs_fumigacao_name',$('#docs_fumigacao_name').val());
                data.append('docs_bl_name',$('#docs_bl_name').val());
                data.append('docs_invoice_name',$('#docs_invoice_name').val());
                data.append('docs_ISF_name',$('#docs_ISF_name').val());  

                data.append('id_invoice', '<?=$_GET['i']?>');
                data.append('invoice_nr', $('#invoice_nr').val());
                data.append('invoice_fatura_n', $('#invoice_fatura_n').val());
                data.append('invoice_fatura_valor', $('#invoice_fatura_valor').val());
                data.append('invoice_data_vencimento', $('#invoice_data_vencimento').val());
                data.append('invoice_empresa', $('#invoice_empresa').val());
                data.append('invoice_container', $('#invoice_container').val());
                data.append('invoice_booking', $('#invoice_booking').val());
                data.append('invoice_tipo', $('#invoice_tipo').val());
                data.append('invoice_tara', $('#invoice_tara').val());                
                data.append('invoice_peso_bruto', $('#invoice_peso_bruto').val());
                data.append('invoice_peso_liquido', $('#invoice_peso_liquido').val());
                data.append('invoice_qnt', $('#invoice_qnt').val());                
                data.append('invoice_nota_fiscal', $('#invoice_nota_fiscal').val());
                data.append('invoice_lacres', $('#invoice_lacres').val());            
                data.append('invoice_embarque_data', $('#invoice_embarque_data').val());
                data.append('invoice_embarque_confirmacao', $('#invoice_embarque_confirmacao').val() );
                data.append('invoice_banco', $('#invoice_banco').val() );
                data.append('invoice_status', $('#invoice_status').val() );
                
                return data;
            }(),
			success: function (data) {
				console.log(data);
				if(data) {
					var obj = JSON.parse(data);
					if(obj.transaction == 'OK') {
						alert(obj.msg);
						window.location.assign("logon.php?<?=$urlPagination?>&p=<?=md5('invoices/listar.php')?>");
					} else {
						$("#msg").html(obj.msg);
						$("#msg").html(obj.msg);
						$("#msg").scrollToMe();
					}
				}					
	      	},
			error: function (data) {
				if(data) $("#msg").html("<?=$oConfigs->get('cadastro_invoice','erro_ao_cadastrar')?>");
			},
			complete: function () {
				//
			},
			cache: false,
	        contentType: false,
	        processData: false
		});	
	}	
	
	function progressHandlingFunction(e){
	    if(e.lengthComputable){
			var x = (( 100 * e.loaded ) / e.total) + "%";
	    	$('#progress_status').css({ width:x});
	    }
	}


	function setNameMd5(valor,inputStr)
	{		
		
		$.ajax({
	        url: 'control/ajax/delegate_to/common/md5.php',
	        type: 'POST',
	        data: {
	            'valor':valor
			},
			dataType:"html",         
			success: function (data) {
				$('#'+inputStr).val(data);				
	      	},
			error: function (data) {
				$('#'+inputStr).val('Error:'+data);	
			},
			complete: function () {
				//
			}
		});	
	}

	function reload(){
		var r = confirm("<?=$oConfigs->get('cadastro_invoice','desfazer_certeza')?>");
		if (r == false)  return;

		location.reload();
	}
		
	
</script>
	