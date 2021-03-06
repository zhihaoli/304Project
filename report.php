<html>
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">

<title>Sales Report Generator</title>
<!--
   This is Bruce's Code. It contains the PHP/mySQL Queries for Daily Sales Report and Top Selling Items
-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/Registration.css" rel="stylesheet" type="text/css">

</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="HomePage.html">Allegro Music Store</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="clerk.php">Clerk</a></li>
                    <li><a href="registration.php">Customer</a></li>
                    <li><a href="report.php">Manager</a></li>
                    <li><a href="Godmode.html">TA Godmode</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="panel panel-body">
            <div class="starter-template">
                <h1>Manager Settings</h1>
                <p class="lead">Select a report to generate.</p>
            </div>





<!-- **************************DAILY SALES REPORT************************** -->
<div class="col-md-12">
<h2>Daily Sales Report</h2>
<?php
    //Connect to MySQL Localhost
    $connection = new mysqli("localhost", "root", "", "cs304");

    // Check that the connection was successful, otherwise exit
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

	//If the user presses the Generate Report Button
     if (isset($_POST["submitDate"]) && $_POST["submitDate"] ==  "GENERATE") {       

	//Take the user input as the date
	$date =  $_POST["date_daily_sales"];

	//MySQL Query to get the daily sales report
	$query = "select I.upc, I.category, I.price AS unit_price, SUM(P.quantity)  AS units, SUM(P.quantity)*I.price AS total_value
		from item I, i_order O, purchaseItem P 
		where O.order_date = '$date' && O.receiptId = P.receiptId && P.upc = I.upc
		group by P.upc
		order by I.category;";

	//Store the query result or give a response if no results were found
        if (!$result = $connection->query($query)) {
              die("No results found.");
            }	

	//Initialize variables for Sales Report Algorithm
	$index = 0;
	$prevCategory = "none";
	$accumUnit = 0;
	$accumCost = 0;
	$totalAccumUnits = 0;
	$totalAccumCost = 0;

	echo "<table class=\"table table-striped\">";
  echo "<thead>";
  echo "<tr>";
  echo "<th>UPC</th>";
  echo "<th>Category</th>";
  echo "<th>Unit Price</th>";
  echo "<th>Units</th>";
  echo "<th>Total Value</th>";
  echo "</tr>"; 
  echo "</thead>";
  echo "<tbody>";

 	while($row = $result->fetch_assoc()){       
	
	//If the category changes and its not the first category, add a row with the total unit and values
	if ($prevCategory != $row['category'] && $index != 0){

	//Display the row of total unit and values
		echo "<td>".""."</td>";
		echo "<td>"."Total $prevCategory Sales"."</td>";
		echo "<td>".""."</td>";
		echo "<td>".$accumUnit."</td>";
       		echo "<td>".number_format($accumCost,2,'.','')."</td><td>";
		echo "</td></tr>";

	//Reset the unit and cost variables for the new category
	$accumUnit = 0;
	$accumCost = 0;
		
	}
	
	//Display the query results
       	echo "<td>".$row['upc']."</td>";
       	echo "<td>".$row['category']."</td>";
	echo "<td>".number_format($row['unit_price'],2,'.','')."</td>";
	echo "<td>".$row['units']."</td>";
       	echo "<td>".number_format($row['total_value'], 2,'.','')."</td><td>";
       	echo "</td></tr>";
	
	//Update the variables for the next iteration
	$index++;
	$accumUnit += $row['units'];
	$accumCost += $row['total_value'];
	$totalAccumUnits += $row['units'];
	$totalAccumCost += $row['total_value'];
	$prevCategory = $row['category'];
        
    }
		
	//Display the last category's totals
	echo "<td>".""."</td>";
	echo "<td>"."Total $prevCategory Sales"."</td>";
	echo "<td>".""."</td>";
	echo "<td>".$accumUnit."</td>";
       	echo "<td>".number_format($accumCost,2,'.','')."</td><td>";
	echo "</td></tr>";
		
	//Display the total sales
	echo "<td>".""."</td>";
	echo "<td>"."Total Sales on $date"."</td>";
	echo "<td>".""."</td>";
	echo "<td>".$totalAccumUnits."</td>";
       	echo "<td>".number_format($totalAccumCost,2,'.','')."</td><td>";
	echo "</td></tr>";

    echo "</form>";
		  
   mysqli_close($connection);     
    	  }
	}
  echo "</tbody>";
  echo "</table>";
