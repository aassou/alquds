<?php
class ClientClassementManager{

	//attributes
	private $_db;

	//le constructeur
    public function __construct($db){
        $this->_db = $db;
    }

	//BAISC CRUD OPERATIONS
	public function add(ClientClassement $clientClassement){
    	$query = $this->_db->prepare(' INSERT INTO t_clientclassement (
		nom, classement, remarque, created, createdBy)
		VALUES (:nom, :classement, :remarque, :created, :createdBy)')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':nom', $clientClassement->nom());
		$query->bindValue(':classement', $clientClassement->classement());
		$query->bindValue(':remarque', $clientClassement->remarque());
		$query->bindValue(':created', $clientClassement->created());
		$query->bindValue(':createdBy', $clientClassement->createdBy());
		$query->execute();
		$query->closeCursor();
	}

	public function update(ClientClassement $clientClassement){
    	$query = $this->_db->prepare(' UPDATE t_clientclassement SET 
		classement=:classement, remarque=:remarque, updated=:updated, updatedBy=:updatedBy
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $clientClassement->id());
		$query->bindValue(':classement', $clientClassement->classement());
		$query->bindValue(':remarque', $clientClassement->remarque());
		$query->bindValue(':updated', $clientClassement->updated());
		$query->bindValue(':updatedBy', $clientClassement->updatedBy());
		$query->execute();
		$query->closeCursor();
	}

	public function delete($id){
    	$query = $this->_db->prepare(' DELETE FROM t_clientclassement
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();
		$query->closeCursor();
	}

	public function getClientClassementById($id){
    	$query = $this->_db->prepare(' SELECT * FROM t_clientclassement
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();		
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();
		return new ClientClassement($data);
	}

	public function getClientClassements(){
		$clientClassements = array();
		$query = $this->_db->query(
		'SELECT * FROM t_clientclassement
		ORDER BY classement DESC');
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$clientClassements[] = new ClientClassement($data);
		}
		$query->closeCursor();
		return $clientClassements;
	}

	public function getClientClassementsByLimits($begin, $end){
		$clientClassements = array();
		$query = $this->_db->query('SELECT * FROM t_clientclassement
		ORDER BY id DESC LIMIT '.$begin.', '.$end);
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$clientClassements[] = new ClientClassement($data);
		}
		$query->closeCursor();
		return $clientClassements;
	}

	public function getLastId(){
    	$query = $this->_db->query(' SELECT id AS last_id FROM t_clientclassement
		ORDER BY id DESC LIMIT 0, 1');
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$id = $data['last_id'];
		return $id;
	}

}