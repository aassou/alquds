<?php
class ParkingManager{

	//attributes
	private $_db;

	//le constructeur
    public function __construct($db){
        $this->_db = $db;
    }

	//BAISC CRUD OPERATIONS
	public function add(Parking $parking){
    	$query = $this->_db->prepare(' INSERT INTO t_parking (
		code, status, idProjet, idContrat, created, createdBy)
		VALUES (:code, :status, :idProjet, :idContrat, :created, :createdBy)')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':code', $parking->code());
		$query->bindValue(':status', $parking->status());
		$query->bindValue(':idProjet', $parking->idProjet());
		$query->bindValue(':idContrat', $parking->idContrat());
		$query->bindValue(':created', $parking->created());
		$query->bindValue(':createdBy', $parking->createdBy());
		$query->execute();
		$query->closeCursor();
	}

	public function update(Parking $parking){
    	$query = $this->_db->prepare('UPDATE t_parking SET 
		status=:status, idContrat=:idContrat, updated=:updated, updatedBy=:updatedBy
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $parking->id());
		$query->bindValue(':status', $parking->status());
		$query->bindValue(':idContrat', $parking->idContrat());
		$query->bindValue(':updated', $parking->updated());
		$query->bindValue(':updatedBy', $parking->updatedBy());
		$query->execute();
		$query->closeCursor();
	}

	public function delete($id){
    	$query = $this->_db->prepare('DELETE FROM t_parking
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();
		$query->closeCursor();
	}

	public function getParkingById($id){
    	$query = $this->_db->prepare(' SELECT * FROM t_parking
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();		
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();
		return new Parking($data);
	}

	public function getParkings(){
		$parkings = array();
		$query = $this->_db->query('SELECT * FROM t_parking
		ORDER BY id DESC');
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$parkings[] = new Parking($data);
		}
		$query->closeCursor();
		return $parkings;
	}
    
    public function getParkingsByIdProjet($idProjet){
        $parkings = array();
        $query = $this->_db->prepare('SELECT * FROM t_parking
        WHERE idProjet=:idProjet
        ORDER BY code ASC');
        $query->bindValue(':idProjet', $idProjet);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $parkings[] = new Parking($data);
        }
        $query->closeCursor();
        return $parkings;
    }

	public function getParkingsByLimits($begin, $end){
		$parkings = array();
		$query = $this->_db->query('SELECT * FROM t_parking
		ORDER BY id DESC LIMIT '.$begin.', '.$end);
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$parkings[] = new Parking($data);
		}
		$query->closeCursor();
		return $parkings;
	}

	public function getLastId(){
    	$query = $this->_db->query(' SELECT id AS last_id FROM t_parking
		ORDER BY id DESC LIMIT 0, 1');
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$id = $data['last_id'];
		return $id;
	}
    
    public function getLastCodeByIdProjet($idProjet){
        $query = $this->_db->prepare('SELECT code AS last_code FROM t_parking
        WHERE idProjet=:idProjet
        ORDER BY code DESC LIMIT 0, 1');
        $query->bindValue(':idProjet', $idProjet);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $id = $data['last_code'];
        return $id;
    }

}