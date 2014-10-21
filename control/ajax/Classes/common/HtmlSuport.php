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
}


?>