<html>
    <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    <title>Clerk</title>

        <link href="bookbiz.css" rel="stylesheet" type="text/css">

    </head>

    <body>
    <h1>Returns</h1>

	<table border=0 cellpadding=0 cellspacing=0>
    <tr valign=center>
    <td class=rowheader>Return ID</td>
    <td class=rowheader>UPC</td>
    <td class=rowheader>Title</td>
    <td class=rowheader>Type</td>
    <td class=rowheader>Quantity</td>
    </tr>

   	<?php
    $connection = new mysqli("localhost:3306", "root", "", "store");
    $date_format = "m/d/Y";
        // Check that the connection was successful, otherwise exit
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

        if (!$result = $connection->query("SELECT retid, R.upc, title, item_type, quantity FROM item I, returnItem R WHERE I.upc = R.upc ORDER BY title;")) {
        die('There was an error running the query [' . $db->error . ']');
    }

    /****************************************************
     Display the list of Returns
     ****************************************************/

    while($row = $result->fetch_assoc()){
       
       echo "<td>".$row['retid']."</td>";
       echo "<td>".$row['upc']."</td>";
       echo "<td>".$row['title']."</td>";
       echo "<td>".$row['item_type']."</td>";
       echo "<td>".$row['quantity']."</td><td>";
       echo "</td></tr>";
        
    }


    ?>
</table>

<form id="search" name="search" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table border=0 cellpadding=0 cellspacing=10px>
    	<tr><td>Receipt ID </td><td><input type="text" size=30 name="rptid"</td></tr>
        <tr><td></td><td><input type="submit" name="search" border=0 value="SEARCH"></td></tr>
    </table>
</form>

    <h2>Receipt Search Results</h2>
<!-- 	<form id="return" name="return" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> -->
    <table border=0 cellpadding=0 cellspacing=0>
    <tr valign=center>
    <td class=rowheader>Receipt ID</td>
    <td class=rowheader>UPC</td>
    <td class=rowheader>Card Number</td>
    <td class=rowheader>Order Date</td>
    <td class=rowheader>Quantity</td>
    </tr>
	<?php
	    if ($_SERVER["REQUEST_METHOD"] == "POST") {
           		echo "I'M HEEERRREE";
      		if (isset($_POST["search"]) && $_POST["search"] == "SEARCH") {
  				$rptid = $_POST['rptid'];
  				echo "Where am I?";
  				echo $rptid;
  			    if(!$result = $connection->query("SELECT O.receiptId, upc, cardNumber, order_date, quantity FROM i_order O, purchaseItem P WHERE O.receiptId=P.receiptId AND O.receiptId='$rptid';")) {
  			    	echo "Error in searching in Orders with receipt ID [".$rptid."]";
  			    }

				echo "HELLOO";
				echo "<form id=\"return\" name=\"return\" action=\"";
			    echo htmlspecialchars($_SERVER["PHP_SELF"]);
			    echo "\" method=\"POST\">";
			    // Hidden value is used if the delete link is clicked
			    echo "<input type=\"hidden\" name=\"receiptId\" value=\"-1\"/>";
			    echo "<input type=\"hidden\" name=\"order_date\" value=\"-1\"/>";
          echo "<input type=\"hidden\" name=\"upc\" value=\"-1\"/>";
          echo "<input type=\"hidden\" name=\"quantity\" value=\"-1\"/>";
			   // We need a submit value to detect if delete was pressed 
			    echo "<input type=\"hidden\" name=\"return\" value=\"RETURN\"/>";

			    while($row = $result->fetch_assoc()){
			       
			       echo "<td>".$row['receiptId']."</td>";
             echo "<td>".$row['upc']."</td>";
			       echo "<td>".$row['cardNumber']."</td>";
			       echo "<td>".$row['order_date']."</td>";
             echo "<td>".$row['quantity']."</td><td>";
			       
			       //Display an option to delete this title using the Javascript function and the hidden title_id
			       echo "<button href=\"javascript:formSubmit('".$row['receiptId']."', '".$row['upc']."', '".$row['order_date']."', '".$row['quantity']."');\">Return</button>";
			       echo "</td></tr>";
			        }
			    
			    echo "</form>";
			} elseif (isset($_POST["return"]) && $_POST["return"] == "RETURN") {
				$order_date = $_POST['order_date'];
				
			}
	}

           	

    mysqli_close($connection);
    				
		?>
</table>
<!-- </form> -->
<!-- </div> -->
<script>
function formSubmit(receiptId, upc, order_date, quantity) {
    'use strict';
    var form = document.getElementById('return');
    form.receiptId.value = receiptId;
    form.order_date.value = order_date;
    form.upc.value = upc;
    form.quantity.value = quantity;
    form.submit();
}

</script>

    </body>
</html>