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
		<div>
			<div id="main_content" class="container-fluid">
				<div class="row-fluid">
					<div class="well widget">
						<div class="widget-header">
							<h3 class="title"><?=$oConfigs->get('index','titulo')?></h3>
						</div>
						<div class="form-horizontal">
						
							<div class="control-group">								
								<div class="controls alert-error">
									<i>
										<span id="msg">
											<?=$error?>
										</span>
									</i>
								</div>
							</div>							
						
						
							<div class="control-group">
								<div class="controls">
									<button type="button" class="btn btn-cn" onclick="continuar()"><?=$oConfigs->get('login','continuar')?></button>																	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	<script src="library/assets/js/jquery.js"></script>
	<script>
			jQuery.fn.extend({
				scrollToMe: function () {
					var x = jQuery(this).offset().top - 100;
					jQuery('html,body').animate({scrollTop: x}, 400);
        	}});
	</script>
	<script>
		function continuar() {
			window.location.assign("index.php");
		}	
	</script>
</html>