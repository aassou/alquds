<?php
class TodoManager{

	//attributes
	private $_db;

	//le constructeur
    public function __construct($db){
        $this->_db = $db;
    }

	//BAISC CRUD OPERATIONS
	public function add(Todo $todo){
    	$query = $this->_db->prepare(' INSERT INTO t_todo (
		todo, priority, status, date, created, createdBy)
		VALUES (:todo, :priority, :status, :date, :created, :createdBy)')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':todo', $todo->todo());
        $query->bindValue(':priority', $todo->priority());
		$query->bindValue(':status', $todo->status());
        $query->bindValue(':date', $todo->date());
		$query->bindValue(':created', $todo->created());
		$query->bindValue(':createdBy', $todo->createdBy());
		$query->execute();
		$query->closeCursor();
	}

	public function update(Todo $todo){
    	$query = $this->_db->prepare(' UPDATE t_todo SET 
		todo=:todo, status=:status, priority=:priority, updated=:updated, updatedBy=:updatedBy
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $todo->id());
		$query->bindValue(':todo', $todo->todo());
        $query->bindValue(':priority', $todo->priority());
		$query->bindValue(':status', $todo->status());
		$query->bindValue(':updated', $todo->updated());
		$query->bindValue(':updatedBy', $todo->updatedBy());
		$query->execute();
		$query->closeCursor();
	}

    public function updatePriority(Todo $todo){
        $query = $this->_db->prepare(' UPDATE t_todo SET priority=:priority WHERE id=:id')
        or die (print_r($this->_db->errorInfo()));
        $query->bindValue(':id', $todo->id());
        $query->bindValue(':priority', $todo->priority());
        $query->execute();
        $query->closeCursor();
    }
    
    public function updateDate($idTodo, $newDate, $updated, $updatedBy){
        $query = $this->_db->prepare('
        UPDATE t_todo SET date=:date,
        updated=:updated, updatedBy=:updatedBy 
        WHERE id=:id')
        or die (print_r($this->_db->errorInfo()));
        $query->bindValue(':id', $idTodo);
        $query->bindValue(':date', $newDate);
        $query->bindValue(':updated', $updated);
        $query->bindValue(':updatedBy', $updatedBy);
        $query->execute();
        $query->closeCursor();
    }

	public function delete($id){
    	$query = $this->_db->prepare(' DELETE FROM t_todo
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();
		$query->closeCursor();
	}

	public function getTodoById($id){
    	$query = $this->_db->prepare(' SELECT * FROM t_todo
		WHERE id=:id')
		or die (print_r($this->_db->errorInfo()));
		$query->bindValue(':id', $id);
		$query->execute();		
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();
		return new Todo($data);
	}

	public function getTodos(){
		$todos = array();
		$query = $this->_db->query('SELECT * FROM t_todo
		ORDER BY id DESC');
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$todos[] = new Todo($data);
		}
		$query->closeCursor();
		return $todos;
	}
    
    public function getTodosToday(){
        $todos = array();
        $query = $this->_db->query(
        'SELECT todo FROM t_todo
        WHERE date=CURDATE()
        AND createdBy="admin"
        ORDER BY id DESC');
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $todos[] = $data['todo'];
        }
        $query->closeCursor();
        return $todos;
    }
    
    public function getTodosTodayInformation(){
        $todos = array();
        $query = $this->_db->query(
        'SELECT updated FROM t_todo
        WHERE date=CURDATE()
        AND createdBy="admin"
        ORDER BY id DESC');
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $todos[] = $data['updated'];
        }
        $query->closeCursor();
        return $todos;
    }
    
    public function getTodosOld(){
        $todos = array();
        $query = $this->_db->query(
        'SELECT todo FROM t_todo
        WHERE date<CURDATE()
        AND createdBy="admin"
        ORDER BY id DESC');
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $todos[] = $data['todo'];
        }
        $query->closeCursor();
        return $todos;
    }

	public function getTodosByLimits($begin, $end){
		$todos = array();
		$query = $this->_db->query('SELECT * FROM t_todo
		ORDER BY id DESC LIMIT '.$begin.', '.$end);
		while($data = $query->fetch(PDO::FETCH_ASSOC)){
			$todos[] = new Todo($data);
		}
		$query->closeCursor();
		return $todos;
	}

	public function getLastId(){
    	$query = $this->_db->query(' SELECT id AS last_id FROM t_todo
		ORDER BY id DESC LIMIT 0, 1');
		$data = $query->fetch(PDO::FETCH_ASSOC);
		$id = $data['last_id'];
		return $id;
	}

    public function getTodosNumber(){
        $query = $this->_db->query('SELECT COUNT(*) AS todoNumber FROM t_todo WHERE status=0');
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data['todoNumber'];
    }
    
    ////////////////////////////////////////////////////////////////////
    
    public function getTodosByUser($user){
        $todos = array();
        $query = $this->_db->prepare('SELECT * FROM t_todo WHERE createdBy=:user
        ORDER BY date DESC');
        $query->bindValue(':user', $user);
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC)){
            $todos[] = new Todo($data);
        }
        $query->closeCursor();
        return $todos;
    }
    
    public function getTodosNumberByUser($user){
        $query = $this->_db->prepare(
        'SELECT COUNT(*) AS todoNumber FROM t_todo 
        WHERE status=0 AND createdBy=:user');
        $query->bindValue(':user', $user);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return $data['todoNumber'];
    }

}