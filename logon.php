<?php
session_start();

if(!$_SESSION["pincode"])
	$_SESSION["pincode"] = $_GET['pincode'];

if(!$_SESSION["lang"])
	$_SESSION["lang"]    = $_GET['lang'];

if($_SERVER['DOCUMENT_ROOT'] == "/Library/WebServer/Sites") {
	$local_root = $_SERVER['DOCUMENT_ROOT'];
	$local_simbolic = "/invoice";
} else {
	$local_root = $_SERVER['DOCUMENT_ROOT'];
	$local_simbolic = "";
}

//DETECTA O DEVICE UTILIZADO
//$mDetect->isMobile();
//$mDetect->isTablet();
require_once ( dirname(__FILE__) . "/control/ajax/Classes/common/Mobile_detect.php");
$mDetect = new Mobile_Detect ();



//VERIFICA AUTENCIDADE DO USUARIO
//CLASSE USUARIO
require_once ( dirname(__FILE__) . "/control/ajax/Classes/user/User.php");
$oUser = new User();

//CONFIGURACOES E TRADUCOES
require_once ( dirname(__FILE__) . "/control/ajax/Classes/configs/Configs.php");
$oConfigs = new Configs();
$oConfigs->setLanguage( $_SESSION["lang"], false);

if(!$oUser->autenticaUsuario($_SESSION["pincode"])) {	
	header("Location: index.php?error=invalid_user");
	die();
} 

?>
<!DOCTYPE html>
<html>
	<head>
	<!-- arquivos de cabecalho  -->
		<meta charset="utf-8">
		<title><?=$oConfigs->get('index','titulo')?></title>		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?=$oConfigs->get('index','descricao')?>">
		<meta name="author" content="<?=$oConfigs->get('index','descricao')?>">
		<!-- required styles -->
		<link href="library/assets/css/bootstrap.css" rel="stylesheet">
		<link href="library/assets/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="library/css/styles.css" rel="stylesheet">				
	</head>
	<body>			
		<!-- header -->
		<div id="header" class="navbar">
			<div class="navbar-inner">
				<!-- company or app name -->
				<a class="brand hidden-phone" href="index.html"><?=$oConfigs->get('index','titulo')?></a>
				
				<!-- NOTIFICACOES DA BARRA SUPERIOR  -->
				<?php require_once 'view/notifications.php';?>
				
				<!-- INFORMACOES DA CONTA DO USUARIO -->									
				<?php require_once 'view/account.php';?>
				
				<!-- PESQUISA DE INVOICES -->
				<?php require_once 'view/search.php';?>
				
			</div>
		</div>
		<!-- end header -->		
		
		<div id="left_layout">
			<!-- main content -->
			<div id="main_content" class="container-fluid">
			
				<!-- page heading -->
				<div class="page-heading">				
					<h2 class="page-title muted">
						<i class="icon-dashboard"></i> Dashboard
					</h2>	
					<!-- INFO DA PAGINA -->				
					<div class="page-info hidden-phone">
						<?php require_once 'view/infopage.php';?>
					</div>
				</div>
				<!-- ./ page heading -->
				
				<!-- USADO PARA MOSTRAR QUE PAGINA ESTA -->
				<ul class="breadcrumb breadcrumb-main">
					<?php require_once 'view/breadcrumb.php';?>
				</ul>
				
				<!--  PARTE INTERNA DA PAGINA -->
					<!-- post wrapper -->				
					<div class="row-fluid">
						<?php
							require_once 'control/page_selector.php';
						?>
					</div>
					<!-- ./ post wrapper -->
				<!--  ./ PARTE INTERNA DA PAGINA -->
			</div>
			<!-- end main content -->
			
			<!-- sidebar -->
				<?php require_once 'view/sidebar.php';?>
			<!-- end sidebar -->
			
		</div>
		
		<!-- external api -->
		<script src="http://maps.google.com/maps/api/js?v=3.5&sensor=false"></script>
		
		<!-- base -->
		<script src="library/assets/js/jquery.js"></script>
		<script>
			jQuery.fn.extend({
				scrollToMe: function () {
					var x = jQuery(this).offset().top - 100;
					jQuery('html,body').animate({scrollTop: x}, 400);
        	}});
		</script>
		<script src="library/assets/js/bootstrap.min.js"></script>
		
		<!-- addons -->
		<script src="library/plugins/chart-plugins.js"></script>
		<script src="library/plugins/jquery-ui-slider.js"></script>
		<script src="library/plugins/redactor/redactor.min.js"></script>
		<script src="library/plugins/jmapping/markermanager.js"></script>
		<script src="library/plugins/jmapping/StyledMarker.js"></script>
		<script src="library/plugins/jmapping/jquery.metadata.js"></script>
		<script src="library/plugins/jmapping/jquery.jmapping.min.js"></script>
		<script src="library/plugins/jquery.uniform.js"></script>
		<script src="library/plugins/chosen.jquery.min.js"></script>
		<script src="library/plugins/bootstrap-datepicker.js"></script>
		<script src="library/plugins/jquery.timePicker.min.js"></script>
				
		<!-- plugins loader -->
		<script src="library/js/loader.js"></script>
		
	</body>
	
</html>