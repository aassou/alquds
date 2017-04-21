<?php
class CollaborationManager{

	//attributes
	private $_db;

	//le constructeur
    public function __construct($db){
        $this->_db = $db;
    }

	//BAISC CRUD OPERATIONS
	public function add(Collaboration $collaboration){
    	$query = $this->_db->prepare(' INSERT INTO t_collaboration (
		titre, description, status, duree, created, createdBy)
		VALUES (:titre, :description, :status, :duree, :created, :createdBy)')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':titre', $collaboration->titre());
		$query->bindValue(':description', $collaboration->description());
        $query->bindValue(':status', $collaboration->status());
        $query->bindValue(':duree', $collaboration->duree());
		$query->bindValue(':created', $collaboration->created());
		$query->bindValue(':createdBy', $collaboration->createdBy());
		$query->execute();
		$query->closeCursor();
	}

	public function update(Collaboration $collaboration){
    	$query = $this->_db->prepare(
    	'UPDATE t_collaboration SET 
		titre=:titre, description=:description, status=:status, 
		duree=:duree, updated=:updated, updatedBy=:updatedBy
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $collaboration->id());
		$query->bindValue(':titre', $collaboration->titre());
		$query->bindValue(':description', $collaboration->description());
        $query->bindValue(':status', $collaboration->status());
        $query->bindValue(':duree', $collaboration->duree());
		$query->bindValue(':updated', $collaboration->updated());
		$query->bindValue(':updatedBy', $collaboration->updatedBy());
		$query->execute();
		$query->closeCursor();
	}

	public function delete($id){
    	$query = $this->_db->prepare(' DELETE FROM t_collaboration
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();
		$query->closeCursor();
	}

	public function getCollaborationById($id){
    	$query = $this->_db->prepare(' SELECT * FROM t_collaboration
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();		
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();
		return new Collaboration($data);
	}

	public function getCollaborations(){
		$collaborations = array();
		$query = $this->_db->query(
		'SELECT * FROM t_collaboration
		ORDER BY status DESC');
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$collaborations[] = new Collaboration($data);
		}
		$query->closeCursor();
		return $collaborations;
	}
    
    public function getCollaborationsNonValidees(){
        $collaborations = array();
        $query = $this->_db->query(
        "SELECT * FROM t_collaboration
        WHERE status='X'
        ORDER BY status DESC");
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $collaborations[] = new Collaboration($data);
        }
        $query->closeCursor();
        return $collaborations;
    }

	public function getCollaborationsByLimits($begin, $end){
		$collaborations = array();
		$query = $this->_db->query('SELECT * FROM t_collaboration
		ORDER BY id DESC LIMIT '.$begin.', '.$end);
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$collaborations[] = new Collaboration($data);
		}
		$query->closeCursor();
		return $collaborations;
	}

	public function getLastId(){
    	$query = $this->_db->query(' SELECT id AS last_id FROM t_collaboration
		ORDER BY id DESC LIMIT 0, 1');
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$id = $data['last_id'];
		return $id;
	}

}