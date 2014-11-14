<span class="navbar-search pull-right hidden-phone">
		<input type="text" id="search_in" value="<?=($_GET['s_in'] ? $_GET['s_in'] : '')?>" onclick="cleanSerch('in');" onkeyUp="search('search_in','s_in');" class="search-query span2" placeholder="<?=$oConfigs->get('cadastro_invoice','search_for_invoice')?>">
		<input type="text" id="search_po" value="<?=($_GET['s_po'] ? $_GET['s_po'] : '') ?>" onclick="cleanSerch('po');" onkeyUp="search('search_po','s_po');" class="search-query span2" placeholder="<?=$oConfigs->get('cadastro_invoice','search_for_po')?>">
		<input type="text" id="search_co" value="<?=($_GET['s_co'] ? $_GET['s_co'] : '')?>" onclick="cleanSerch('co');" onkeyUp="search('search_co','s_co');" class="search-query span2" placeholder="<?=$oConfigs->get('cadastro_invoice','search_for_container')?>">
</span>

<script>
	function cleanSerch(field)
	{
		switch(field){
			case 'in':
				$('#search_po').val('');$('#search_co').val('');break;
			case 'po':
				$('#search_in').val('');$('#search_co').val('');break;
			case 'co':
				$('#search_in').val('');$('#search_po').val('');break;				
		}
	}	             

	function search(idStr,lookinfor)
	{

		<? 
		$urlStrAlter = $oHtmlSuport->serializeGET(
			array(
					a_page => $_GET['a_page'],
					o_by => $_GET['o_by'],
					o_tg => $_GET['o_tg'],
					n => $_GET['n'],
					s_special => $_GET['s_special'],
					page_n => $_GET['page_n'],
			)
		);
		?>	
		
		$( "#"+idStr ).keypress(function( event ) {
			  if ( event.which == 13 ) {
			     var search = lookinfor +"="+$('#'+idStr).val() + '<?=$urlStrAlter?>';			     
			     window.location.assign("logon.php?lang=<?=$oUser->get('lingua')?>&p=<?=md5("invoices/listar.php")?>&"+search);
			  }
		});
	}	


</script>