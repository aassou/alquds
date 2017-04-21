<?php
class CommissionManager{

	//attributes
	private $_db;

	//le constructeur
    public function __construct($db){
        $this->_db = $db;
    }

	//BAISC CRUD OPERATIONS
	public function add(Commission $commission){
    	$query = $this->_db->prepare(' INSERT INTO t_commission (
		titre, commissionnaire, montant, codeContrat, date, etat, created, createdBy)
		VALUES (:titre, :commissionnaire, :montant, :codeContrat, :date, :etat, :created, :createdBy)')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':titre', $commission->titre());
		$query->bindValue(':commissionnaire', $commission->commissionnaire());
		$query->bindValue(':montant', $commission->montant());
		$query->bindValue(':codeContrat', $commission->codeContrat());
		$query->bindValue(':date', $commission->date());
		$query->bindValue(':etat', $commission->etat());
		$query->bindValue(':created', $commission->created());
		$query->bindValue(':createdBy', $commission->createdBy());
		$query->execute();
		$query->closeCursor();
	}

	public function update(Commission $commission){
    	$query = $this->_db->prepare(' UPDATE t_commission SET 
		titre=:titre, commissionnaire=:commissionnaire, montant=:montant, 
		etat=:etat, updated=:updated, updatedBy=:updatedBy
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $commission->id());
		$query->bindValue(':titre', $commission->titre());
		$query->bindValue(':commissionnaire', $commission->commissionnaire());
		$query->bindValue(':montant', $commission->montant());
		$query->bindValue(':etat', $commission->etat());
		$query->bindValue(':updated', $commission->updated());
		$query->bindValue(':updatedBy', $commission->updatedBy());
		$query->execute();
		$query->closeCursor();
	}

	public function delete($id){
    	$query = $this->_db->prepare(' DELETE FROM t_commission
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();
		$query->closeCursor();
	}

	public function getCommissionById($id){
    	$query = $this->_db->prepare(' SELECT * FROM t_commission
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();		
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();
		return new Commission($data);
	}

	public function getCommissions(){
		$commissions = array();
		$query = $this->_db->query('SELECT * FROM t_commission
		ORDER BY id DESC');
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$commissions[] = new Commission($data);
		}
		$query->closeCursor();
		return $commissions;
	}

    public function getCommissionsByCodeContrat($codeContrat){
        $commissions = array();
        $query = $this->_db->prepare(
        'SELECT * FROM t_commission
        WHERE codeContrat=:codeContrat
        ORDER BY id DESC');
        $query->bindValue(':codeContrat', $codeContrat);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $commissions[] = new Commission($data);
        }
        $query->closeCursor();
        return $commissions;
    }

	public function getCommissionsByLimits($begin, $end){
		$commissions = array();
		$query = $this->_db->query('SELECT * FROM t_commission
		ORDER BY id DESC LIMIT '.$begin.', '.$end);
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$commissions[] = new Commission($data);
		}
		$query->closeCursor();
		return $commissions;
	}

	public function getLastId(){
    	$query = $this->_db->query(' SELECT id AS last_id FROM t_commission
		ORDER BY id DESC LIMIT 0, 1');
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$id = $data['last_id'];
		return $id;
	}

}