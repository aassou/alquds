<?php
class ClientAttenteManager{

	//attributes
	private $_db;

	//le constructeur
    public function __construct($db){
        $this->_db = $db;
    }

	//BAISC CRUD OPERATIONS
	public function add(ClientAttente $clientAttente){
    	$query = $this->_db->prepare(' INSERT INTO t_clientattente (
		nom, tel, date, bien, prix, superficie, emplacementVente, emplacementAchat, created, createdBy)
		VALUES (:nom, :tel, :date, :bien, :prix, :superficie, :emplacementVente, :emplacementAchat, :created, :createdBy)')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':nom', $clientAttente->nom());
        $query->bindValue(':tel', $clientAttente->tel());
		$query->bindValue(':date', $clientAttente->date());
		$query->bindValue(':bien', $clientAttente->bien());
		$query->bindValue(':prix', $clientAttente->prix());
		$query->bindValue(':superficie', $clientAttente->superficie());
		$query->bindValue(':emplacementVente', $clientAttente->emplacementVente());
		$query->bindValue(':emplacementAchat', $clientAttente->emplacementAchat());
		$query->bindValue(':created', $clientAttente->created());
		$query->bindValue(':createdBy', $clientAttente->createdBy());
		$query->execute();
		$query->closeCursor();
	}

	public function update(ClientAttente $clientAttente){
    	$query = $this->_db->prepare(' UPDATE t_clientattente SET 
		nom=:nom, tel=:tel, date=:date, bien=:bien, prix=:prix, superficie=:superficie, emplacementVente=:emplacementVente, emplacementAchat=:emplacementAchat, updated=:updated, updatedBy=:updatedBy
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $clientAttente->id());
		$query->bindValue(':nom', $clientAttente->nom());
        $query->bindValue(':tel', $clientAttente->tel());
		$query->bindValue(':date', $clientAttente->date());
		$query->bindValue(':bien', $clientAttente->bien());
		$query->bindValue(':prix', $clientAttente->prix());
		$query->bindValue(':superficie', $clientAttente->superficie());
		$query->bindValue(':emplacementVente', $clientAttente->emplacementVente());
		$query->bindValue(':emplacementAchat', $clientAttente->emplacementAchat());
		$query->bindValue(':updated', $clientAttente->updated());
		$query->bindValue(':updatedBy', $clientAttente->updatedBy());
		$query->execute();
		$query->closeCursor();
	}

	public function delete($id){
    	$query = $this->_db->prepare(' DELETE FROM t_clientattente
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();
		$query->closeCursor();
	}

	public function getClientAttenteById($id){
    	$query = $this->_db->prepare(' SELECT * FROM t_clientattente
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();		
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();
		return new ClientAttente($data);
	}

	public function getClientAttentes(){
		$clientAttentes = array();
		$query = $this->_db->query('SELECT * FROM t_clientattente
		ORDER BY id DESC');
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$clientAttentes[] = new ClientAttente($data);
		}
		$query->closeCursor();
		return $clientAttentes;
	}

	public function getClientAttentesByLimits($begin, $end){
		$clientAttentes = array();
		$query = $this->_db->query('SELECT * FROM t_clientattente
		ORDER BY id DESC LIMIT '.$begin.', '.$end);
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$clientAttentes[] = new ClientAttente($data);
		}
		$query->closeCursor();
		return $clientAttentes;
	}

	public function getLastId(){
    	$query = $this->_db->query(' SELECT id AS last_id FROM t_clientattente
		ORDER BY id DESC LIMIT 0, 1');
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$id = $data['last_id'];
		return $id;
	}

}