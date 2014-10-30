<?php 

require_once ( "InvoiceDb.php");

class Invoice extends InvoiceDb {
   	
	private $oInvoiceDb;
	
	public function __construct()
	{
		$this->oInvoiceDb = new InvoiceDb; //Acess to DB/Parser		
	}
	
	public function delete($id)
	{
		return $this->oInvoiceDb->deleteDB($id);
	}
	
	public function update($arr_args)
	{
		return $this->oInvoiceDb->updateDB($arr_args);
	}

	public function create($arr_args)
	{
		return $this->oInvoiceDb->createDB($arr_args);
	}
	
	public function read($arr_campos = null)
	{
		return $this->oInvoiceDb->getList($arr_campos);
	}
	
	public function get($id)
	{
		return $this->oInvoiceDb->getDB($id);
	}
	
	public function getBindUser($id)
	{
		return $this->oInvoiceDb->getBindUserDB($id);
	}
	
	public function copyDoc( $arrDocs, $__FILES, $uploaddir )
	{	
					
		$docs_md5 = md5( basename($__FILES['tmp_name']) );
		$file = $uploaddir . $docs_md5;
		
		$transaction = 'NO';
		if ( move_uploaded_file($__FILES['tmp_name'], $file) ) 
			$transaction = 'OK';
	
		$arrDocs = array(
				'name'        => $__FILES['name'],
				'type'        => $__FILES['type'],
				'error'       => $__FILES['error'],
				'size'        => $__FILES['size'],
				'transaction' => $transaction,
				'tmp_md5'     => $docs_md5
		);
		return $arrDocs;
	}
	
}



?>