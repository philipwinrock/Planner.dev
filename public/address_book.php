
<?php

class AddressDataStore {

    public $filename = '../data/address_book.csv';

    public function readCSVFile()
    {
        // Code to read file $this->filename
        // CREATE EMPTY $addressBook
        $addressBook = [];
        $handle = fopen($this->filename, 'r');

        while(!feof($handle)) {
            $row = fgetcsv($handle);
            if(!empty($row)) {
                $addressBook[] = $row;
            }
        }
        fclose($handle);
        return $addressBook;
    }

    public function saveCSVFile($addressBook)
    {
          // Open file and overwrite contents.
        $handle = fopen($this->filename, 'w');
        // Loop through the entry and write to the csv file.
        foreach ($addressBook as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);// Code to write $addresses_array to file $this->filename
    }

}

// INITIALIZE ADDRESS BOOK ARRAY VARIABLE
//$addressBook = [];
 
// LOAD SAVED ADDRESS BOOK CSV TO POPULATE ADDRESS BOOK TABLE
//$addressBook = readCSVFile();

// Create an object
$addressBookObject = new AddressDataStore();
$addressBook = $addressBookObject->readCSVFile();

 
//saveCSVFile($addressBook);
 
if (!empty($_POST['name']) && !empty($_POST['address']) && !empty($_POST['city']) && !empty($_POST['state']) && !empty($_POST['zip']) && !empty($_POST['phone'])) {
    // PREVENT CODE INJECTION ON EACH INPUT
    foreach ($_POST as $key => $input) {
        $_POST[$key] = strip_tags($input);
    }
    // ASSIGN FORM INPUT DATA TO SPECIFIC INDEXES
    $newEntry[0] = $_POST['name'];
    $newEntry[1] = $_POST['address'];
    $newEntry[2] = $_POST['city'];
    $newEntry[3] = $_POST['state'];
    $newEntry[4] = $_POST['zip'];
    $newEntry[5] = $_POST['phone'];
    
    // PUSH FORM ADDRESS BOOK ENTRY INTO ADDRESS BOOK ARRAY.
    $addressBook[] = $newEntry;
    // SAVE NEW ENTRY TO CSV FILE
    $addressBookObject->saveCSVFile($addressBook);
}
 
// GET REQUEST TO REMOVE SINGLE ENTRIES FROM THE ADDRESS BOOK
if (isset($_GET['remove'])) {
    $removeKey = $_GET['remove'];
    unset($addressBook[$removeKey]);
    $addressBook = array_values($addressBook);
    $addressBookObject->saveCSVFile($addressBook);
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
                        <th>Name</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Zip Code</th>
                        <th>Phone Number</th>
                        <th>Delete</th>
                        <style>body { background-color: #d0e4fe; } </style>
                        <style></style>
    

    

 
                    </tr>
                    <!-- LOOPING THROUGH TOP LEVEL ARRAY OF ADDRESS BOOK ENTRIES -->
                    <? foreach($addressBook as $entry => $row): ?>
                        <tr>
                            <!-- LOOPING THROUGH ARRAY OF CONTACT ENTRIES, PRINTING DATA INTO TABLE COLUMNS -->
                            <? foreach($row as $columnData): ?>
                                <td>
                                    <?= $columnData ?>
                                </td>
                            <? endforeach; ?>
                            <td><a href="?remove=<?=$entry?>">Delete</a></td>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div>
                <div class="col-md-3 col-md-offset-1">
                    <form method="POST" action="/address_book.php" class="form-horizontal">
                        <h2>Add a Contact</h2>
                        <label for="name">Name: </label> <input type="text" name="name" id="name" class="form-control"> <br>
                        <label for="address">Address: </label> <input type="text" name="address" id="address" class="form-control"> <br>
                        <label for="city">City: </label> <input type="text" name="city" id="city" class="form-control"> <br>
                        <label for="state">State: </label> <input type="text" name="state" id="state" class="form-control"> <br>
                        <label for="zip">Zip: </label> <input type="text" name="zip" id="zip" class="form-control"> <br>
                        <label for="phone">Phone: </label> <input type="tel" name="phone" id="phone" class="form-control"> <br>
                        <input type="submit" value="Submit" class="btn btn-primary">
                    </form>
                </div>
        </div>
    </div>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
</body>
</html>