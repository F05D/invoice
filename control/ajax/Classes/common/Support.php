<?php

class Support {
	
	/*
	 * Funcao utilizada para transcrever um resultado de banco de dados para uma lista de arrays;
	 * $resultDB: resultado de uma busca em SQL;
	 */
	public function transcriberToList($resultDB) 
	{
		$arrResult = array();
		if( $resultDB->num_rows ) {
			while ( $obj = $resultDB->fetch_object () ) 
			{								
				$arr_unit = array();
				foreach ($obj as $key => $value) 
				{					
					$arr_unit[$key] = $value;					
				}
				
				array_push($arrResult, $arr_unit);
			}
		}
		
		return $arrResult;		
	}
	
	/*
	 * Funcao utilizada para transforma um array em uma sequencia de palavras;
	 * Utilizada para buscas sql
	 * Ex: ['id','nome','cargo'] >>> SELECT id,nome,cargo FROM ??? 
	 * 
	 */	
	public function arrToText($arr)
	{
		if(count($arr) <= 0) return;
		$str = "";
		foreach ($arr as $val) 
		{
			$str .= $val.",";		
		}		
		return substr($str, 0,-1);
	}
	
	public function arrAddslashes($arr)
	{
		if(count($arr) <= 0) return;
		foreach ($arr as $key => $val) $arr[$key] = addslashes($val);		
		return $arr;
	}
	
}


?>