<?php

	// Flow of the todo app:
	// 1. Establish your DB connection
	// 2. Query the DB to get our todo_list items
	// 3. Add items to the DB

	// Include external class Filestore
require_once('./inc/filestore.php');

	/* ----------------------------------------------- */
	// Create PDO Object (Establish your DB connection)

$dbc = new PDO('mysql:host=127.0.0.1;dbname=todo_list', 'codeup', 'rocks');

	// Tell PDO to throw exceptions on error
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Output connection status
	// echo $dbc->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "\n";

	/* ----------------------------------------------- */

	/* ----------------------------------------------- */
	// Query the DB to get our todo_list items
function getItems($dbc) {
		$stmt = $dbc->query('SELECT id, content FROM todo_items');
		$todoItems = [];
		
		// CREATE TODO ITEM OBJECTS FROM EACH RECORD IN DB
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			// $entry = ['id' => '', 'content' => ''];
		 //    $entry['id'] = $row['id']; 
		 //    $entry['content'] = $row['content'];
		    $todoItems[] = $row;
	}
		
		// RETURN AN ARRAY OF ALL THE TODO ITEMS
		return $todoItems;
}
	/* ----------------------------------------------- */

	/* ----------------------------------------------- */
	// Remove item from database
function removeItem($dbc, $itemToRemove) {
		// Use a DELETE statement, and the $dbc
		// DELETE FROM table_name WHERE some_column=some_value;
		$query = 'DELETE FROM todo_items WHERE id = :id';
		$remove_stmt = $dbc->prepare($query);
		$remove_stmt->bindValue(':id', $itemToRemove, PDO::PARAM_INT);
		$remove_stmt->execute();

}

	/* ----------------------------------------------- */

	/* ----------------------------------------------- */
	// (Add items to the DB)
function addItem($dbc, $itemToAdd) {
		$query = ('INSERT INTO todo_items (content) VALUES (:content)');
		$stmt = $dbc->prepare($query);
		$stmt->bindValue(':content', $itemToAdd, PDO::PARAM_STR);
		$stmt->execute();
}

	/* ----------------------------------------------- */


	 
	// Populate our $items variable with the returned data from getItems()
	//$items = getItems($dbc);
	// var_dump($items);

$listObject = new Filestore($dbc);
$items = $listObject->getItems();
	//var_dump($listObject);


	// Check for POST data, and add item
if (isset($_POST['addToList'])) {
		
	$stringToValidate = trim($_POST['addToList']);

		// Validate Input
	if ( $stringToValidate == '' || strlen($stringToValidate) >240) {
			throw new Exception('Please enter an item.');
	}
		else {
			
			//$items[]=$_POST['addToList'];
			//$listObject->write($items);

			// Add one item to database
			$itemToAdd = $_POST['addToList'];
			$listObject->addItem($itemToAdd);
			$items = $listObject->getItems();
		}
}

if (isset($_GET['remove'])){
	$itemToRemove = $_GET['remove'];
		//unset($items[$removeKey]);
		//$items = array_values($items);
		//$listObject->write($items);

	$listObject->removeItem($itemToRemove);
	$items = $listObject->getItems();
}

	// UPLOAD FILES
if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) {
    // Set the destination directory for uploads
    $upload_dir = '/vagrant/sites/planner.dev/public/uploads/';
    // Grab the filename from the uploaded file by using basename
    $filename = basename($_FILES['file1']['name']);
    // Create the saved filename using the file's original name and our upload directory
    $saved_filename = $upload_dir . $filename;
    // Move the file from the temp location to our uploads directorys
    move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);
    
    $newListObject = new Filestore($dbc, $saved_filename);
    var_dump($newListObject);
    $arrayFromNewObject = $newListObject->read();
    var_dump($arrayFromNewObject);
	    foreach ($arrayFromNewObject as $external_item) {
	    	$listObject->addItem($external_item);
	    }
	$items = $listObject->getItems();
}

// Check if we saved a file
if (isset($saved_filename)) {
    // If we did, show a link to the uploaded file
    echo "<p>You can download your file <a href='/uploads/{$filename}'>here</a>.</p>";
}

?>

<DOCTYPE>
<html>
 <head>
    <title>todo.php</title>
    <link rel="stylesheet" href="/css/site.css">
	<title>To do List</title>
</head>
<body>
<style>body{ background-color: #d0e4fe; }</style>	
<h1>TO DO LIST</h1>
		
<ol>

<h1>Upload File</h1>

<form method="POST" enctype="multipart/form-data" action="/todo_list.php">
<p>
    <label for="file1">File to upload: </label>
    <input type="file" id="file1" name="file1">
</p>

<p>
    <input type="submit" value="Upload">
</p>

</form>

	<!-- Loop through items array and output the database id and content for each item -->
	<? foreach ($items as $key => $dbEntry): ?>
			
		<li><a href="?remove=<?=$dbEntry['id']?>"><button>Completed</button></a><?=$dbEntry['content']?></li>
		
	<? endforeach; ?>
				 
</ol>

<form method="POST" action="/todo_list.php">
<p>
	<label for="username">AddToList: </label>
	<input id="addToList" name="addToList" type="text">
 	<!-- <input id="additem" name="additem" rows="5" cols="40"></input> -->
	<input type="Submit" value= "Send"></input>
</p>


</form>

</body>
</html> 