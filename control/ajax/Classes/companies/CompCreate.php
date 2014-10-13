<?php 

require_once ( dirname(__FILE__) . "/CompDb.php");

class CompCreate extends CompDb {
   	
	private $oCompDb;
	
	public function __construct()
	{
		 $this->oCompDb = new CompDb; //User DB New
	}
		
	 function create($arr_args) {
		return $this->oCompDb->createDB($arr_args);	
	}
}
   