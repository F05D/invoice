<?php 

	require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/invoice/Invoice.php");
	$oInvoice = new Invoice();
	
?>
<ul class="stats">
	<li onclick="searchSpecial('pg_ok');" class="pointer">
		<span class="large text-success"><?=$oInvoice->getScores('pagas_ok')?></span>
		<span class="mini muted"><?=$oConfigs->get('cadastro_invoice','stats_info_pagas_ok')?></span>
	</li>
	<li onclick="searchSpecial('pg_no');" class="pointer">
		<span class="large text-error"><?=$oInvoice->getScores('pagas_no')?></span>
		<span class="mini muted"><?=$oConfigs->get('cadastro_invoice','stats_info_pagas_no')?></span>
	</li>
	<li onclick="searchSpecial('pg_ve');" class="pointer">
		<span class="large text-warning"><?=$oInvoice->getScores('a_vencer')?></span>
		<span class="mini muted"><?=$oConfigs->get('cadastro_invoice','stats_info_a_vencer')?></span>
	</li>
	<li onclick="searchSpecial('pg_tt');" class="pointer">
		<span class="large text-info"><?=$oInvoice->getScores('total')?></span>
		<span class="mini muted"><?=$oConfigs->get('cadastro_invoice','stats_info_total')?></span>
	</li>
</ul>


<script>
	function searchSpecial(codeStr)
	{
		<?php 
				$urlStrSearchSpecial = $oHtmlSuport->serializeGET(
					array(
						a_page => $_GET['a_page'],
						o_by => $_GET['o_by'],
						o_tg => $_GET['o_tg'],
						s_in => $_GET['s_in'],
						s_po => $_GET['s_po'],
						s_co => $_GET['s_co'],
						n => $_GET['n'],
						page_n => $_GET['page_n'],							
					)
				);
			?>
		
	     var search = 's_special='+codeStr + '<?=$urlStrSearchSpecial?>';			     
	     window.location.assign("logon.php?lang=<?=$oUser->get('lingua')?>&p=<?=md5("invoices/listar.php")?>&"+search);
	}
</script>