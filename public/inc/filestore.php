<?php

class Filestore {

protected $dbc;
protected $is_csv;
public $uploaded_filename;

public function __construct($dbc, $uploaded_filename = '')
{
  // Sets $this->filename
  $this->dbc = $dbc;
  $this->is_csv =substr($this->uploaded_filename , -3) =='csv'? TRUE :FALSE;
  $this->uploaded_filename = $uploaded_filename;
}

public function read(){

  if($this->is_csv){
    // This one reads csvs for address book
    return $this->read_csv();
       
  }
  else {
    // This one reads txt file for todo list
    return $this->getItems();
  } 
      
}

public function write($array){

    if($this->is_csv == TRUE){
          // var_dump ($this->writeCsv);
      return $this->write_csv($array); 
  }
    else{
      return $this->write_lines($array); 
  }
}
      
public function getItems() {
  $stmt = $this->dbc->query('SELECT id, content FROM todo_items');
  $todoItems = [];
  
  // CREATE TODO ITEM OBJECTS FROM EACH RECORD IN DB
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $todoItems[] = $row;
  }
  
  // RETURN AN ARRAY OF ALL THE TODO ITEMS
  return $todoItems;
}
     

     /**
      * Writes each element in $array to a new line in $this->filename
      */
  function addItem( $itemToAdd) {
    $query = ('INSERT INTO todo_items (content) VALUES (:content)');
    $stmt = $this->dbc->prepare($query);
    $stmt->bindValue(':content', $itemToAdd, PDO::PARAM_STR);
    $stmt->execute();
  }
    function removeItem($itemToRemove) {
    // Use a DELETE statement, and the $dbc
    // DELETE FROM table_name WHERE some_column=some_value;
    $query = 'DELETE FROM todo_items WHERE id = :id';
    $remove_stmt = $this->dbc->prepare($query);
    $remove_stmt->bindValue(':id', $itemToRemove, PDO::PARAM_INT);
    $remove_stmt->execute();
}
     /**
      * Reads contents of csv $this->filename, returns an array
      */
private function read_csv(){

    // Code to read file $this->filename
    // CREATE EMPTY $addressBook
  $csvRows = [];
  $handle = fopen($this->filename, 'r');

  while(!feof($handle)) {
    $row = fgetcsv($handle);
     if(!empty($row)) {
        $csvRows[] = $row;
}
}
  fclose($handle);
  return $csvRows;
}

     /**
      * Writes contents of $array to csv $this->filename
      */
private function write_csv($array)
{
      // Open file and overwrite contents.
  $handle = fopen($this->filename, 'w');
     // Loop through the entry and write to the csv file.
  foreach ($array as $row) {
    fputcsv($handle, $row);
          
}

fclose($handle);
}

}

     

 
