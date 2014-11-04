<? 
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/companies/Comp.php");
	$oComp = new Comp();
	
	$arr_result_empresas = array();
	$arr_result_empresas = $oComp->read(array('id','nome'));
	
	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/banks/Bank.php");
	$oBank = new Bank();
	
	$arr_result_bancarios = array();
	$arr_result_bancarios = $oBank->read(array('id','banco_nome','swift_code'));
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
						<option value="<?=$val['id']?>"><?=$val['nome']?></option>
					<? } ?>
				</select>	
			</td>
		</tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_nr')?></td>                      <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_nr')?>"                   id="invoice_nr"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_fatura_n')?></td>                <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_fatura_n')?>"             id="invoice_fatura_n"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_fatura_valor')?></td>            <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_fatura_valor')?>"         id="invoice_fatura_valor"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_data_vencimento')?></td>         <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_data_vencimento')?>"      id="invoice_data_vencimento"></td></tr>		
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_banco')?></td>                         
			<td>
				<select class="input-block-level" id="invoice_banco">
					<option value="">- <?=$oConfigs->get('cadastro_invoice','cad_banco')?> -</option>
					<? foreach ( $arr_result_bancarios as $val) { ?> 
						<option value="<?=$val['id']?>"><?=$val['banco_nome']?> (<?=$val['swift_code']?>)</option>
					<? } ?>
				</select>	
			</td>
		</tr>		
	</table>
</div>

