<?php
class ClientAttente{

	//attributes
	private $_id;
	private $_nom;
    private $_tel;
	private $_date;
	private $_bien;
	private $_prix;
	private $_superficie;
	private $_emplacementVente;
	private $_emplacementAchat;
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
    
	public function setNom($nom){
		$this->_nom = $nom;
   	}
    
    public function setTel($tel){
        $this->_tel = $tel;
    }

	public function setDate($date){
		$this->_date = $date;
   	}

	public function setBien($bien){
		$this->_bien = $bien;
   	}

	public function setPrix($prix){
		$this->_prix = $prix;
   	}

	public function setSuperficie($superficie){
		$this->_superficie = $superficie;
   	}

	public function setEmplacementVente($emplacementVente){
		$this->_emplacementVente = $emplacementVente;
   	}

	public function setEmplacementAchat($emplacementAchat){
		$this->_emplacementAchat = $emplacementAchat;
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
	
	public function nom(){
		return $this->_nom;
   	}
    
    public function tel(){
        return $this->_tel;
    }

	public function date(){
		return $this->_date;
   	}

	public function bien(){
		return $this->_bien;
   	}

	public function prix(){
		return $this->_prix;
   	}

	public function superficie(){
		return $this->_superficie;
   	}

	public function emplacementVente(){
		return $this->_emplacementVente;
   	}

	public function emplacementAchat(){
		return $this->_emplacementAchat;
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