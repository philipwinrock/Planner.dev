<?php


class ToDoItem {
	protected$dbc;
	protected$id;
	protected$content "";
	protected$date_added;
	protected$date_completed;

	// constructor for todo items//
	public function__constructor($dbc,$id = null){
		$this->$dbc=$dbc
		//check if item already is there//
		if)isset($id)){
	//assign id from existing item//
	$this->id=$id;
	//statement to read in content of todo item//
	$selectStmt=$this->dbc->prepared('SELECT * FROM todo_items WHERE id= ?');
	$selectStmt->execute([$this->id]);
	//copy associative array of properties from db record//
	$row=$selectStmt->fetch(PDO::FETCH_ASSOC);
	//assign object prop from fetch array//
	$this->content        =$row['content'];
	$this->date_added     =new DateTime($row['date_added']);
	$this->date_completed =new DateTime($row['date_completed']);
}
	}

}
	public function getContent() {
	return $this->content;
}

	public function insert(){
	//time stamp//
	$this->date_added = new DateTime();
}	$this->content    =$_POST['content']











