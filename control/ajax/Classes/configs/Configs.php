<?php 

if($_SERVER['DOCUMENT_ROOT'] == "/Library/WebServer/Sites") {
	$local_root = $_SERVER['DOCUMENT_ROOT'];
	$local_simbolic = "/www.invoice.artsulgranitos.com.br";
} else {
	$local_root = $_SERVER['DOCUMENT_ROOT'];
	$local_simbolic = "";
}

//CONFIGURACOES E TRADUCOES
require_once ( $local_root. $local_simbolic . "/control/ajax/Classes/configs/files/Enigma.php");

class Configs extends Enigma {
   	
	private $CODE_LANG_SRT;
	private $CODE_LANG_INT;	
	private $year_now;
	private $timezone;
	
	public static function Instance()
	{
		static $inst = null;
		if ($inst === null) {
			$inst = new Configs();
		}
		return $inst;
	}
	
	function __construct() {		
		$this->setTimezone("America/Sao_Paulo");
		$this->year_now = date("Y");		
	}

	public function setLanguage($lang_code, $change_link = true) {
				
		//TRADUCAO DO SISTEMA
		if( !$lang_code ) {
				
			$ver_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
				
			switch ($ver_lang) {
				case "sp":
					$this->CODE_LANG_SRT = "sp";
					break;
		
				case "pt":
				case "br":
					$this->CODE_LANG_SRT = "pt";
					break;
		
				default:
					$this->CODE_LANG_SRT = "en";
					break;
			}
				
			if($change_link) header("Location: index.php?lang=".$this->CODE_LANG_SRT);
		
		} else
			$this->CODE_LANG_SRT = $lang_code;
		
		switch( $this->CODE_LANG_SRT ) {
			case "pt":
			case "br":
				$this->CODE_LANG_INT = 0;
				break;
			case "en":
				$this->CODE_LANG_INT = 1;
				break;
			case "sp":
				$this->CODE_LANG_INT = 2;
				break;
			default:
				$this->CODE_LANG_INT = 0;
				break;
		}
	}
	
	public function getLangSTR(){
		return $this->CODE_LANG_SRT;
	}

	public function getLangCODE(){
		return $this->CODE_LANG_INT;
	}
	
	
	public function setTimezone($zone){
		date_default_timezone_set($zone);
	}
	
	public function getTimezone(){
		return date_default_timezone_set($this->timezone);
	}
	
	public function  getDateNowForDB(){
		$this->getTimezone();
		return date("Y-m-d G:i:s");
	}
	
	public function get( $file, $keyword) {
		
		//Renova array;
		$arr_translate = array(); 
		
		switch($file) {
			case "index";				
				require 'files/index.php';				
				break;
				
			case "login";
				require 'files/login.php';
				break; 
				
			case "cadastro_usuario";
				require 'files/cadastro_usuario.php';
				break;

			case "cadastro_companies";
				require 'files/cadastro_companies.php';
				break;
				
			case "cadastro_invoice";
				require 'files/cadastro_invoice.php';
				break;
			
			case "cadastro_material";
				require 'files/cadastro_material.php';
				break;
				
			case "general";
				require 'files/general.php';
				break;
		}
		
		return $arr_translate[$keyword][$this->CODE_LANG_INT];
		
	}

	
	public function decodeURL($code) {
		$oEnigma = new Enigma();
		return $oEnigma->decoder($code);				
	} 

	public function encoderURL($str) {
		$oEnigma = new Enigma();
		return $oEnigma->decoder($str);		
	}	
	
}



?>