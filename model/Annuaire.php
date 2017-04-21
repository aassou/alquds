<?php
class Annuaire{

	//attributes
	private $_id;
	private $_nom;
	private $_description;
	private $_telephone1;
	private $_telephone2;
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

	public function setDescription($description){
		$this->_description = $description;
   	}

	public function setTelephone1($telephone1){
		$this->_telephone1 = $telephone1;
   	}

	public function setTelephone2($telephone2){
		$this->_telephone2 = $telephone2;
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

	public function description(){
		return $this->_description;
   	}

	public function telephone1(){
		return $this->_telephone1;
   	}

	public function telephone2(){
		return $this->_telephone2;
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