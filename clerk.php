<html>
    <head>
      <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
      <meta content="utf-8" http-equiv="encoding">

      <title>Clerk</title>

      <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
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
                <h1>Clerk</h1>
                <p class="lead">You go girl, you process those returns!<br />
                </p>
            </div>


  <div class="col-md-12">
    <h1>Returns</h1>
    <?php
    $connection = new mysqli("localhost", "root", "", "cs304");
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
        echo "<table class=\"table table-striped\">";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Return ID</th>";
        echo "<th>UPC</th>";
        echo "<th>Title</th>";
        echo "<th>Type</th>";
        echo "<th>Return Date</th>";
        echo "<th>Quantity</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

    while($row = $result->fetch_assoc()){
       
       echo "<td>".$row['retid']."</td>";
       echo "<td>".$row['upc']."</td>";
       echo "<td>".$row['title']."</td>";
       echo "<td>".$row['item_type']."</td>";
       echo "<td>".$row['return_date']."</td>";
       echo "<td>".$row['quantity']."</td><td>";
       echo "</td></tr>";
        
    }
    echo "</tbody>";
    echo "</table>";
    ?>
<div class="col-md-3">
<form id="search" name="search" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <label for="rptid">Receipt ID</label>
      <input type="text" name="rptid" class="form-control"><br/>
      <input type="submit" name="search" border=0 value="SEARCH" class="btn btn-success">
</form>
</div>
</div>

<div class="col-md-12">
    <h2>Receipt Search Results</h2>
<!--  <form id="return" name="return" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> -->
    <table class="table table-striped">
    <thead>
    <tr>
    <th>Receipt ID</th>
    <th>UPC</th>
    <th>Card Number</th>
    <th>Order Date</th>
    <th>Quantity</th>
    </tr>
    </thead>
  <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST["search"]) && $_POST["search"] == "SEARCH") {
          
          $rptid = $_POST['rptid'];

          if(!$result = $connection->query("SELECT O.receiptId, upc, cardNumber, order_date, quantity FROM i_order O, purchaseItem P WHERE O.receiptId=P.receiptId AND O.receiptId='$rptid';")) {
            echo "Error in searching in Orders with receipt ID [".$rptid."]";
          }

        
          echo "<form id=\"return\" name=\"return\" action=\"";
          echo htmlspecialchars($_SERVER["PHP_SELF"]);
          echo "\" method=\"POST\">";
          echo "<input type=\"hidden\" name=\"receiptId\" value=\"-1\"/>";
          echo "<input type=\"hidden\" name=\"order_date\" value=\"-1\"/>";
          echo "<input type=\"hidden\" name=\"upc\" value=\"-1\"/>";
          echo "<input type=\"hidden\" name=\"quantity\" value=\"-1\"/>";
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
            $order_date = $_POST['order_date'];
            $quantity = $_POST['quantity'];

            $today = date($date_format);

            $datetime1 = new Datetime($order_date);
            $datetime2 = new Datetime($today);
            $interval = $datetime1->diff($datetime2);
            $date_diff = $interval->format('%a');

            if(!$tr = $connection->query("SELECT quantity FROM purchaseItem WHERE upc = '$upc';")){
                echo "Error occured looking up quantity of purchaseItem";
            } 
		
		$tr_row = $tr->fetch_assoc();
            if ($tr_row['quantity']==0){

                echo "<script> javascript: alert(\"This item of the Purchase has already been previously returned.\");</script>";
              
            } elseif ($date_diff > 15) {
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

              if(!$connection->query("UPDATE purchaseItem SET quantity = 0 WHERE upc = $upc")){
                echo "Error occured updating purchaseItem to set quantity of $upc to 0";
              }

              echo "You have successfully returned $quantity of the item with UPC[$upc]. Your Return ID is [$retid]";

        }


        
      }
  }

    mysqli_close($connection);
            
    ?>
</table>
</div>

</div>
</div>
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
