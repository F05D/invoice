<?
//REMOUNT
$urlStrSelector = $oHtmlSuport->serializeGET(
		array(
				a_page => 'us',
				o_by => $_GET['o_by'],
				o_tg => $_GET['o_tg'],
				s_in => $_GET['s_in'],
				s_po => $_GET['s_po'],
				s_co => $_GET['s_co'],
				s_special => $_GET['s_special'],
				n => $_GET['n'],
				page_n => $_GET['page_n'],
		)
);
?>
<!-- dropdown user account -->
<ul class="nav pull-right">
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<i class="icon-large icon-user"></i>
		</a>
		<ul class="dropdown-menu dropdown-user-account">
			<li class="account-info">
				<h3>-> <?=$oUser->get('nome')?></h3>
				<p></p>
				<p>
				
				<?php					
					$edit_user = "logon.php?lang=".$_SESSION["lang"]."&p=" . md5("usuarios/editar.php") . "&i=" . $oUser->get('id') . $urlStrSelector;
				?>
					<a href="<?=$edit_user?>"><?=$oConfigs->get('cadastro_usuario','editar')?></a>
				</p>
			</li>
			<li class="account-footer">
				<div class="row-fluid">
					<div class="span4 align-right">
						<a class="btn btn-small btn-danger btn-flat" href="#" onclick="logout()"><?=$oConfigs->get('cadastro_usuario','logout')?></a>
					</div>
				</div>
			</li>
		</ul>
	</li>
</ul>

<script>
	function logout(){
		var r = confirm("<?=$oConfigs->get('cadastro_usuario','deseja_sair_sistema')?>");
		if (r == false)  return;
		window.location.assign("index.php");
	}
</script>