<?php 

require_once ( "BankDb.php");

class Bank extends BankDb {
   	
	private $oBankDb;
	
	public function __construct()
	{
		$this->oBankDb = new BankDb; //Acess to DB/Parser		
	}
	
	public function delete($id)
	{
		return $this->oBankDb->deleteDB($id);
	}
	
	public function update($arr_args)
	{
		return $this->oBankDb->updateDB($arr_args);
	}

	public function create($arr_args)
	{
		return $this->oBankDb->createDB($arr_args);
	}
	
	public function read($arr_campos = null)
	{
		return $this->oBankDb->getList($arr_campos);
	}
	
	public function get($id)
	{
		return $this->oBankDb->getDB($id);
	}
	
	public function getBindUser($id)
	{
		return $this->oBankDb->getBindUserDB($id);
	}
	
	
	
}



?>