?>


<div class="col-md-3">
<form id="enterDate" name="enterDate" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label for="date_daily_sales">Enter a Date (mm/dd/yyyy)</label>
  <input type="date" class="form-control" name="date_daily_sales"><br/>
  <input type="submit" class="btn btn-success" name="submitDate" value="GENERATE">
</form>
</div>
</div>









<!-- **************************TOP SELLING ITEMS************************** -->
<div class="col-md-12">
<h2>Top Selling Items</h2>

<?php

 if ($_SERVER["REQUEST_METHOD"] == "POST") {

 //If the user presses the Generate Top Selling Items button
 if (isset($_POST["submitTopItems"]) && $_POST["submitTopItems"] ==  "GENERATE") {   
	
	//Set the user inputs as variables
	$topNumber =  $_POST["topItemNumber"];
	$topDate = $_POST["topItemDates"];
	  echo "<b>Top $topNumber Selling Items on $topDate</b>";
	
	//MySQL Query for getting the top selling items on a date
	$query = "SELECT I.title, I.company, I.stock, SUM(P.quantity) AS quantity 
             FROM item I, i_order O, purchaseItem P 
  			             WHERE O.order_date = '$topDate' && O.receiptId = P.receiptId && P.upc = I.upc
			             GROUP BY P.upc
			             ORDER by SUM(P.quantity) desc
			             LIMIT $topNumber";

		
        if (!$result = $connection->query($query)) {

              die("No results found.");
            }	

  echo "<table class=\"table table-striped\">";
  echo "<thead>";
  echo "<tr>";
  echo "<th>Title</th>";
  echo "<th>Company</th>";
  echo "<th>Stock</th>";
  echo "<th>Quantity</th>";
  echo "</tr>";
  echo "</thead>";
  echo "<tbody>";

	//Display the results of the query in the table
 	while($row = $result->fetch_assoc()){      

       		echo "<td>".$row['title']."</td>";
       		echo "<td>".$row['company']."</td>";
			echo "<td>".$row['stock']."</td>";
       		echo "<td>".$row['quantity']."</td><td>";
      		echo "</td></tr>";
	}

 	   	
	}
}
  echo "</tbody>";
  echo "</table>";
?>

<div class="col-md-3">
<form id="enterDate" name="enterDate" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="topItemDates">Enter a Date (mm/dd/yyyy)</label>
    <input type="date" name="topItemDates" class="form-control">
    <label for="topItemNumber">Enter the number of top items:</label>
        <input type="number" name="topItemNumber" class="form-control" min=0><br/>
<input type="submit" name="submitTopItems" value="GENERATE" class="btn btn-success">
</form>
</div>
</div>








<div class="col-md-12">
<h2>Orders</h2>

