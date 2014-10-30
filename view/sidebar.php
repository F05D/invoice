<!-- sidebar -->
<ul id="sidebar" class="nav nav-pills nav-stacked">
	<li class="active"><a href="index.html"> <i class="micon-screen"></i> <span
			class="hidden-phone">INVOICES</span>
	</a></li>
	<li class="dropdown"><a href="#" class="dropdown-toggle"
		data-toggle="dropdown"> <i class="micon-gift"></i> <span
			class="hidden-phone">Cadastros</span>
	</a>
		<ul class="dropdown-menu">
		
			<li><a href="logon.php?lang=<?=$oUser->get('lingua')?>&p=<?=md5("invoices/listar.php")?>"> <i class="icon-large icon-th"></i>
					Lista de Invoices
			</a></li>
			<li><a href="logon.php?lang=<?=$oUser->get('lingua')?>&p=<?=md5("usuarios/listar.php")?>"> <i class="icon-large icon-th"></i>
					Lista de Usuários
			</a></li>
			<li><a href="logon.php?lang=<?=$oUser->get('lingua')?>&p=<?=md5("companies/listar.php")?>"> <i class="icon-large icon-th"></i>
					Lista de Empresas
			</a></li>

			
		</ul></li>
	<li><a href="charts.html"> <i class="micon-stats-up"></i> <span
			class="hidden-phone">Charts</span>
	</a></li>
	<li><a href="maps.html"> <i class="micon-location"></i> <span
			class="hidden-phone">Maps</span>
	</a></li>
	<li class="dropdown"><a href="#" class="dropdown-toggle"
		data-toggle="dropdown"> <i class="micon-checkbox"></i> <span
			class="hidden-phone">Form</span>
	</a>
		<ul class="dropdown-menu">
			<li><a href="form-elements.html"> <i class="icon-large icon-th-large"></i>
					Form elements
			</a></li>
			<li><a href="form-input-sizes.html"> <i
					class="icon-large icon-th-large"></i> Input Size
			</a></li>
			<li><a href="form-control-states.html"> <i
					class="icon-large icon-th-large"></i> Form control states
			</a></li>
			<li><a href="wysiwyg.html"> <i class="icon-large icon-th-large"></i>
					WYSIWYG
			</a></li>
		</ul></li>
	<li><a href="widgets.html"> <i class="micon-lab"></i> <span
			class="hidden-phone">Widgets</span>
	</a></li>
</ul>
<!-- end sidebar -->