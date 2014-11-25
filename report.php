<html>
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">

<title>Sales Report Generator</title>
<!--
   This is Bruce's Code. It contains the PHP/mySQL Queries for Daily Sales Report and Top Selling Items
-->
    <link href="bookbiz.css" rel="stylesheet" type="text/css">

<h1>Daily Sales Report</h1>

<table border=0 cellpadding=10 cellspacing=5>

<tr valign=center>
<td class=rowheader>UPC</td>
<td class=rowheader>Category</td>
<td class=rowheader>Unit Price</td>
<td class=rowheader>Units</td>
<td class=rowheader>Total Value</td>
</tr>


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

	
 	while($row = $result->fetch_assoc()){       
	
	//If the category changes and its not the first category, add a row with the total unit and values
	if ($prevCategory != $row['category'] && $index != 0){

	//Display the row of total unit and values
		echo "<td>".""."</td>";
		echo "<td>"."Total $prevCategory Sales"."</td>";
		echo "<td>".""."</td>";
		echo "<td>".$accumUnit."</td>";
       		echo "<td>".$accumCost."</td><td>";
		echo "</td></tr>";

	//Reset the unit and cost variables for the new category
	$accumUnit = 0;
	$accumCost = 0;
		
	}
	
	//Display the query results
       	echo "<td>".$row['upc']."</td>";
       	echo "<td>".$row['category']."</td>";
	echo "<td>".$row['unit_price']."</td>";
	echo "<td>".$row['units']."</td>";
       	echo "<td>".$row['total_value']."</td><td>";
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
	echo "<td>"."Total"."</td>";
	echo "<td>".""."</td>";
	echo "<td>".$accumUnit."</td>";
       	echo "<td>".$accumCost."</td><td>";
	echo "</td></tr>";
		
	//Display the total sales
	echo "<td>".""."</td>";
	echo "<td>"."Total Daily Sales"."</td>";
	echo "<td>".""."</td>";
	echo "<td>".$totalAccumUnits."</td>";
       	echo "<td>".$totalAccumCost."</td><td>";
	echo "</td></tr>";

    echo "</form>";
		  
   mysqli_close($connection);     
    	  }
	}
?>
</table>


<form id="enterDate" name="enterDate" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table border=0 cellpadding=0 cellspacing=0>
  <tr><td>Enter a Date (mm/dd/yyyy)</td><td><input type="text" size=30 name="date_daily_sales"</td></tr>
<tr><td></td><td><input type="submit" name="submitDate" border=0 value="GENERATE"></td></tr>
    </table>
</form>


<h1>Top Selling Items</h1>
<!-- Set up a table to view the book titles -->
<table border=0 cellpadding=10 cellspacing=5>
<!-- Create the table column headings -->

<tr valign=center>
<td class=rowheader>Title</td>
<td class=rowheader>Company</td>
<td class=rowheader>Stock</td>
<td class=rowheader>Quantity</td>
</tr>

<?php

 if ($_SERVER["REQUEST_METHOD"] == "POST") {

 //If the user presses the Generate Top Selling Items button
 if (isset($_POST["submitTopItems"]) && $_POST["submitTopItems"] ==  "GENERATE") {   
	
	//Set the user inputs as variables
	$topNumber =  $_POST["topItemNumber"];
	$topDate = $_POST["topItemDates"];
	
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

?>
</table>

<form id="enterDate" name="enterDate" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table border=0 cellpadding=0 cellspacing=0>
        <tr><td>Enter a Date (mm/dd/yyyy): </td><td><input type="text" size=30 name="topItemDates"</td></tr>
        <tr><td>Enter the number of top items: </td><td> <input type="text" size=20 name="topItemNumber"></td></tr>
<tr><td></td><td><input type="submit" name="submitTopItems" border=0 value="GENERATE"></td></tr>
    </table>
</form>


</body>
</html>