<?php

 if ($_SERVER["REQUEST_METHOD"] == "POST") {

if (isset($_POST["submit"]) && $_POST["submit"] ==  "PROCESS") {       
       /*
        Process delivery by updating delivery item using the post vars deliveredDate and receiptId
        */
        $receiptId = $_POST["existing_receiptId"];
        $deliveredDate = $_POST["new_deliveredDate"];

         // First check if receiptId is valid: it must be an existing order, otherwise: no db action + notify user
        $stmt = $connection->prepare("SELECT * FROM i_order WHERE receiptId = '$receiptId'");
        $stmt->execute();

	
        $results = $stmt->get_result();

        $row = $results->fetch_assoc();
        $rcpt = $row['receiptId'];

        if (! $rcpt) {
          echo "Hey, that is not an existing order. You can't update an order that doesn't exist. Try again.";
        } else {
          $stmt = $connection->prepare("UPDATE i_order SET deliveredDate = '$deliveredDate' WHERE receiptId = '$receiptId'");
          // Execute the insert statement
          $stmt->execute();

          if($stmt->error) {
            printf("<b>Error: %s.</b>\n", $stmt->error);
          } else {
            echo "<b>Successfully processed the order ".$receiptId." with delivered delivery date ".$deliveredDate."</b>";
          } 
        }
      }
    }
    /****************************************************
     STEP 3: Select orders
     ****************************************************/
   // Select all of the item rows columns upc, title, item_type, category, company, year, price, stock
    if (!$result = $connection->query("SELECT receiptId, order_date, cid, cardNumber, expiryDate, expectedDate, deliveredDate FROM i_order ORDER BY order_date")) {
        die('There was an error running the query [' . $db->error . ']');
    }
    // Avoid Cross-site scripting (XSS) by encoding PHP_SELF (this page) using htmlspecialchars.
    echo "<form id=\"delete\" name=\"delete\" action=\"";
    echo htmlspecialchars($_SERVER["PHP_SELF"]);
    echo "\" method=\"POST\">";
    // Hidden value is used if the delete link is clicked
    echo "<input type=\"hidden\" name=\"receiptId\" value=\"-1\"/>";
   // We need a submit value to detect if delete was pressed 
    echo "<input type=\"hidden\" name=\"submitDelete\" value=\"DELETE\"/>";
    /****************************************************
     STEP 4: Display the list of orders
     ****************************************************/
    // Display each item title databaserow as a table row

      echo "<table class=\"table table-striped\">";
      echo "<thead>";
      echo "<tr>";
      echo "<th>Receipt ID</th>";
      echo "<th>Order Date</th>";
      echo "<th>Customer ID</th>";
      echo "<th>Card Number</th>";
      echo "<th>Card Expiry Date</th>";
      echo "<th>Expected Delivery Date</th>";
      echo "<th>Delivered On</th>";
      echo "</tr>";
      echo "</thead>";
      echo "<tbody>";
    while($row = $result->fetch_assoc()){
        
       echo "<td>".$row['receiptId']."</td>";
       echo "<td>".$row['order_date']."</td>";
       echo "<td>".$row['cid']."</td>";
       echo "<td>".$row['cardNumber']."</td>";
       echo "<td>".$row['expiryDate']."</td>";
       echo "<td>".$row['expectedDate']."</td>";
       echo "<td>".$row['deliveredDate']."</td><td>";
       
       //Display an option to delete this order using the Javascript function and the hidden receiptId
       echo "</td></tr>";
        
    }
    echo "</form>";
    echo "</tbody>";
    echo "</table>";
    // Close the connection to the database once we're done with it.
    mysqli_close($connection);
?>
</div>













<div class="col-md-12">
<h2>Process delivery (i.e. set date of delivery)</h2>
<div class="col-md-3">
<form id="update" name="updateDate" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table border=0 cellpadding=0 cellspacing=0>
        <label for="existing_receiptId">Receipt ID</label>
        <input type="text" size=30 name="existing_receiptId" class="form-control">
        <label for="new_deliveredDate">Date Delivered</label>
        <input type="date" name="new_deliveredDate" class="form-control"><br/>
        <input type="submit" name="submit" border=0 value="PROCESS" class="btn btn-success">
    </table>
</form>
</div>
</div>


