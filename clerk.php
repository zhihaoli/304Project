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
    <td class=rowheader>Return Date</td>
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

        if (!$result = $connection->query("SELECT T.retid, T.upc, title, item_type, return_date, quantity FROM item I, returnItem T, c_return R WHERE I.upc = T.upc AND R.retid = T.retid ORDER BY title;")) {
        die('There was an error running the query [SELECT T.retid, T.upc, title, item_type, return_date, quantity FROM item I, returnItem T, c_return R WHERE I.upc = T.upc AND R.retid = T.retid ORDER BY title;]');
    }

    /****************************************************
     Display the list of Returns
     ****************************************************/

    while($row = $result->fetch_assoc()){
       
       echo "<td>".$row['retid']."</td>";
       echo "<td>".$row['upc']."</td>";
       echo "<td>".$row['title']."</td>";
       echo "<td>".$row['item_type']."</td>";
       echo "<td>".$row['return_date']."</td>";
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
<!--  <form id="return" name="return" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> -->
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
             echo "<button><a href=\"javascript:formSubmit('".$row['receiptId']."', '".$row['upc']."', '".$row['order_date']."', '".$row['quantity']."');\">Return</a></button>";
             echo "</td></tr>";
              }
          
          echo "</form>";
      } elseif (isset($_POST["return"]) && $_POST["return"] == "RETURN") {
        $receiptId = $_POST['receiptId'];
        $upc = $_POST['upc'];
        echo "UPC: ".$upc;
        $order_date = $_POST['order_date'];
        $quantity = $_POST['quantity'];

        $today = date($date_format);

        $datetime1 = new Datetime($order_date);
        $datetime2 = new Datetime($today);
        $interval = $datetime1->diff($datetime2);
        $date_diff = $interval->format('%a');
        echo "INTERVAL".$interval->format('%a');

        if ($date_diff > 15) {
          echo "<script> javascript: alert(\"This purchase was made more than 15 days ago and can no longer be returned for refund.\");</script>";
        } else {
          $retid = uniqid("R_", FALSE);

          // insert new return into c_return table
          $ret_stmt = $connection->prepare("INSERT INTO c_return (retid, return_date, receiptId) VALUES (?,?,?);");
          $ret_stmt->bind_param("sss", $retid, $today, $receiptId);
          $ret_stmt->execute();

          if ($ret_stmt->error) {
            die("Error inserting new return into c_return table: ".$ret_stmt->error);
          }

          // insert item into returnItem table
          $rItem_stmt = $connection->prepare("INSERT INTO returnItem (retid, upc, quantity) VALUES (?,?,?);");
          $rItem_stmt->bind_param("sss", $retid, $upc, $quantity);
          $rItem_stmt->execute();

          if ($rItem_stmt->error) {
            die("Error inserting item into returnItem table: ".$rItem_stmt->error);
          }
          // if (!$connection->query("INSERT INTO returnItem VALUES ($retid, $upc, $quantity);")) {

          //   die("Error inserting [$upc] into returnItem table");

          // }

          echo "You have successfully returned $quantity of the item with UPC[$upc]. Your Return ID is [$retid]";

        }


        
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