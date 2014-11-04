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
	
	public function pagination($num_rows,$max,$n_atual,$langStr)
	{
		$html = "";
	
		$lang  = "lang=".$langStr."&";
		$p     = "p=b5415e05223570d577345d8d9fc10756";
		$range = "page_n=1&";
		$n     = "n=1&";
	
		$pages = round(($num_rows / $max) , 0 , PHP_ROUND_HALF_UP);
		$n_ini = ($n_atual < 1 ?  1 : $n_atual - 5 ); $n_ini = ($n_ini < 1 ? 1 : $n_ini);
		$n_fim = ($n_fim > $pages ? $pages : $n_atual + 5 ); $n_fim = ($n_fim > $pages ? $pages : $n_fim);
	
		$html .= '<li><a href="logon.php?'.$lang.$n.$range.$p.'">«</a></li>';
	
		for($i = $n_ini; $i <= $n_fim; $i++)
		{
		$range = "page_n=".$page."&";
				$n = "n=".$i."&";
	
				$html .=  '<li class="'. ($n_atual == $i ? "active" : "" ) .'" ><a href="logon.php?'.$lang.$n.$range.$p.'">'.$i.'</a></li>';
	
			$page = ( $page >= $num_rows ? $num_rows : $page + $max);
		}
	
		$range = "page_n=$pages&";
		$n     = "n=$pages&";
	
		$html .= '<li><a href="logon.php?'.$lang.$n.$range.$p.'">»</a></li>';
		return $html;
	}
	
}


?>