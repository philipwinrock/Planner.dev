<?php
require_once('filestore.php');
class AddressDataStore extends filestore    {

    //public $filename = '../data/address_book.csv';

    public function __construct($filename = '') {

        parent::__construct(strtolower($filename));

    }
   
    public function readaddressBook()   {
    
        // Code to read file $this->filename
        // CREATE EMPTY $addressBook
        $addressBook = $this->read();
        return $addressBook;
    }
        
public function writeaddressBook($addressArray){
    
       $this->write($addressArray);
    }

}
