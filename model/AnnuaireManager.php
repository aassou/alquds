<?php
class AnnuaireManager{

	//attributes
	private $_db;

	//le constructeur
    public function __construct($db){
        $this->_db = $db;
    }

	//BAISC CRUD OPERATIONS
	public function add(Annuaire $annuaire){
    	$query = $this->_db->prepare(' INSERT INTO t_annuaire (
		nom, description, telephone1, telephone2, created, createdBy)
		VALUES (:nom, :description, :telephone1, :telephone2, :created, :createdBy)')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':nom', $annuaire->nom());
		$query->bindValue(':description', $annuaire->description());
		$query->bindValue(':telephone1', $annuaire->telephone1());
		$query->bindValue(':telephone2', $annuaire->telephone2());
		$query->bindValue(':created', $annuaire->created());
		$query->bindValue(':createdBy', $annuaire->createdBy());
		$query->execute();
		$query->closeCursor();
	}

	public function update(Annuaire $annuaire){
    	$query = $this->_db->prepare(' UPDATE t_annuaire SET 
		nom=:nom, description=:description, telephone1=:telephone1, telephone2=:telephone2, updated=:updated, updatedBy=:updatedBy
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $annuaire->id());
		$query->bindValue(':nom', $annuaire->nom());
		$query->bindValue(':description', $annuaire->description());
		$query->bindValue(':telephone1', $annuaire->telephone1());
		$query->bindValue(':telephone2', $annuaire->telephone2());
		$query->bindValue(':updated', $annuaire->updated());
		$query->bindValue(':updatedBy', $annuaire->updatedBy());
		$query->execute();
		$query->closeCursor();
	}

	public function delete($id){
    	$query = $this->_db->prepare(' DELETE FROM t_annuaire
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();
		$query->closeCursor();
	}

	public function getAnnuaireById($id){
    	$query = $this->_db->prepare(' SELECT * FROM t_annuaire
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();		
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();
		return new Annuaire($data);
	}

	public function getAnnuaires(){
		$annuaires = array();
		$query = $this->_db->query('SELECT * FROM t_annuaire
		ORDER BY id DESC');
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$annuaires[] = new Annuaire($data);
		}
		$query->closeCursor();
		return $annuaires;
	}

	public function getAnnuairesByLimits($begin, $end){
		$annuaires = array();
		$query = $this->_db->query('SELECT * FROM t_annuaire
		ORDER BY id DESC LIMIT '.$begin.', '.$end);
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$annuaires[] = new Annuaire($data);
		}
		$query->closeCursor();
		return $annuaires;
	}

	public function getLastId(){
    	$query = $this->_db->query(' SELECT id AS last_id FROM t_annuaire
		ORDER BY id DESC LIMIT 0, 1');
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$id = $data['last_id'];
		return $id;
	}

}