<!DOCTYPE html>
<html>
    <head>
    	 
       
				<title>To do List</title>

	</head>

	<body>
		<center>
		<?php
			var_dump($_POST);
			var_dump($_GET);
		?>
		</center>

		<center><h1>TO DO LIST</h1></center>
		<p></p>
		<p></p>
    	<center><ul></center>

  	  <center><li>Wash and wax the car.</li>
 	  <li>Clean out the garage.</li>
 	  <li>Get flea shampoo for dog.</li></center>


			<center></ul></center>
   
            


 <center>RequestBin</center>



<form method="POST">
    <p>
	     <center><label for="username">AddToList</label></center>
	    <!-- <input id="addToList" name="addToList" type="text">
 -->
  <center><textarea id="email_body" name="email_body" rows="5" cols="40"></textarea></center>

    </p>

    <p>
		 <center><input type="submit" value= "Send"></center>
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