<?php
class TypeSyndiqueManager{

	//attributes
	private $_db;

	//le constructeur
    public function __construct($db){
        $this->_db = $db;
    }

	//BAISC CRUD OPERATIONS
	public function add(TypeSyndique $typeSyndique){
    	$query = $this->_db->prepare(' INSERT INTO t_typeSyndique (
		designation, frais, created, createdBy)
		VALUES (:designation, :frais, :created, :createdBy)')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':designation', $typeSyndique->designation());
		$query->bindValue(':frais', $typeSyndique->frais());
		$query->bindValue(':created', $typeSyndique->created());
		$query->bindValue(':createdBy', $typeSyndique->createdBy());
		$query->execute();
		$query->closeCursor();
	}

	public function update(TypeSyndique $typeSyndique){
    	$query = $this->_db->prepare(' UPDATE t_typeSyndique SET 
		designation=:designation, frais=:frais, updated=:updated, updatedBy=:updatedBy
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $typeSyndique->id());
		$query->bindValue(':designation', $typeSyndique->designation());
		$query->bindValue(':frais', $typeSyndique->frais());
		$query->bindValue(':updated', $typeSyndique->updated());
		$query->bindValue(':updatedBy', $typeSyndique->updatedBy());
		$query->execute();
		$query->closeCursor();
	}

	public function delete($id){
    	$query = $this->_db->prepare(' DELETE FROM t_typeSyndique
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();
		$query->closeCursor();
	}

	public function getTypeSyndiqueById($id){
    	$query = $this->_db->prepare(' SELECT * FROM t_typeSyndique
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();		
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();
		return new TypeSyndique($data);
	}

	public function getTypeSyndiques(){
		$typeSyndiques = array();
		$query = $this->_db->query('SELECT * FROM t_typeSyndique
		ORDER BY id DESC');
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$typeSyndiques[] = new TypeSyndique($data);
		}
		$query->closeCursor();
		return $typeSyndiques;
	}

	public function getTypeSyndiquesByLimits($begin, $end){
		$typeSyndiques = array();
		$query = $this->_db->query('SELECT * FROM t_typeSyndique
		ORDER BY id DESC LIMIT '.$begin.', '.$end);
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$typeSyndiques[] = new TypeSyndique($data);
		}
		$query->closeCursor();
		return $typeSyndiques;
	}

	public function getLastId(){
    	$query = $this->_db->query(' SELECT id AS last_id FROM t_typeSyndique
		ORDER BY id DESC LIMIT 0, 1');
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$id = $data['last_id'];
		return $id;
	}

}