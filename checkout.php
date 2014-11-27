<html>
    <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    <title>Check-Out</title>

   <link href="css/bootstrap.min.css" rel="stylesheet">
   <link href="css/registration.css" rel="stylesheet" type="text/css">

    </head>

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
<body>
<div class="container">
        <div class="panel panel-body">
            <div class="starter-template">
                <h1>Checkout</h1>
                <p class="lead">Thanks for shopping with us!<br />
                </p>
            </div>
	<h2>Order</h2>

    <?php
    $connection = new mysqli("localhost", "root", "", "cs304");


    $max_oneday = 10;
    $date_format = "Y-m-d";
        // Check that the connection was successful, otherwise exit
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

      	if (isset($_POST["purchase"]) && $_POST["purchase"] == "PURCHASE") {
      		// retrieve customer input
      		$card = $_POST["cardNumber"];
      		$expiry = $_POST["expiryDate"];
      		$cid = $_POST["cid"];

      		if(!$cid_check = $connection->query("SELECT count(*) as count from customer where cid = '$cid';")) {
      			echo "<script> javascript: alert(\"Your Customer ID is invalid, please check your ID (1)\");</script>";
      		}
		      $row = $cid_check->fetch_assoc();
      		if($row['count'] == 0) {
      			echo "<script> javascript: alert(\"Your Customer ID is invalid, please check your ID (2)\");</script>";
      		} else {

	      		// generate unique receiptId (set to FALSE so it generates 13 characters after the prefix)
	      		$rid = uniqid('P_', FALSE);

	      		// retrieve number of orders not yet delivered
	      		if(!$outstand_q = $connection->query("SELECT count(*) as count from i_order where deliveredDate = null;")) {
	      			echo "ERROR in retrieving count of outstanding orders";
	      		}
			$outstand_q_row = $outstand_q->fetch_assoc();
	      		$outstand_num = $outstand_q_row['count'];
	      		// calculating the delivery days using max number of one day delivery and the number of outstanding orders
	      		$deliver_days = floor($outstand_num/$max_oneday) + 1;
	      		// todays date
	      		$order_date = date($date_format);
	      		// adding the delivery days to todays date to get expected delivery date
	      		$exptd_date = date($date_format,strtotime("+$deliver_days day", strtotime($order_date)));

	      		// insert into order
	      		$ord_stmt = $connection->prepare("INSERT INTO i_order (receiptId, order_date, cid, cardNumber, expiryDate, expectedDate, deliveredDate) VALUES (?,?,?,?,?,?,?);");

	      		$null=null;
	      		$ord_stmt->bind_param("sssssss", $rid, $order_date, $cid, $card, $expiry, $exptd_date, $null);
	      		$ord_stmt->execute();

	      		if($ord_stmt->error) {       
	          		printf("<b>Error: %s.</b>\n", $ord_stmt->error);
	        	} else {
	         		echo "<b>Your purchase has been received. Your receipt ID is \"$rid\" and your expected delivery date is $exptd_date</b>";
	        	}

	        	if (!$cart = $connection->query("SELECT upc, quantity FROM cart;")) {
					echo "ERROR in retrieving cart items to update and add to purchaseItem";
	        	}

	        	while($item= $cart->fetch_assoc()){
	        		$item_upc = $item['upc'];
	        		$item_qty = $item['quantity'];

	        		// add item from cart to purchaseItem
	        		$tee = $connection->prepare("INSERT INTO purchaseItem (receiptId, upc, quantity) VALUES (?,?,?);");
	        		$tee->bind_param("ssi", $rid, $item_upc, $item_qty);
	        		$tee->execute();

	        		if($tee->error) {
	        			printf("<b>Error inserting item into purchaseItem table: %s </b>\n", $tee->error);
	        		}

	        		// update item stock in items table
	        		if(!$connection->query("UPDATE item SET stock = stock - $item_qty WHERE upc = '$item_upc';")) {
						echo "Error updating item '$item_upc' stock in item table";
	        		}

	        		// delete item from cart
	        		if(!$connection->query("DELETE FROM cart WHERE upc = '$item_upc';")){
	        			echo "Error delete item '$item_upc' from cart after purchase";
	        		}
	        	}

	     	}
	     }

  	}
    if (!$result = $connection->query("SELECT I.upc, I.title, item_type, quantity*price as price, quantity FROM item I, cart C WHERE I.upc = C.upc ORDER BY title;")){
       	die('There was an error running the query [' . $db->error . ']');
    }

    echo "<table class=\"table table-striped\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Title</th>";
    echo "<th>Type</th>";
    echo "<th>Quantity</th>";
    echo "<th>Price</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    /****************************************************
     Display the bill
     ****************************************************/
    while($row = $result->fetch_assoc()){
        
       	echo "<td>".$row['title']."</td>";
       	echo "<td>".$row['item_type']."</td>";
       	echo "<td>".$row['quantity']."</td>";
       	echo "<td>$".$row['price']."</td><td>";
       
       //Display an option to delete this title using the Javascript function and the hidden title_id
       	echo "</td></tr>";
        
    }

    if(!$amt = $connection->query("SELECT SUM(quantity*price) as total FROM item I, cart C WHERE I.upc = C.upc;")) {
      	echo "ERROR in retrieving total amount";
    }

    $subtotal = $amt->fetch_assoc()['total'];
    $tax = 0.10 * $subtotal;
    $total = $subtotal + $tax;

    echo "</form>";
    echo "</tbody>";
    echo "</table>";
    echo "<label> Subtotal: $".$subtotal."</label><br/>";
    echo "<label> Tax(10%): $".$tax."</label><br/>";
    echo "<label> Total: $".$total."</label><br/>";
   	mysqli_close($connection);
    ?>
<br>


<div class="col-md-3">
<form id="paymentform" name="paymentform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return validateForm()">
    	<label for="cid">Customer ID</label>
      <input type="text" size=30 name="cid" class="form-control">
      <label for="cardNumber">Credit Card #</label>
      <input type="text" size=30 name="cardNumber" class="form-control">
      <label for="expiryDate">Expiry Date</label>
      <input type="text" size=5 name="expiryDate" class="form-control"><br/>
      <input type="submit" name="purchase" value="PURCHASE"  class="btn btn-success">
</form>
</div>
</div>
</div>

  <script>
function formSubmit(upc, title) {
    'use strict';
    if (confirm('Are you sure you want to remove this item?')) {
      // Set the value of a hidden HTML element in this form
      var form = document.getElementById('order');
      form.upc.value = upc;
      form.title.value = title;
      // Post this form
      form.submit();
    }
}


function validateForm() {
	var form = document.getElementById('paymentform');
	var card = form.cardNumber.value;
	var expiry = form.expiryDate.value;
	var cid = form.cid.value;

	if (cid== null || cid =="") {
		alert("Your Customer ID is required to complete the transaction.");
		return false;
	}

	if (card == null || card =="") {
		alert("Your credit card number is required to complete the transaction.");
		return false;
	}

	if (expiry == null || expiry == "") {
		alert("The credit card expiry date is required to complete the transaction.");
		return false;
	}
}
</script>
</body>
</html>