<?php

class Validacoes {
	
	//Email valido
	public function valida_mail($email) {
		return ereg ( "^([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([a-z,A-Z]){2,3}([0-9,a-z,A-Z])?$", $email );
	}

	//Formato da data
// 	public function convertDateToBD($dt,$lang) {
		
// 		$dtArr = split("/",$dt);
		
// 		switch ($lang) {
// 			case "en"://03/13/1980
// 				return $dtArr[2]."-".$dtArr[0]."-".$dtArr[1];
// 				break;
				
// 			case "pt":
// 			case "sp"://13/03/1980
// 			default:	
// 				return $dtArr[2]."-".$dtArr[1]."-".$dtArr[0];
// 				break;
// 		}
// 	}
	
	//Formato da data
	public function valida_dataFormato($dt,$lang) {
		
		switch ($lang) {
			case "en"://03/13/1980
				return ereg ( "^(0[1-9]|1[012])/(0[1-9]|[12][0-9]|3[01])/[12][0-9]{3}$", $dt );
				break;
		
			case "pt":
			case "sp"://13/03/1980
			default:
				return ereg ( "^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}$", $dt );
				break;
		}
	}

	//Formato da data
	public function convertDataToDB($dt,$lang_code) {
		
		$dt_arr = split("/",$dt);
		
		switch ($lang_code) {
			case "en"://03/13/1980								
				$dt_aux = $dt_arr[2] ."-". $dt_arr[0] ."-". $dt_arr[1]; 				
				break;
	
			case "pt":
			case "sp"://13/03/1980
			default:
				$dt_aux = $dt_arr[2] ."-". $dt_arr[1] ."-". $dt_arr[0];
				break;
		}
		
		return $dt_aux;
	}
	
	//Formato da data
	public function convertDBtoData($dt,$lang_code) {
	
		$dt_arr = explode("-",$dt);
	
		switch ($lang_code) {
			case "en"://03/13/1980
				$dt_aux = $dt_arr[1] ."/". $dt_arr[2] ."/". $dt_arr[0];
				break;
	
			case "pt":
			case "sp"://13/03/1980
			default:
				$dt_aux = $dt_arr[2] ."/". $dt_arr[1] ."/". $dt_arr[0];
				break;
		}
	
		return $dt_aux;
	}
	
	public function valida_senha($senh){
		
		if (
				preg_match('/[a-zA-Z0-9]{6,}/',$senh) && 
				preg_match_all('/[0-9]/',$senh) >= 1 && 
				( preg_match_all('/[a-z]/',$senh) >= 1 ||
				preg_match_all('/[A-Z]/',$senh) >= 1 )
			)
			return true;
		
		return;

	}
	
	// Checks if string is a URL
	// @param string $url
	// @return bool
	public function isURL ($url = NULL) {
		if($url==NULL) return false;
	
		$protocol = '(http://|https://)';
		$allowed = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)';
	
		$regex = "^". $protocol . // must include the protocol
				 '(' . $allowed . '{1,63}\.)+'. // 1 or several sub domains with a max of 63 chars
				 '[a-z]' . '{2,6}'; // followed by a TLD
		if (eregi($regex, $url) == true) 
			return true;
		
		return false;
    }
	
}


?>