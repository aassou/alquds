<?php
class Commission{

	//attributes
	private $_id;
	private $_titre;
	private $_commissionnaire;
	private $_montant;
	private $_codeContrat;
	private $_date;
	private $_etat;
	private $_status;
	private $_created;
	private $_createdBy;
	private $_updated;
	private $_updatedBy;

	//le constructeur
    public function __construct($data){
        $this->hydrate($data);
    }
    
    //la focntion hydrate sert à attribuer les valeurs en utilisant les setters d\'une façon dynamique!
    public function hydrate($data){
        foreach ($data as $key => $value){
            $method = 'set'.ucfirst($key);
            
            if (method_exists($this, $method)){
                $this->$method($value);
            }
        }
    }

	//setters
	public function setId($id){
    	$this->_id = $id;
    }
	public function setTitre($titre){
		$this->_titre = $titre;
   	}

	public function setCommissionnaire($commissionnaire){
		$this->_commissionnaire = $commissionnaire;
   	}

	public function setMontant($montant){
		$this->_montant = $montant;
   	}

	public function setCodeContrat($codeContrat){
		$this->_codeContrat = $codeContrat;
   	}

	public function setDate($date){
		$this->_date = $date;
   	}

	public function setEtat($etat){
		$this->_etat = $etat;
   	}

	public function setStatus($status){
		$this->_status = $status;
   	}

	public function setCreated($created){
        $this->_created = $created;
    }

	public function setCreatedBy($createdBy){
        $this->_createdBy = $createdBy;
    }

	public function setUpdated($updated){
        $this->_updated = $updated;
    }

	public function setUpdatedBy($updatedBy){
        $this->_updatedBy = $updatedBy;
    }

	//getters
	public function id(){
    	return $this->_id;
    }
	public function titre(){
		return $this->_titre;
   	}

	public function commissionnaire(){
		return $this->_commissionnaire;
   	}

	public function montant(){
		return $this->_montant;
   	}

	public function codeContrat(){
		return $this->_codeContrat;
   	}

	public function date(){
		return $this->_date;
   	}

	public function etat(){
		return $this->_etat;
   	}

	public function status(){
		return $this->_status;
   	}

	public function created(){
        return $this->_created;
    }

	public function createdBy(){
        return $this->_createdBy;
    }

	public function updated(){
        return $this->_updated;
    }

	public function updatedBy(){
        return $this->_updatedBy;
    }

}