<?php

// Flow of the todo app:
// 1. Establish your DB connection
// 2. Query the DB to get our address items
// 3. Add items to the DB

require_once('inc/address_data_store.php');
class InvalidInputException extends Exception { }

// Create an object
//$addressBookObject = new AddressDataStore('../data/address_book.csv');

/* ----------------------------------------------- */
// Create PDO Object (Establish your DB connection)

$dbc = new PDO('mysql:host=127.0.0.1;dbname=address_book', 'codeup', 'rocks');

// Tell PDO to throw exceptions on error
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Output connection status
// echo $dbc->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "\n";

/* ----------------------------------------------- */

//Populates our addressBook with returned data from getItems
$addressBook = getEntries($dbc);

// $addressBook = $addressBookObject->read();

/* ----------------------------------------------- */
// Query the DB to get our address items
function getEntries($dbc) {
    $stmt = $dbc->query('SELECT id, name, address, city, state, zip, phone FROM address_book');
    $addressBookItems = [];
    
    // CREATE TODO ITEM OBJECTS FROM EACH RECORD IN DB
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $addressBookItems[] = $row;
    }
    
    // RETURN AN ARRAY OF ALL THE TODO ITEMS
    return $addressBookItems;

}

/* ----------------------------------------------- */

/* ----------------------------------------------- */
// (Add items to the DB)
function addEntry($dbc, $newEntry) {
    $query = ('INSERT INTO address_book (name, address, city, state, zip, phone) 
        VALUES (:name, :address, :city,:state, :zip, :phone)');
    $stmt = $dbc->prepare($query);
    $stmt->bindValue(':name',   $newEntry['name'],     PDO::PARAM_STR);
    $stmt->bindValue(':address',$newEntry['address'],  PDO::PARAM_STR);
    $stmt->bindValue(':city',   $newEntry['city'],     PDO::PARAM_STR);
    $stmt->bindValue(':state',  $newEntry['state'],    PDO::PARAM_STR);
    $stmt->bindValue(':zip',    $newEntry['zip'],      PDO::PARAM_STR);
    $stmt->bindValue(':phone',  $newEntry['phone'],    PDO::PARAM_STR);

    $stmt->execute();
}
/* _________________________________________________________________________*/

$offset= isset($_GET['offset']) ? intval($_GET['offset']) : 0;
// function getAddress
$stmt = $dbc->query("SELECT name, address ,city , state, zip , phone FROM address_book 

LIMIT 10 OFFSET $offset");

$addressBook = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count=(int)$dbc->query('SELECT count(*) FROM address_book')->fetchColumn();

/* ----------------------------------------------- 

/* ----------------------------------------------- */
// Remove item from database
// GET REQUEST TO REMOVE SINGLE ENTRIES FROM THE ADDRESS BOOK
function removeEntry($dbc, $itemToRemove) {
    // Use a DELETE statement, and the $dbc
    // DELETE FROM table_name WHERE some_column=some_value;
    $query = 'DELETE FROM address_book WHERE id = :id';
    $remove_stmt = $dbc->prepare($query);
    $remove_stmt->bindValue(':id', $itemToRemove, PDO::PARAM_INT);
    $remove_stmt->execute();

}

/* ----------------------------------------------- */

if (count($_FILES) > 0 && $_FILES['fileupload']['error'] == UPLOAD_ERR_OK) {

    // Set the destination directory for uploads
    $upload_dir = '/vagrant/sites/planner.dev/public/uploads/';

    // Grab the filename from the uploaded file by using basename
    $filename = basename($_FILES['fileupload']['name']);

    // Create the saved filename using the file's original name and our upload directory
    $saved_filename = $upload_dir . $filename;

    // Move the file from the temp location to our uploads directory
    move_uploaded_file($_FILES['fileupload']['tmp_name'], $saved_filename);

    foreach ($addressBook as $newListObject) {
        addItem($dbc, $newListObject);
    }

    $items = getEntries($dbc);
}            # code...
        
    // $uploadedfile = openfile($saved_filename);
    // $addressBook = array_merge($uploadedfile , $addressBook);
   

    // var_dump($_FILES);
if (!empty($_FILES) && $_FILES['fileupload']['error'] == UPLOAD_ERR_OK){
    $importedList = new AddressDataStore($saved_filename);
    $newList = $importedList->read();
        // var_dump($newList);
    $addressBook = array_merge($addressBook,$newList);
    // $addressBookItems->write($addressBook);

}

$saveToFile = "";

if (!empty($_POST)) {

    // Set boolean, to determine if we save later on or not
    $saveToFile = true;
            
    try {

        $error_message = '';

        // Loop throught POST data
        foreach ($_POST as $key => $stringToValidate) {
            // Make sure each value in POST data is not empty, and not greater than 125 characters
            if (empty($stringToValidate) || strlen($stringToValidate) > 125) {
            // If it meets this above condition, modify boolean and throw error exception
                $error_message .= " $key";
            }
        }
        if (!empty($error_message)) {
            throw new Exception('Invalid Input' . $error_message);
            $saveToFile = false;
        }
    }
    catch (Exception $e) {
        echo "<div class='alert alert-danger'>{$e->getMessage()}</div>";
    }

    // If all inputs are validated, and boolean value is still true - then run code to save.
    if ($saveToFile) {

        foreach ($_POST as $key => $input) {
            $_POST[$key] = strip_tags($input);
        }

        $newEntry = [];
        // ASSIGN FORM INPUT DATA TO SPECIFIC INDEXES
        $newEntry['name'] =     $_POST['name'];
        $newEntry['address'] =  $_POST['address'];
        $newEntry['city'] =     $_POST['city'];
        $newEntry['state'] =    $_POST['state'];
        $newEntry['zip'] =      $_POST['zip'];
        $newEntry['phone'] =    $_POST['phone'];
                    
        // PUSH FORM ADDRESS BOOK ENTRY INTO ADDRESS BOOK ARRAY.
        //$addressBook[] = $newEntry;
        //$addressBookObject->write($addressBook);

        addEntry($dbc, $newEntry);
        $addressBook = getEntries($dbc);

    }    
}

if (!empty($_GET)) {
    $entryToRemove = $_GET['remove'];
    removeEntry($dbc, $entryToRemove);
    $addressBook = getEntries($dbc);
}



?>
 
<!DOCTYPE html>

<html>
<head>
    <title>Address Book</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/address_styles.css" >
</head>
<body>
    <div id="wrap">
        <div id ="addressBook" class="row">
            <div class="col-md-7 table-responsive">

                <table class="table table-striped table-bordered table-condensed">
                    <tr>
                        <!-- <th><center>Id</center></th> -->
                        <th><center>Name</center></th>
                        <th><center>Address</center></th>
                        <th><center>City</center></th>
                        <th><center>State</center></th>
                        <th><center>Zip Code</center></th>
                        <th><center>Phone Number</center></th>
                        <th><center>Delete</center></th>
                    </tr>                      
                                            
                    <style>body{ background-color: #d0e4fe; }</style>


                    <!-- LOOPING THROUGH TOP LEVEL ARRAY OF ADDRESS BOOK ENTRIES -->
                    <? foreach($addressBook as $entry => $row): ?>
                    <tr>

                    <!-- LOOPING THROUGH ARRAY OF CONTACT ENTRIES, PRINTING DATA INTO TABLE COLUMNS -->
                    <? foreach($row as $columnData): ?>
                        <td>
                            <?= $columnData ?>
                        </td>

                        <? endforeach; ?>

                    <td><a href="?remove=<?=$row['id']?>">Delete</a></td>
                    </tr>

                    <? endforeach; ?>
                </table>

            </div>
            <div class="col-md-3 col-md-offset-1">

                <form method="POST" action="/address_book.php" class="form-horizontal">
                    <h2><center>Address Book</center></h2>
                    <h4><center>Add Contact:</center></h4>
                    <label for="name">Name: </label> <input type="text" name="name" id="name" class="form-control"> <br>
                    <label for="address">Address: </label> <input type="text" name="address" id="address" class="form-control"> <br>
                    <label for="city">City: </label> <input type="text" name="city" id="city" class="form-control"> <br>
                    <label for="state">State: </label> <input type="text" name="state" id="state" class="form-control"> <br>
                    <label for="zip">Zip: </label> <input type="text" name="zip" id="zip" class="form-control"> <br>
                    <label for="phone">Phone: </label> <input type="tel" name="phone" id="phone" class="form-control"> <br>
                    <input type="submit" value="Submit" class="btn btn-primary">
                </form>                             

                <form method="POST" action="/address_book.php" enctype="multipart/form-data" class="form-horizontal">

                    <label for="upload">File to Import:</label>
                    <input type="file" name="fileupload" id="upload" class="form-control"><br>
                    <input type="submit" value="Import" class="btn btn-primary">

                </form>
            </div>
        </div>
    </div>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
</body>
</html>
    

   
 

                        
                        
   

 

