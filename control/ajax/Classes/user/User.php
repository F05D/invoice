<?php 

require_once ( "UserDb.php");

class User extends UserDb {
   	
	private $oDbUser;
	private $user = array();
	
	public function __construct()
	{
		$this->oUserDb = new UserDb();
	}
   
	public function getById($id) 
	{
		$arr_result = $this->oUserDb->getByDB($id,NULL);
		return $arr_result;
	}
	
	public function getByEmail($email) 
	{
		$arr_result = $this->oUserDb->getByDB(NULL,$email);
		return $arr_result;
	}
	
	public function autenticaUsuario($pincode) 
	{
	 	$user = $this->oUserDb->searchUserByPincodeDB($pincode);
	 	
	 	if($user['id']) {
	 		$this->user = $this->getById($user['id'],null);
	 		return true; 			 		
	 	} 
	 	
	 	return false;
	}
	
	public function getPrivilegiosByPincode($pincode)
	{
		$user = $this->oUserDb->searchUserByPincodeDB($pincode);
		if($user['id']) {
	 		return $this->getById($user['id'],$user['usuario'])['privilegios'];	 		 			 	
	 	} 
	 	
	 	return false;
	}
	
	public function getEmpresaByPincode($pincode)
	{
		$user = $this->oUserDb->searchUserByPincodeDB($pincode);
		if($user['id']) {			
			return $this->getBindCompany($user['id'])['id'];
		}
		 
		return false;
	}
	
	public function get($key)
	{
		return $this->user[$key];
	}
	
	public function getUserList()
	{
		return $this->oUserDb->getUserList();
	}
	
	public function getCodeSecurity($id)
	{		
		return md5( Date("d").$id."$@#");
	}

	public function getLastId()
	{
		return $this->oUserDb->getLastIdDB();
	}	
	
	public function userPermission($id,$code)
	{
		return ($code == $this->getCodeSecurity($id) ? TRUE : FALSE);
	}	
	
	public function create($arr_user)
	{
		return $this->oUserDb->createDB($arr_user);
	}
	
	public function update($arr_user)
	{
		return $this->oUserDb->updateDB($arr_user);
	}
	
	public  function delete($id)
	{
		return $this->oUserDb->deleteDB($id);		
	}	
	
	public function verificaUserInDB($email,$id = null)
	{
		return $this->oUserDb->verificaUserInDB($email,$id);
	}
	
	public function getListPrivilegios()
	{
		return $this->oUserDb->getListPrivilegiosDB();
	}
		
	public function getListLinguas()
	{
		return $this->oUserDb->getListLinguasDB();
	}	
	
	public function getBindCompany($id)
	{
		return $this->oUserDb->getBindCompanyDB($id);
	}
	
}



?>