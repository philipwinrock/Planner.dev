<!DOCTYPE html>
<html>
    <head>
    	 
    <title>CSS Test Page</title>
    <link rel="stylesheet" href="/css/site.css">

       
				<title>To do List</title>

	</head>

	<body>
		
		<?php
			var_dump($_POST);
			var_dump($_GET);
		?>
		
		 
		<h1>TO DO LIST</h1>
		<p></p>
		<p></p>
    	<ul>

  	  <li>Wash and wax the car.</li>
 	  <li>Clean out the garage.</li>
 	  <li>Get flea shampoo for dog.</li><


</ul>
   
            


RequestBin



<form method="POST">
    <p>
	     <label for="username">AddToList</label>
	    <!-- <input id="addToList" name="addToList" type="text"> -->
 
  <textarea id="email_body" name="email_body" rows="5" cols="40"></textarea>

    </p>

    <p>
		 <input type="submit" value= "Send">
	</p>





</form>





<!-- within the file you just created, build an HTML document with a title of: "TODO List".
In the body of the document, place a heading that says: "TODO List".
Below the heading, place an unordered list with some sample to-do items.
Below the unordered list, create a form that contains the necessary inputs to add a TODO item to the list. Add a heading above the form describing the function of the form.
Test the form using RequestBin.
Save your HTML file, commit the file to your git repository, and push to GitHub.
 -->


     
</body>


</html> 