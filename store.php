    <html>
    <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    <title>CPSC 304 Bookbiz</title>
    <!--
        A simple stylesheet is provided so you can modify colours, fonts, etc.
    -->
        <link href="bookbiz.css" rel="stylesheet" type="text/css">

    <!--
        Javascript to submit a title_id as a POST form, used with the "delete" links
    -->
    <script>
    function formSubmit(titleId) {
        'use strict';
        if (confirm('Are you sure you want to delete this title?')) {
          // Set the value of a hidden HTML element in this form
          var form = document.getElementById('delete');
          form.title_id.value = titleId;
          // Post this form
          form.submit();
        }
    }
    </script>
    </head>

    <body>
    <h1>Manage Book Inventory</h1>
    <h2>Search Results</h2>
    <!-- Set up a table to view the book titles -->
    <table border=0 cellpadding=0 cellspacing=0>
    <!-- Create the table column headings -->

    <tr valign=center>
    <td class=rowheader>UPC</td>
    <td class=rowheader>Title</td>
    <td class=rowheader>Lead Singer</td>
    <td class=rowheader>Type</td>
    <td class=rowheader>Category</td>
    <td class=rowheader>Company</td>
    <td class=rowheader>Year</td>
    <td class=rowheader>Available Stock</td>
    <td class=rowheader>Price</td>
    <td class=rowheader>Quantity</td>
    </tr>
    <?php
        /****************************************************
         STEP 1: Connect to the bookbiz MySQL database
         ****************************************************/

        // CHANGE this to connect to your own MySQL instance in the labs or on your own computer
        $connection = new mysqli("localhost:3306", "root", "", "store");

        // Check that the connection was successful, otherwise exit
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

          if (isset($_POST["submitDelete"]) && $_POST["submitDelete"] == "DELETE") {
           /*
              Delete the selected book title using the title_id
            */
           
           // Create a delete query prepared statement with a ? for the title_id
           $stmt = $connection->prepare("DELETE FROM titles WHERE title_id=?");
           $deleteTitleID = $_POST['title_id'];
           // Bind the title_id parameter, 's' indicates a string value
           $stmt->bind_param("s", $deleteTitleID);
           
           // Execute the delete statement
           $stmt->execute();
              
           if($stmt->error) {
             printf("<b>Error: %s.</b>\n", $stmt->error);
           } else {
             echo "<b>Successfully deleted ".$deleteTitleID."</b>";
           }
                
          } elseif (isset($_POST["submit"]) && $_POST["submit"] ==  "ADD") {       
           /*
            Add a book title using the post variables title_id, title and pub_id.
    	*/
    	      echo "<b>inside ADD if uhhh</b>";
            $title_id = $_POST["new_title_id"];
            $title = $_POST["new_title"];
            $pub_id = $_POST["new_pub_id"];
              
            $stmt = $connection->prepare("INSERT INTO titles (title_id, title, pub_id) VALUES (?,?,?)");
              
            // Bind the title and pub_id parameters, 'sss' indicates 3 strings
            $stmt->bind_param("sss", $title_id, $title, $pub_id);
            
            // Execute the insert statement
            $stmt->execute();
              
            if($stmt->error) {       
              printf("<b>Error: %s.</b>\n", $stmt->error);
            } else {
              echo "<b>Successfully added ".$title."</b>";
            }
          } elseif (isset($_POST["submitSearch"]) && $_POST["submitSearch"] == "SEARCH"){
    		    echo "inside Search if uhhh";
    		    $query = "SELECT I.upc, title, L.name, item_type, category, company, item_year, stock, price FROM item I, leadSinger L WHERE I.upc=L.upc";

    		    $category = $_POST['category_input'];
    		    $title = $_POST['title_input'];
    	     	$leadSinger = $_POST['leadSinger_input'];

    		    if($category != '') {
    			   $query .= " AND I.category LIKE '%$category%'";
    		    }
    			
    		    if($title != '') {
    			   $query .= " AND I.title LIKE '%$title%'";
    		    }

    		    if($leadSinger != '') {
    			   $query .= " AND L.name LIKE '%$leadSinger%'";
    		    }

    		    $query .= ";"; 
    		    echo $query;

            if (! $result = $connection->query($query)) {
              die("No results found.");
            }

                echo "<form id=\"delete\" name=\"delete\" action=\"";
    echo htmlspecialchars($_SERVER["PHP_SELF"]);
    echo "\" method=\"POST\">";
    // Hidden value is used if the delete link is clicked
    echo "<input type=\"hidden\" name=\"title_id\" value=\"-1\"/>";
   // We need a submit value to detect if delete was pressed 
    echo "<input type=\"hidden\" name=\"submitDelete\" value=\"DELETE\"/>";

            while($row = $result->fetch_assoc()){
              
              echo "<td>".$row['upc']."</td>";
              echo "<td>".$row['title']."</td>";
              echo "<td>".$row['name']."</td>";
              echo "<td>".$row['item_type']."</td>";
              echo "<td>".$row['category']."</td>";
              echo "<td>".$row['company']."</td>";
              echo "<td>".$row['item_year']."</td>";
              echo "<td>".$row['stock']."</td>";
              echo "<td>".$row['price']."</td>";
              echo "<td><select type=\"number\" size=1 name=\"quantity\"><option selected=\"selected\">0</option>";
              $stock= $row['stock'];
              $i = 1;
              while($i <= $stock){
                echo "<option>".$i."</option>";
                $i++;
              }
              echo "</select></td><td>";
              echo "<a href=\"javascript:formSubmit('".$row['upc']."');\">ADD TO SHOPPING CART</a>";
              echo "</td></tr>";
            }  
        }
            echo "</form>";

    // Close the connection to the database once we're done with it.
    mysqli_close($connection);
       }
    ?>

    </table>

    <h2>Add a New Book Title</h2>

    <!--
      /****************************************************
       STEP 5: Build the form to add a book title
       ****************************************************/
        Use an HTML form POST to add a book, sending the parameter values back to this page.
        Avoid Cross-site scripting (XSS) by encoding PHP_SELF using htmlspecialchars.

        This is the simplest way to POST values to a web page. More complex ways involve using
        HTML elements other than a submit button (eg. by clicking on the delete link as shown above).
    -->

    <form id="add" name="add" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table border=0 cellpadding=0 cellspacing=0>
            <tr><td>Book Title ID</td><td><input type="text" size=30 name="new_title_id"</td></tr>
            <tr><td>Book Title</td><td><input type="text" size=30 name="new_title"</td></tr>
            <tr><td>Publisher ID:</td><td> <input type="text" size=5 name="new_pub_id"></td></tr>
            <tr><td></td><td><input type="submit" name="submit" border=0 value="ADD"></td></tr>
        </table>
    </form>
    <form id="search" name="search" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table border=0 cellpadding=0 cellspacing=0>
    	<tr><td>Title</td><td><input type="text" size=30 name="title_input"</td></tr>
            <tr><td>Category</td><td><input type="text" size=30 name="category_input"</td></tr>
            <tr><td>Lead Singer</td><td> <input type="text" size=5 name="leadSinger_input"></td></tr>
            <tr><td></td><td><input type="submit" name="submitSearch" border=0 value="SEARCH"></td></tr>
        </table>
    </form>
    </body>
    </html>
