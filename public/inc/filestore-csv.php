<?php

class Filestore {

public $filename = '';
protected $is_csv;

public function __construct($filename = '')
{
         // Sets $this->filename
  $this->filename = $filename;
  $this->is_csv =substr($this->filename , -3) =='csv'? TRUE :FALSE;
}

public function read(){

  if($this->is_csv){
    return $this->read_csv();
       
  }
  else {
    return $this->read_lines();
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

 
  // Returns array of lines in $this->filename
      
private function read_lines(){
      
$line =[];

if (filesize($this->filename) == 0) {
  $filesize = 100;
    
}
  else {
     $filesize = filesize($this->filename);
}

$handle = fopen($this->filename , 'r');

      //read in entire file and remove any blank lines
$content = trim(fread($handle , $filesize));

      //always close the file
fclose($handle);

      //split the contents of file into an array
$lines = explode("\n", $content);


      // Returns a list of items in an array, from the filename specified.
return $lines;
}
     

     /**
      * Writes each element in $array to a new line in $this->filename
      */
private function write_lines($array){
      
  $handle = fopen($this->filename, "w");
  foreach ($array as $line) {
    fwrite($handle, $line . PHP_EOL);
}
  fclose($handle);
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

     

 
