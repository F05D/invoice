<?php 

require_once ( dirname(__FILE__) . "/CompDb.php");

class Comp extends CompDb {
   	
	private $oCompDb;
	
	public function __construct()
	{
		$this->oCompDb = new CompDb; //Acess to DB/Parser
	}
	
	public function delete($id)
	{
		return $this->oCompDb->deleteDB($id);
	}
	
	public function update($arr_args)
	{
		return $this->oCompDb->updateDB($arr_args);
	}

	public function create($arr_args)
	{
		return $this->oCompDb->createDB($arr_args);
	}
	
	public function read()
	{
		return $this->oCompDb->getList();
	}
	
	public function get($id)
	{
		return $this->oCompDb->getDB($id);
	}
	
}



?>