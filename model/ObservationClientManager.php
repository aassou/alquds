<?php
class ObservationClientManager{

	//attributes
	private $_db;

	//le constructeur
    public function __construct($db){
        $this->_db = $db;
    }

	//BAISC CRUD OPERATIONS
	public function add(ObservationClient $observationClient){
    	$query = $this->_db->prepare(' INSERT INTO t_observationclient (
		description, idContrat, created, createdBy)
		VALUES (:description, :idContrat, :created, :createdBy)')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':description', $observationClient->description());
		$query->bindValue(':idContrat', $observationClient->idContrat());
		$query->bindValue(':created', $observationClient->created());
		$query->bindValue(':createdBy', $observationClient->createdBy());
		$query->execute();
		$query->closeCursor();
	}

	public function update(ObservationClient $observationClient){
    	$query = $this->_db->prepare(' UPDATE t_observationclient SET 
		description=:description, idContrat=:idContrat, updated=:updated, updatedBy=:updatedBy
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $observationClient->id());
		$query->bindValue(':description', $observationClient->description());
		$query->bindValue(':idContrat', $observationClient->idContrat());
		$query->bindValue(':updated', $observationClient->updated());
		$query->bindValue(':updatedBy', $observationClient->updatedBy());
		$query->execute();
		$query->closeCursor();
	}

	public function delete($id){
    	$query = $this->_db->prepare(' DELETE FROM t_observationclient
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();
		$query->closeCursor();
	}

	public function getObservationClientById($id){
    	$query = $this->_db->prepare(' SELECT * FROM t_observationclient
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();		
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();
		return new ObservationClient($data);
	}
    
    public function getObservationClientByIdContrat($idContrat){
        $query = $this->_db->prepare('SELECT * FROM t_observationclient
        WHERE idContrat=:idContrat')
        or die (print_r($this->_db->errorInfo()));
        $query->bindValue(':idContrat', $idContrat);
        $query->execute();      
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return new ObservationClient($data);
    }

	public function getObservationClients(){
		$observationClients = array();
		$query = $this->_db->query('SELECT * FROM t_observationclient
		ORDER BY id DESC');
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$observationClients[] = new ObservationClient($data);
		}
		$query->closeCursor();
		return $observationClients;
	}

    public function getObservationClientsByIdContrat($idContrat){
        $observationClients = array();
        $query = $this->_db->prepare('SELECT * FROM t_observationclient
        WHERE idContrat=:idContrat
        ORDER BY id DESC');
        $query->bindValue(':idContrat', $idContrat);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $observationClients[] = new ObservationClient($data);
        }
        $query->closeCursor();
        return $observationClients;
    }

	public function getObservationClientsByLimits($begin, $end){
		$observationClients = array();
		$query = $this->_db->query('SELECT * FROM t_observationclient
		ORDER BY id DESC LIMIT '.$begin.', '.$end);
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$observationClients[] = new ObservationClient($data);
		}
		$query->closeCursor();
		return $observationClients;
	}

	public function getLastId(){
    	$query = $this->_db->query(' SELECT id AS last_id FROM t_observationclient
		ORDER BY id DESC LIMIT 0, 1');
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$id = $data['last_id'];
		return $id;
	}

}