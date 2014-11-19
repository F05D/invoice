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
	
	public function read($arr_campos = null,$limit = null, $search = null, $orderBy = null, $_arrUserPerm = null)
	{
		return $this->oInvoiceDb->getList($arr_campos, $limit, $search, $orderBy, $_arrUserPerm);
	}
	
	public function numRows($search = null)
	{
		return $this->oInvoiceDb->numRowsDB($search);
	}
	
	public function get($id)
	{
		return $this->oInvoiceDb->getDB($id);
	}
	
	public function getBindUser($id)
	{
		return $this->oInvoiceDb->getBindUserDB($id);
	}
	
	public function copyDoc( $arrDocs, $__FILES, $lockStatus, $uploaddir, $statusChanges = null )
	{			
		if($__FILES['tmp_name']) 
		{
			$docs_md5 = md5( basename($__FILES['tmp_name']) );
			$file = $uploaddir . $docs_md5;
			
			if ( move_uploaded_file($__FILES['tmp_name'], $file) ) {
				$arrDocs = array(
						'name'        => ($__FILES['name']  ? $__FILES['name']   : ''),
						'type'        => ($__FILES['type']  ? $__FILES['type']   : ''),
						'error'       => ($__FILES['error'] ? $__FILES['error']  : ''),
						'size'        => ($__FILES['size']  ? $__FILES['size']   : ''),
						'transaction' => 'OK',
						'tmp_md5'     => ($docs_md5 ? $docs_md5 : ''),
						'lock'        => ($lockStatus ? $lockStatus : 0),
						'action'      => $statusChanges
				);
				
			} else {
				$arrDocs = array(
						'name'        => '',
						'type'        => '',
						'error'       => 1,
						'size'        => '',
						'transaction' => 'NO',
						'tmp_md5'     => '',
						'lock'        => '',
						'action'      => ''
				);
			}
		}
		
		return $arrDocs;
	}
	
	public function lockDoc( $docs_md5, $lockStatus, $statusChanges )
	{
	
		$arrDocs = array(
				'tmp_md5'     => $docs_md5,
				'lock'        => ($lockStatus ? $lockStatus : 0),				
				'action'      => $statusChanges
		);
		return $arrDocs;
	}
	
	public function getDocs($id) 
	{
		return $this->oInvoiceDb->getDocsDB($id);		
	}
	
	public function downloadFile($arr_args) 
	{
		return $this->oInvoiceDb->getFileDocsDB($arr_args);
	}
	
	public function getScores($field, $_arrUserPerm = null)
	{
		return $this->oInvoiceDb->getScoresDB($field, $_arrUserPerm);
	}
	
}



?>