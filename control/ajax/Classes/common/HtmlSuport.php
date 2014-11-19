<?php

class HtmlSuport {
	
	public function arraybi2table($arr){
		if(sizeof($arr) < 1 ) 
			return;

		$html_ret  = "<table class='table table_empresa'>";		
		
		foreach (  $arr as $value ){
			$html_ret .= "  <tr>";
			$html_ret .= "    <td>".$value[0].":</td>";
			$html_ret .= "    <td>".$value[1]."</td>";
			$html_ret .= "  </tr>";
		}
		$html_ret .= "</table>";
		
		return $html_ret;		

	}
	
	public function pagination($num_rows,$max,$n_atual,$langStr,$urlStr)
	{
		$html = "";
	
		$lang  = "lang=".$langStr."&";
		$p     = "p=b5415e05223570d577345d8d9fc10756";
		$range = "page_n=0&";
		$n     = "n=1&";
		
		$pages = ceil($num_rows / $max);
		
		$n_ini = ($n_atual < 1 ?  1 : $n_atual - 5 ); $n_ini = ($n_ini < 1 ? 1 : $n_ini);
		$n_fim = ($n_fim > $pages ? $pages : $n_atual + 5 ); $n_fim = ($n_fim > $pages ? $pages : $n_fim);
		
		//Caso nao ha o que paginar, retorna nada
		if(!$n_fim) return;
		
		$html .= '<li><a href="logon.php?'.$lang.$n.$range.$p.$urlStr.'">«</a></li>';
	
		for($i = $n_ini; $i <= $n_fim; $i++)
		{
			$range = "page_n=".$page."&";
			$n = "n=".$i."&";
	
			$html .=  '<li class="'. ($n_atual == $i ? "active" : "" ) .'" ><a href="logon.php?'.$lang.$n.$range.$p.$urlStr.'">'.$i.'</a></li>';
	
			$page = ( $page >= $num_rows ? $num_rows : $page + $max);
		}
		
		$range = "page_n=" . ($num_rows - $max) . "&";
		$n     = "n=$pages&";
	
		$html .= '<li><a href="logon.php?'.$lang.$n.$range.$p.$urlStr.'">»</a></li>';
		return $html;
	}
	
	public function orderIcon($atual,$o_by,$o_tg)
	{
		if(!$o_by || !$o_tg) return;
		
		$p = '<span class="icon-chevron-';
		
		if($atual == $o_by) {
			$order_ret = $p . ( $o_tg == 'asc' ? 'up' : 'down') . '"></span>'; 
		}
		else 
			$order_ret = ""; 
		
		return $order_ret;
	}
	
	public function serializeGET($arrGET)
	{
		if(!count($arrGET)) return;
		
		$urlStr = "";
		foreach ($arrGET as $key => $val) {
			$urlStr  .= '&'.$key.'=' . ($val ? $val : '');
		}
		return $urlStr;
		
	}
	
}


?>