<div class="well widget">
	<div class="widget-header">
		<h3 class="title">Dados do Container:</h3>
	</div>
	<table class="table table-hover">	
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_container')?></td>               <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_container')?>"            id="invoice_container"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_booking')?></td>                 <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_booking')?>"              id="invoice_booking"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_tipo')?></td>                    <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_tipo')?>"                 id="invoice_tipo"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_tara')?></td>                    <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_tara')?>"                 id="invoice_tara"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_peso_bruto')?></td>              <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_peso_bruto')?>"           id="invoice_peso_bruto"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_peso_liquido')?></td>            <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_peso_liquido')?>"         id="invoice_peso_liquido"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_qnt')?></td>                     <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_qnt')?>"                  id="invoice_qnt"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_nota_fiscal')?></td>             <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_nota_fiscal')?>"          id="invoice_nota_fiscal"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_lacres')?></td>                  <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_lacres')?>"               id="invoice_lacres"></td></tr>
		<tr><td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_embarque_data')?></td>           <td><input class="span12" type="text" placeholder="<?=$oConfigs->get('cadastro_invoice','cad_invoice_embarque_data')?>"        id="invoice_embarque_data"></td></tr>
		<tr>
			<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','cad_invoice_embarque_confirmacao')?></td>    
			<td>
				<select class="span8"  id="invoice_embarque_confirmacao">
					<option value="">- <?=$oConfigs->get('cadastro_invoice','cad_confirmacao')?> -</option>
					<option value="0"><?=$oConfigs->get('cadastro_invoice','cad_invoice_embarque_confirmacao_no')?></option>
					<option value="1"><?=$oConfigs->get('cadastro_invoice','cad_invoice_embarque_confirmacao_ok')?></option>
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
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','docs_packinglist')?></td>
			    <td>
			    	<span class="lock" id="docs_packinglist_lock" onclick="toggleLock('docs_packinglist_lock','docs_packinglist_lock_status')"><img src="library/images/icons/unlock.png" height="25" width="25"></span><input type="hidden" id="docs_packinglist_lock_status" value="0">			    
			    	<span onclick="clearInput('docs_packinglist','docs_packinglist_info')" class="remove_text"><?=$oConfigs->get('cadastro_invoice','remover')?></span>
			    	<input class="input_file" onchange="change('docs_packinglist','docs_packinglist_info')" type="file" placeholder="<?=$oConfigs->get('cadastro_invoice','docs_packinglist')?>" id="docs_packinglist">
			    	<br>
			    	<span id="docs_packinglist_info"></span>
			    </td>							    		  
			</tr>		 
			<tr>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','docs_formei')?></td>
				<td>
					<span class="lock" id="docs_formei_lock" onclick="toggleLock('docs_formei_lock','docs_formei_lock_status')"><img src="library/images/icons/unlock.png" height="25" width="25"></span><input type="hidden" id="docs_formei_lock_status" value="0">
					<span onclick="clearInput('docs_formei','docs_formei_info')" class="remove_text"><?=$oConfigs->get('cadastro_invoice','remover')?></span>
					<input class="input_file" onchange="change('docs_formei','docs_formei_info')" type="file" placeholder="<?=$oConfigs->get('cadastro_invoice','docs_formei')?>" id="docs_formei">
					<br>
					<span id="docs_formei_info"></span>
				</td>
				
			</tr>
			<tr>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','docs_fumigacao')?></td>
				<td>
					<span class="lock" id="docs_fumigacao_lock" onclick="toggleLock('docs_fumigacao_lock','docs_fumigacao_lock_status')"><img src="library/images/icons/unlock.png" height="25" width="25"></span><input type="hidden" id="docs_fumigacao_lock_status" value="0">
					<span onclick="clearInput('docs_fumigacao','docs_fumigacao_info')" class="remove_text"><?=$oConfigs->get('cadastro_invoice','remover')?></span>
					<input class="input_file" onchange="change('docs_fumigacao','docs_fumigacao_info')" type="file" placeholder="<?=$oConfigs->get('cadastro_invoice','docs_formei')?>" id="docs_fumigacao">
					<br>
					<span id="docs_fumigacao_info"></span>
				</td>
			</tr>
			<tr>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','docs_bl')?></td>
				<td>
					<span class="lock" id="docs_bl_lock" onclick="toggleLock('docs_bl_lock','docs_bl_lock_status')"><img src="library/images/icons/lock.png" height="25" width="25"></span><input type="hidden" id="docs_bl_lock_status" value="1">
					<span onclick="clearInput('docs_bl','docs_bl_info')" class="remove_text"><?=$oConfigs->get('cadastro_invoice','remover')?></span>
					<input class="input_file" onchange="change('docs_bl','docs_bl_info')" type="file" placeholder="<?=$oConfigs->get('cadastro_invoice','docs_formei')?>" id="docs_bl">
					<br>
					<span id="docs_bl_info"></span>
				</td>
			</tr>
			<tr>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','docs_invoice')?></td>
				<td>
					<span class="lock" id="docs_invoice_lock" onclick="toggleLock('docs_invoice_lock','docs_invoice_lock_status')"><img src="library/images/icons/unlock.png" height="25" width="25"></span><input type="hidden" id="docs_invoice_lock_status" value="0">
					<span onclick="clearInput('docs_invoice','docs_invoice_info')" class="remove_text"><?=$oConfigs->get('cadastro_invoice','remover')?></span>
					<input class="input_file" onchange="change('docs_invoice','docs_invoice_info')" type="file" placeholder="<?=$oConfigs->get('cadastro_invoice','docs_formei')?>" id="docs_invoice">
					<br>
					<span id="docs_invoice_info"></span>
				</td>
			</tr>
			<tr>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','docs_ISF')?></td>
				<td>
					<span class="lock" id="docs_ISF_lock" onclick="toggleLock('docs_ISF_lock','docs_ISF_lock_status')"><img src="library/images/icons/unlock.png" height="25" width="25"></span><input type="hidden" id="docs_ISF_lock_status" value="0">
					<span onclick="clearInput('docs_ISF','docs_ISF_info')" class="remove_text"><?=$oConfigs->get('cadastro_invoice','remover')?></span>
					<input class="input_file" onchange="change('docs_ISF','docs_ISF_info')" type="file" class="span12"   id="docs_ISF">
					<br>
					<span id="docs_ISF_info"></span>
				</td>
			</tr>
			<tr>
				<td class="invoice_cadastro"><?=$oConfigs->get('cadastro_invoice','size_total')?></td>
				<td>					
					<span id="docs_size_total_info"></span>
				</td>
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
				<a class="btn btn-large btn-danger" onclick="ajax_control();">
					<span class="right">
						<i class="icon-large icon-download-alt"></i>
					</span> 
					<?=$oConfigs->get('cadastro_invoice','cad_inserir')?>
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

	function toggleLock(idImg,idLock){		
		if( $('#'+idLock).val() == 0 ) { 
			$('#'+idLock).val(1);
			
			$('#'+idImg).html('<img src="library/images/icons/lock.png" height="25" width="25">');
		} else {
			$('#'+idLock).val(0);
			$('#'+idImg).html('<img src="library/images/icons/unlock.png" height="25" width="25">');
		}
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
	        url: 'control/ajax/delegate_to/invoice/create.php',
	        type: 'POST',	       
	        // Form data
	      	data: function(){
                var data = new FormData();

                data.append('step','create');
                
                data.append('id_usuario','<?=$oUser->get('id')?>');
                data.append('lang', '<?=$oUser->get('lingua')?>');                
                data.append('code_user', '<?=$oUser->getCodeSecurity( $oUser->get('id')."_D%F%")?>' );
                data.append('code_create', '<?=$oUser->getCodeSecurity( $oUser->getLastId()."g$@)")?>' );
                
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

                if($('#docs_packinglist').get(0).files[0]) data.append('docs_packinglist_size',$('#docs_packinglist').get(0).files[0]['size']);
                if($('#docs_formei').get(0).files[0]) data.append('docs_formei_size',$('#docs_formei').get(0).files[0]['size']);
                if($('#docs_fumigacao').get(0).files[0]) data.append('docs_fumigacao_size',$('#docs_fumigacao').get(0).files[0]['size']);
                if($('#docs_bl').get(0).files[0]) data.append('docs_bl_size',$('#docs_bl').get(0).files[0]['size']);
                if($('#docs_invoice').get(0).files[0]) data.append('docs_invoice_size',$('#docs_invoice').get(0).files[0]['size']);
                if($('#docs_ISF').get(0).files[0]) data.append('docs_ISF_size',$('#docs_ISF').get(0).files[0]['size']);                
                
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

	function upload() {

		
		var strFiles = "";
		if( $('#docs_packinglist').get(0).files[0] == undefined )  strFiles += " * Packinglist\n"; 
		if( $('#docs_formei').get(0).files[0] == undefined)        strFiles += " * Formei\n";
		if( $('#docs_fumigacao').get(0).files[0] == undefined  )   strFiles += " * Fumigação\n";
		if( $('#docs_bl').get(0).files[0] == undefined  )          strFiles += " * BL.\n";
		if( $('#docs_invoice').get(0).files[0] == undefined  )     strFiles += " * Invoice\n";
		if( $('#docs_ISF').get(0).files[0] == undefined  )         strFiles += " * ISF\n";

		if(strFiles != "") 
		{
			var r = confirm("<?=$oConfigs->get('cadastro_invoice','sem_docs_tem_certeza')?>? \n\n" + strFiles + "\n ???");
			if (r == false)  return;
		}	

		$("#progress_status").scrollToMe();	

	    $.ajax({
	        url: 'control/ajax/delegate_to/invoice/create.php',
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
                
                return data;
            }(),
			success: function (data) {
				console.log(data);
				if(data) {
					var obj = JSON.parse(data);
					if(obj.transaction == 'OK') {
						alert(obj.msg);
						window.location.assign("logon.php?p=<?=md5('invoices/listar.php')?>");
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
</script>
	