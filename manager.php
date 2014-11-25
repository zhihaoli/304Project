<html>
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">

<title>Manager gets to do this stuff!!</title>
<!--
    A simple stylesheet is provided so you can modify colours, fonts, etc.
-->
    <link href="bookbiz.css" rel="stylesheet" type="text/css">

<!--
    Javascript to submit a upc as a POST form, used with the "delete" links
-->
<script>
function formSubmit(upc) {
    'use strict';
    if (confirm('Are you sure you want to delete this item?')) {
      // Set the value of a hidden HTML element in this form
      var form = document.getElementById('delete');
      form.upc.value = upc;
      // Post this form
      form.submit();
    }
}
</script>
</head>

<body>
<h1>Manage CD and DVD Inventory</h1>
<?php
    /****************************************************
     STEP 1: Connect to the bookbiz MySQL file and use o8r6 database
     ****************************************************/

    // CHANGE this to connect to your own MySQL instance in the labs or on your own computer
    $connection = new mysqli("localhost:3306", "root", "c", "cs304store");

    // Check that the connection was successful, otherwise exit
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    /****************************************************
     STEP 2: Detect the user action

     Next, we detect what the user did to arrive at this page
     There are 3 possibilities 1) the first visit or a refresh,
     2) by clicking the Delete link beside a book title, or
     3) by clicking the bottom Submit button to add a book title
     
     NOTE We are using POST superglobal to safely pass parameters
        (as opposed to URL parameters or GET)
     ****************************************************/

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      if (isset($_POST["submitDelete"]) && $_POST["submitDelete"] == "DELETE") {
       /*
          Delete the selected book title using the upc
        */
       
       // Create a delete query prepared statement with a ? for the upc
       $stmt = $connection->prepare("DELETE FROM item WHERE upc=?");
       $deleteUPC = $_POST['upc'];
       // Bind the upc parameter, 's' indicates a string value
       $stmt->bind_param("s", $deleteUPC);
       
       // Execute the delete statement
       $stmt->execute();
          
       if($stmt->error) {
         printf("<b>Error: %s.</b>\n", $stmt->error);
       } else {
         echo "<b>Successfully deleted ".$deleteUPC."</b>";
       }
            
      } elseif (isset($_POST["submit"]) && $_POST["submit"] ==  "ADD") {       
       /*
        Add item using the post vars upc, title, item_type, category, company, item_year, price, stock.
        */
        $upc = $_POST["new_upc"];
        $title = $_POST["new_title"];
		$item_type = $_POST["new_item_type"];
		$category = $_POST["new_category"];

        $company = $_POST["new_company"];
		$item_year = $_POST["new_item_year"];
		$price = $_POST["new_price"];
		$stock = $_POST["new_stock"];
          
        $stmt = $connection->prepare("INSERT INTO item (upc, title, item_type, category, company, item_year, price, stock) VALUES (?,?,?,?,?,?,?,?)");
          
        // Bind all 8 parameters, 'sss' indicates 3 strings: want to do "sssssidi"
        $stmt->bind_param("sssssidi", $upc, $title, $item_type, $category, $company, $item_year, $price, $stock);
        
        // Execute the insert statement
        $stmt->execute();
          
        if($stmt->error) {       
          printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
          echo "<b>Successfully added ".$title."</b>";
        }
      } elseif (isset($_POST["submit"]) && $_POST["submit"] ==  "UPDATE") {
       /*
		Add input quantity to existing stock using post variables upc, qty.
        */
		$upc = $_POST["existing_upc"];
		$qty = $_POST["additional_stock"];

	// Prepare statement so we don't get haX0r'd.
	$stmt = $connection->prepare("UPDATE item SET stock = stock + $qty WHERE upc = '$upc'");

	//Execute the update statement
	$stmt->execute();

	if($stmt->error) {
          printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
          echo "<b>Successfully increased stock for upc: ".$upc."</b>";
        }
      }
   }
?>

<h2>Item Titles in alphabetical order</h2>
<!-- Set up a table to view the item titles -->
<table border=0 cellpadding=0 cellspacing=0>
<!-- Create the table column headings -->

<tr valign=center>
<td class=rowheader>upc</td>
<td class=rowheader>Title</td>
<td class=rowheader>CD or DVD?</td>
<td class=rowheader>Category (genre)</td>
<td class=rowheader>company</td>
<td class=rowheader>Year</td>
<td class=rowheader>Price</td>
<td class=rowheader>Stock</td>

