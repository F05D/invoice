<?php 

require_once ( dirname(__FILE__) . "/db/DBComp.php");

class Comp extends DBComp {
   	
	//Companies DB	
	private $oDBComp;
	
	function __construct() {
		//if(!$this->oDBComp) $this->oDBComp = new DBComp();
	}

	public function getList() {
		$arrResult = array();
		
		$result = $this->oDBComp->getList();
		if( $result->num_rows ) {
			while ( $obj = $result->fetch_object () ) {
				array_push( $arrResult, array(
						'id'        => $obj->id,
						'nome'      => $obj->nome,
						'end'       => $obj->endereco,
						'map_ln'    => $obj->mapa_link,
						'tel_p'     => $obj->tel_princ,
						'tel_s'     => $obj->tel_sec,
						'cnpj_id'   => $obj->cnpj_id,
						'site'      => $obj->site,
						'email'     => $obj->email,
						'nome_prop' => $obj->nome_proprietario,
						'pais'      => $obj->pais,
						'estado'    => $obj->estado,
						'cidade'    => $obj->cidade,
						'coment'    => $obj->comentarios,
						'ativo'     => $obj->ativo
					)
				);
			}
		}
		
		return $arrResult;
	}

	
}



?>