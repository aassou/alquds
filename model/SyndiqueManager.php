<?php
class SyndiqueManager{

	//attributes
	private $_db;

	//le constructeur
    public function __construct($db){
        $this->_db = $db;
    }

	//BAISC CRUD OPERATIONS
	public function add(Syndique $syndique){
    	$query = $this->_db->prepare('INSERT INTO t_syndique (
		date, montant, status, idClient, idProjet, created, createdBy)
		VALUES (:date, :montant, :status, :idClient, :idProjet, :created, :createdBy)')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':date', $syndique->date());
		$query->bindValue(':montant', $syndique->montant());
        $query->bindValue(':status', $syndique->status());
		$query->bindValue(':idClient', $syndique->idClient());
		$query->bindValue(':idProjet', $syndique->idProjet());
		$query->bindValue(':created', $syndique->created());
		$query->bindValue(':createdBy', $syndique->createdBy());
		$query->execute();
		$query->closeCursor();
	}

	public function update(Syndique $syndique){
    	$query = $this->_db->prepare('UPDATE t_syndique SET 
		date=:date, status=:status, montant=:montant, idClient=:idClient, 
		idProjet=:idProjet, updated=:updated, updatedBy=:updatedBy
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $syndique->id());
		$query->bindValue(':date', $syndique->date());
		$query->bindValue(':montant', $syndique->montant());
        $query->bindValue(':status', $syndique->status());
		$query->bindValue(':idClient', $syndique->idClient());
		$query->bindValue(':idProjet', $syndique->idProjet());
		$query->bindValue(':updated', $syndique->updated());
		$query->bindValue(':updatedBy', $syndique->updatedBy());
		$query->execute();
		$query->closeCursor();
	}
    
    public function updateStatus($idSyndique, $status){
        $query = $this->_db->prepare('UPDATE t_syndique SET status=:status WHERE id=:id')
        or die (print_r($this->_db->errorInfo()));
        $query->bindValue(':id', $idSyndique);
        $query->bindValue(':status', $status);
        $query->execute();
        $query->closeCursor();
    }

	public function delete($id){
    	$query = $this->_db->prepare('DELETE FROM t_syndique WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();
		$query->closeCursor();
	}

	public function getSyndiqueById($id){
    	$query = $this->_db->prepare('SELECT * FROM t_syndique WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();		
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();
		return new Syndique($data);
	}

	public function getSyndiques(){
		$syndiques = array();
		$query = $this->_db->query('SELECT * FROM t_syndique ORDER BY id DESC');
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$syndiques[] = new Syndique($data);
		}
		$query->closeCursor();
		return $syndiques;
	}
    
    public function getSyndiquesByIdProjet($idProjet){
        $syndiques = array();
        $query = $this->_db->prepare(
        'SELECT * FROM t_syndique WHERE idProjet=:idProjet ORDER BY id DESC');
        $query->bindValue(':idProjet', $idProjet);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $syndiques[] = new Syndique($data);
        }
        $query->closeCursor();
        return $syndiques;
    }

    public function getSyndiquesTotalByIdProjet($idProjet){
        $syndiques = array();
        $query = $this->_db->prepare(
        'SELECT SUM(montant) AS total FROM t_syndique WHERE idProjet=:idProjet ORDER BY id DESC');
        $query->bindValue(':idProjet', $idProjet);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['total'];
    }

	public function getSyndiquesByLimits($begin, $end){
		$syndiques = array();
		$query = $this->_db->query('SELECT * FROM t_syndique ORDER BY id DESC LIMIT '.$begin.', '.$end);
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$syndiques[] = new Syndique($data);
		}
		$query->closeCursor();
		return $syndiques;
	}

	public function getLastId(){
    	$query = $this->_db->query(' SELECT id AS last_id FROM t_syndique ORDER BY id DESC LIMIT 0, 1');
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$id = $data['last_id'];
		return $id;
	}
    
    public function getSyndiquesGroupByMonthByIdProjet($idProjet){
        $syndiques = array();
        $query = $this->_db->prepare(
        "SELECT * FROM t_syndique
        WHERE idProjet=:idProjet 
        GROUP BY MONTH(date), YEAR(date)
        ORDER BY date DESC");
        $query->bindValue(':idProjet', $idProjet);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $syndiques[] = new Syndique($data);
        }
        $query->closeCursor();
        return $syndiques;
    }
    
    public function getTotalSyndiquesByMonthYearByIdProjet($month, $year, $idProjet){
        $query = $this->_db->prepare(
        "SELECT SUM(montant) AS total FROM t_syndique 
        WHERE MONTH(date) = :month
        AND YEAR(date) = :year
        AND idProjet=:idProjet
        ORDER BY date DESC");
        $query->bindValue(':month', $month);
        $query->bindValue(':year', $year);
        $query->bindValue(':idProjet', $idProjet);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        $query->closeCursor();
        return $data['total'];
    }
    
    public function getSyndiquesByIdProjetByMonthYear($idProjet, $month, $year){
        $syndiques = array();
        $query = $this->_db->prepare(
        "SELECT * FROM t_syndique 
        WHERE idProjet=:idProjet
        AND MONTH(date) = :month
        AND YEAR(date) = :year
        ORDER BY date DESC");
        $query->bindValue(':idProjet', $idProjet);
        $query->bindValue(':month', $month);
        $query->bindValue(':year', $year);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $syndiques[] = new Syndique($data);
        }
        $query->closeCursor();
        return $syndiques;
    }

}