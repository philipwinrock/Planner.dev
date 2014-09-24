<?php

	//define('FILENAME', 'data/mylist.txt');
	//var_dump(FILENAME);
	// var_dump($_POST);
	// var_dump($_GET);

	require_once('./inc/filestore.php');

	 
	 $listObject = new Filestore('../data/list.txt');
	 $items = $listObject->read();


	if (isset($_POST['addToList'])) {
		
		$stringToValidate = trim($_POST['addToList']);

		// Validate Input
		if ( $stringToValidate == '' || strlen($stringToValidate) >240) {
			throw new Exception('Please enter an item.');
		}
		else {
			$items[]=$_POST['addToList'];
			$listObject->write($items);
		}
	}

	if (isset($_GET['remove'])){
		$removeKey = $_GET['remove'];
		unset($items[$removeKey]);
		$items = array_values($items);
		$listObject->write($items);

	}

	 // var_dump(!empty($_POST['addToList']))

	if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) {
    // Set the destination directory for uploads
    $upload_dir = '/vagrant/sites/planner.dev/public/uploads/';
    // Grab the filename from the uploaded file by using basename
    $filename = basename($_FILES['file1']['name']);
    // Create the saved filename using the file's original name and our upload directory
    $saved_filename = $upload_dir . $filename;
    // Move the file from the temp location to our uploads directory
    move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);
    $newListObject = new Filestore($saved_filename);
    $arrayFromNewObject = $newListObject->read();
    $items = array_merge($arrayFromNewObject , $items);
    $listObject->write($items);
}

// Check if we saved a file
if (isset($saved_filename)) {
    // If we did, show a link to the uploaded file
    // echo "<p>You can download your file <a href='/uploads/{$filename}'>here</a>.</p>";
}


if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) {
    // Set the destination directory for uploads
    $upload_dir = '/vagrant/sites/codeup.dev/public/uploads/';
    // Grab the filename from the uploaded file by using basename
    $filename = basename($_FILES['file1']['name']);
    // Create the saved filename using the file's original name and our upload directory
    $saved_filename = $upload_dir . $filename;
    // Move the file from the temp location to our uploads directory
    move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);
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

							  
    			<? foreach ($items as $key => $value): ?>
				 
				 	<li><a href="?remove=<?=$key?>"><button>Completed</button></a><?=$value?></li>
					
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