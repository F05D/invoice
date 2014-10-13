<!-- LOCALIZACAO DA PAGINA -->
<!-- NECESSARIO ESTAR EM CONCORDANCIA COM internal.php  -->

<li>
	<a href="logon.php?lang=<?=$user['lingua']?>&p=<?=md5("logon.php")?>">Home</a>
	<span class="divider">
		<i class="icon-caret-right"></i>
	</span>
</li>
<?php 
	require 'control/page_selector_breadcrumb.php';
?>




