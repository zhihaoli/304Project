<html>
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">

<title>Manager processes delivery</title>
<!--
    A simple stylesheet is provided so you can modify colours, fonts, etc.
-->
    <link href="bookbiz.css" rel="stylesheet" type="text/css">

<!--
    Javascript to submit a upc as a POST form, used with the "delete" links
-->
<script>
function formSubmit(receiptId) {
    'use strict';
    if (confirm('Are you sure you want to delete this item?')) {
      // Set the value of a hidden HTML element in this form
      var form = document.getElementById('delete');
      form.receiptId.value = receiptId;
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
     STEP 1: Connect to my local db
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
     2) by clicking the Delete link beside an item's row, or
     3) by clicking the bottom "Update delivery" button to process delivery record
     
     NOTE We are using POST superglobal to safely pass parameters
        (as opposed to URL parameters or GET)
     ****************************************************/
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST["submitDelete"]) && $_POST["submitDelete"] == "DELETE") {
       /*
          Delete the selected book title using the upc
        */
       
       // Create a delete query prepared statement with a ? for the upc
       $stmt = $connection->prepare("DELETE FROM item WHERE receiptId=?");
       $deleteReceiptId = $_POST['receiptId'];
       // Bind the upc parameter, 's' indicates a string value
       $stmt->bind_param("s", $deleteReceiptId);
       
       // Execute the delete statement
       $stmt->execute();
          
       if($stmt->error) {
         printf("<b>Error: %s.</b>\n", $stmt->error);
       } else {
         echo "<b>Successfully deleted ".$deleteReceiptId."</b>";
       }
            
      } elseif (isset($_POST["submit"]) && $_POST["submit"] ==  "PROCESS") {       
       /*
        Process delivery by updating delivery item using the post vars expectedDate and receiptId
        */
        $receiptId = $_POST["existing_receiptId"];
        $expectedDate = $_POST["new_expectedDate"];

        $stmt = $connection->prepare("UPDATE i_order SET expectedDate = '$expectedDate' WHERE receiptId = '$receiptId'");

        // Execute the insert statement
        $stmt->execute();
          
        if($stmt->error) {       
          printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
          echo "<b>Successfully processed the order ".$receiptId." with expected delivery date ".$expectedDate."</b>";
        }
      }
   }
?>

<h2>Orders</h2>
<!-- Set up a table to view the orders -->
<table border=0 cellpadding=0 cellspacing=0>
<!-- Create the table column headings -->

<tr valign=center>
<td class=rowheader>Receipt ID</td>
<td class=rowheader>Order Date</td>
<td class=rowheader>Customer ID</td>
<td class=rowheader>Card Number</td>
<td class=rowheader>Card Expiry Date</td>
<td class=rowheader>Expected Delivery Date</td>
<td class=rowheader>Delivered On</td>

</tr>

<?php
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
    while($row = $result->fetch_assoc()){
        
       echo "<td>".$row['receiptId']."</td>";
       echo "<td>".$row['order_date']."</td>";
       echo "<td>".$row['cid']."</td>";
       echo "<td>".$row['cardNumber']."</td>";
       echo "<td>".$row['expiryDate']."</td>";
       echo "<td>".$row['expectedDate']."</td>";
       echo "<td>".$row['deliveredDate']."</td><td>";
       
       //Display an option to delete this order using the Javascript function and the hidden receiptId
       echo "<a href=\"javascript:formSubmit('".$row['receiptId']."');\">DELETE</a>";
       echo "</td></tr>";
        
    }
    echo "</form>";
    // Close the connection to the database once we're done with it.
    mysqli_close($connection);
?>

</table>


<h2>Process delivery (i.e. update expected date of delivery)</h2>

<!--
  /****************************************************
   STEP 5: Build the form to update stock of an existing item
   ****************************************************/
    Use an HTML form POST to update the stock of an item, sending the parameter values back to this page.
    Avoid Cross-site scripting (XSS) by encoding PHP_SELF using htmlspecialchars.

    This is the simplest way to POST values to a web page. More complex ways involve using
    HTML elements other than a submit button (eg. by clicking on the delete link as shown above).
-->

<form id="update" name="updateDate" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table border=0 cellpadding=0 cellspacing=0>
        <tr><td>Receipt ID</td><td><input type="text" size=30 name="existing_receiptId"</td></tr>
        <tr><td>Expected Delivery Date</td><td> <input type="date" name="new_expectedDate"></td></tr>
        <tr><td></td><td><input type="submit" name="submit" border=0 value="PROCESS"></td></tr>
    </table>
</form>



</body>
</html>