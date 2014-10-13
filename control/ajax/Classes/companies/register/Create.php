<?php 

require_once ( dirname(__FILE__) . "/../db/DbComp.php");

class CompCreate extends DbComp {
   	
	//User	
	private $oDbComp;
	
	function __construct() {
		 if(!$this->oDbComp) $this->oDbComp = new DbComp(); //User DB New
	}
		
	public function create($arr_args) {
		return $this->oDbComp->CompCreate($arr_args);	
	}
}
   