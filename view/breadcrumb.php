<!-- LOCALIZACAO DA PAGINA -->
<!-- NECESSARIO ESTAR EM CONCORDANCIA COM internal.php  -->

<li>
	<a href="logon.php?lang=<?=$oUser->get('lingua')?>&p=<?=md5("logon.php")?>&a_page=&o_by=&o_tg=&s_in=&s_po=&s_co=&s_special=&n=&page_n="><?=$oConfigs->get('general','home')?></a>
	<span class="divider">
		<i class="icon-caret-right"></i>
	</span>
</li>
<?php 
	require 'control/page_selector_breadcrumb.php';
?>