</tr>

<?php
    /****************************************************
     STEP 3: Select the most recent list of item titles
     ****************************************************/

   // Select all of the item rows columns upc, title, item_type, category, company, year, price, stock
    if (!$result = $connection->query("SELECT upc, title, item_type, category, company, item_year, price, stock FROM item ORDER BY title")) {
        die('There was an error running the query [' . $db->error . ']');
    }

    // Avoid Cross-site scripting (XSS) by encoding PHP_SELF (this page) using htmlspecialchars.
    echo "<form id=\"delete\" name=\"delete\" action=\"";
    echo htmlspecialchars($_SERVER["PHP_SELF"]);
    echo "\" method=\"POST\">";
    // Hidden value is used if the delete link is clicked
    echo "<input type=\"hidden\" name=\"upc\" value=\"-1\"/>";
   // We need a submit value to detect if delete was pressed 
    echo "<input type=\"hidden\" name=\"submitDelete\" value=\"DELETE\"/>";


    /****************************************************
     STEP 4: Display the list of item titles
     ****************************************************/
    // Display each item title databaserow as a table row
    while($row = $result->fetch_assoc()){
        
       echo "<td>".$row['upc']."</td>";
       echo "<td>".$row['title']."</td>";
       echo "<td>".$row['item_type']."</td>";
       echo "<td>".$row['category']."</td>";
       echo "<td>".$row['company']."</td>";
       echo "<td>".$row['item_year']."</td>";
       echo "<td align='right'>".$row['price']."</td>";
       echo "<td align='right'>".$row['stock']."</td><td>";
       
       //Display an option to delete this title using the Javascript function and the hidden title_id
       echo "<a href=\"javascript:formSubmit('".$row['upc']."');\">DELETE</a>";
       echo "</td></tr>";
        
    }
    echo "</form>";

    // Close the connection to the database once we're done with it.
    mysqli_close($connection);
?>

</table>

<h2>Add a New Item</h2>

<!--
  /****************************************************
   STEP 5: Build the form to add an item
   ****************************************************/
    Use an HTML form POST to add a book, sending the parameter values back to this page.
    Avoid Cross-site scripting (XSS) by encoding PHP_SELF using htmlspecialchars.

    This is the simplest way to POST values to a web page. More complex ways involve using
    HTML elements other than a submit button (eg. by clicking on the delete link as shown above).
-->

<form id="add" name="add" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table border=0 cellpadding=0 cellspacing=0>
        <tr><td>item UPC</td><td><input type="text" size=30 name="new_upc"</td></tr>
        <tr><td>Title</td><td><input type="text" size=30 name="new_title"</td></tr>

<!--	<tr><td>Type (CD or DVD)</td><td><input type="text" size=5 name="new_item_type"</td></tr>
-->
		<tr><td>Type</td><td><select name="new_item_type"><option value="CD">CD</option><option value="DVD">DVD</option></select></td>


	<tr><td>Category (genre)</td><td><input type="text" size=30 name="new_category"</td></tr>
        <tr><td>Company</td><td> <input type="text" size=30 name="new_company"></td></tr>
        <tr><td>Year</td><td> <input type="number" size=5 name="new_item_year"></td></tr>
        <tr><td>Price</td><td> <input type="value" size=5 name="new_price"></td></tr>
	<tr><td>Quantity</td><td> <input type="number" size=5 name="new_stock" min=0></td></tr>
        <tr><td></td><td><input type="submit" name="submit" border=0 value="ADD"></td></tr>
    </table>
</form>


<h2>Update stock for an existing Item</h2>

<!--
  /****************************************************
   STEP 5: Build the form to update stock of an existing item
   ****************************************************/
    Use an HTML form POST to update the stock of an item, sending the parameter values back to this page.
    Avoid Cross-site scripting (XSS) by encoding PHP_SELF using htmlspecialchars.

    This is the simplest way to POST values to a web page. More complex ways involve using
    HTML elements other than a submit button (eg. by clicking on the delete link as shown above).
-->

<form id="update" name="update" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table border=0 cellpadding=0 cellspacing=0>
        <tr><td>item UPC</td><td><input type="text" size=30 name="existing_upc"</td></tr>
        <tr><td>Quantity</td><td> <input type="number" size=5 name="additional_stock"></td></tr>
        <tr><td></td><td><input type="submit" name="submit" border=0 value="UPDATE"></td></tr>
    </table>
</form>







</body>
</html>