<?php
  $connection = new mysqli("localhost:3306", "root", "", "cs304");


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
/*    // OOPS I guess we didn't need this!! Keeping it here just in case :)
      } elseif (isset($_POST["submit"]) && $_POST["submit"] ==  "ADD") {       
        // Add item using the post vars upc, title, item_type, category, company, item_year, price, stock.
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
*/        
      } elseif (isset($_POST["submit"]) && $_POST["submit"] ==  "Add stock") {
       /*
		Add input quantity to existing stock using post variables upc, qty.
        */
		$existing_upc = $_POST["existing_upc"];
		$qty = $_POST["additional_stock"];
		$newPrice = $_POST["update_price"];

    // Check that this item does exist already
    $stmt = $connection->prepare("SELECT * FROM item WHERE upc = '$existing_upc'");
    $stmt->execute();
    $results = $stmt->get_result();
    $row = $results->fetch_assoc();
    $e_upc = $row['upc'];

    if (! $e_upc) {
      echo "Item with that UPC does not exist in our inventory. Try retyping, or add new item in above form.";          
    } else {

	$updatePriceQuery = "UPDATE item SET stock = stock + $qty, price = $newPrice WHERE upc = '$existing_upc'";
	$oldPriceQuery = "UPDATE item SET stock = stock + $qty  WHERE upc = '$existing_upc'";

	if ($newPrice == NULL){

      	$stmt = $connection->prepare($oldPriceQuery);
	}else{
	 $stmt = $connection->prepare($updatePriceQuery);
	}

      //Execute the update statement
      $stmt->execute();
      if($stmt->error) {
        printf("<b>Error: %s.</b>\n", $stmt->error);
      } else {
        echo "<b>Successfully updated stock for upc: ".$existing_upc."</b>";
      }
    }
  }
}
?>


<div class="col-md-12">
<h2>Manage Inventory</h2>
<?php

$connection = new mysqli("localhost:3306", "root", "", "cs304");

    // Check that the connection was successful, otherwise exit
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
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

    echo "<table class=\"table table-striped\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th>upc</th>";
    echo "<th>Title</th>";
    echo "<th>CD or DVD?</th>";
    echo "<th>Category (genre)</th>";
    echo "<th>company</th>";
    echo "<th>Year</th>";
    echo "<th>Price</th>";
    echo "<th>Stock</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
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
       echo "<td>".number_format($row['price'],2,'.','')."</td>";
       echo "<td>".$row['stock']."</td><td>";
       
       //Display an option to delete this title using the Javascript function and the hidden title_id
       echo "</td></tr>";
        
    }
    echo "</form>";

    echo "</tbody>";
    echo "</table>";
    // Close the connection to the database once we're done with it.
    mysqli_close($connection);
?>
</div>
<!--
<h2>Add a New Item</h2>


  /****************************************************
   STEP 5: Build the form to add an item
   ****************************************************/
    Use an HTML form POST to add a new item, sending the parameter values back to this page.
    Avoid Cross-site scripting (XSS) by encoding PHP_SELF using htmlspecialchars.

    This is the simplest way to POST values to a web page. More complex ways involve using
    HTML elements other than a submit button (eg. by clicking on the delete link as shown above).
-->

<!-- Guess we didn't need this!

<form id="add" name="add" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table border=0 cellpadding=0 cellspacing=0>
        <tr><td>UPC</td><td><input type="text" size=30 name="new_upc"</td></tr>
        <tr><td>Title</td><td><input type="text" size=30 name="new_title"</td></tr>
		<tr><td>Type</td><td><select name="new_item_type"><option value="CD">CD</option><option value="DVD">DVD</option></select></td>
		<tr><td>Category (genre)</td><td><input type="text" size=30 name="new_category"</td></tr>

        <tr><td>Company</td><td> <input type="text" size=30 name="new_company"></td></tr>
        <tr><td>Year</td><td> <input type="number" size=5 name="new_item_year"></td></tr>
        <tr><td>Price</td><td> <input type="value" size=5 name="new_price"></td></tr>
		<tr><td>Quantity</td><td> <input type="number" size=5 name="new_stock" min=0></td></tr>

        <tr><td></td><td><input type="submit" name="submit" border=0 value="ADD"></td></tr>
    </table>
</form>
-->

<div class="col-md-12">
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
<div class="col-md-3">
<form id="update" name="update" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="existing_upc">Item UPC</label>
        <input type="text" size=20 name="existing_upc" class="form-control">
        <label for="additional_stock">Quantity to Add</label>
        <input type="number" size=5 name="additional_stock" class="form-control">
	      <label for="update_price">Update Price (optional)</label>
        <input type="value" size=5 name="update_price" class="form-control"> <br/>
        <input type="submit" name="submit" value="Add stock" class="btn btn-success">
</form>
</div>
</div>



</div>
</div>
</body>
</html>
