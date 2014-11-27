    <html>
    <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    <title>Customer</title>
    <!--
        A simple stylesheet is provided so you can modify colours, fonts, etc.
    -->
        
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/registration.css" rel="stylesheet" type="text/css">

    <!--
        Javascript to submit a title_id as a POST form, used with the "delete" links
    -->
    <script>
    function formSubmitSearch(upc, title, stock, qty) {
        'use strict';
          // Set the value of a hidden HTML element in this form
          var form = document.getElementById('searchHidden');
          form.upc.value = upc;
          form.item_title.value = title;
          form.stock.value = stock;
          if (qty >0) {
            form.submitCart.value  = "ADD TO SHOPPING CART";
            form.quantity.value = qty;
          }
          form.submit();
        
    }

    function formSubmitDelete(upc, title) {
      'use strict';
      if (confirm('Are you sure you want to remove the item "' + title + '"" from your cart?')) {
        // Set the value of a hidden HTML element in this form
        var form = document.getElementById('cart');
        form.upc.value = upc;
        form.title.value = title;
        // Post this form
        form.submit();
      }
    }

    function confirmMsg(upc, stock, title, cart_qty, remain_qty ) {

      var bool = confirm('There are ' + stock + ' of the item ' + title + ' in stock, and you have already added ' + 
        cart_qty + ' to your cart. You can only add ' + remain_qty + 
        ' more of this item to your cart. Would you like to change your quantity to ' + remain_qty + '?');
      
      if (bool) {
          var form = document.getElementById('newval');
          form.quantity.value = remain_qty;
          form.item_title.value = title;
          form.upc.value = upc;
          form.stock.value = stock;
          form.submit();
        }
      
    }
    </script>
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
                  <li class="active"><a href="registration.php">Customer</a></li>
                  <li><a href="report.php">Manager</a></li>
                  <li><a href="Godmode.html">TA Godmode</a></li>
              </ul>
          </div>
      </div>
  </nav>
    <div class="container">
        <div class="panel panel-body">
            <div class="starter-template">
                <h1>Shop and Search</h1>
                <p class="lead">IT'S SHOPPING TIME!<br />
                </p>
            </div>
    <form class="form-inline" id="search" name="search" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <label for="title_input">Title&nbsp;</label>
      <input type="text" name="title_input" class="form-control">&nbsp;
            <label for="category_input">Category</label>
            <select name="category_input" class="form-control">&nbsp;
            <option value="" selected="selected"></option>
              <option value="Classical">Classical</option>
              <option value="Country">Country</option>
              <option value="Instrumental">Instrumental</option>
              <option value="New Age">New Age</option>
              <option value="Pop">Pop</option>
              <option value="Rap">Rap</option>
              <option value="Rock">Rock</option></select>
        <label for="leadSinger_input">Lead Singer&nbsp;</label>
        <input type="text" name="leadSinger_input" class="form-control">&nbsp;
        <input type="submit" name="submitSearch" value="SEARCH" class="btn btn-success">
    </form>

    <?php
        /****************************************************
         STEP 1: Connect to the bookbiz MySQL database
         ****************************************************/

        // CHANGE this to connect to your own MySQL instance in the labs or on your own computer
        $connection = new mysqli("localhost:3306", "root", "", "cs304");

        // Check that the connection was successful, otherwise exit
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

          if (isset($_POST["submitCart"]) && ($_POST["submitCart"] == "ADD TO SHOPPING CART" || $_POST["submitCart"] == "NEW QUANTITY" )) {
           /*
              Delete the selected book title using the title_id
            */
           
           // Create a delete query prepared statement with a ? for the title_id
           $stmt = $connection->prepare("INSERT INTO cart (upc, title, quantity) VALUES (?,?,?)");
           $upc = $_POST['upc'];
           $title = $_POST['item_title'];
           $qty = $_POST['quantity'];
           $a_stock = $_POST['stock'];

           // Bind the title_id parameter, 's' indicates a string value
           $stmt->bind_param("ssi", $upc, $title, $qty);
           
           // Execute the delete statement
           $stmt->execute();
              
           if($stmt->error) {

              $get_qty="SELECT quantity FROM cart WHERE upc='$upc';";
              if (! $result = $connection->query($get_qty)) {
                die("Error in fetching cart item quantity.");
              }   
              $row = $result->fetch_assoc();
              $cart_qty = $row['quantity'];

              
              
              if ($qty + $cart_qty <= $a_stock){
              $stmt = $connection->prepare("UPDATE cart SET quantity=? WHERE upc=$upc;");
              $new_qty = $qty + $cart_qty;
              $stmt->bind_param("i", $new_qty);
              $stmt->execute();
            } else {
              $remaining_qty = $a_stock - $cart_qty;
              
              echo "<form id=\"newval\" name=\"newval\" style = \"display:none\" action=\"";
              echo htmlspecialchars($_SERVER["PHP_SELF"]);
              echo "\" method=\"POST\">";
              echo "<input type=\"hidden\" name=\"upc\" value=\"-1\"/>";
              echo "<input type=\"hidden\" name=\"item_title\" value=\"-1\"/>";
              echo "<input type=\"hidden\" name=\"quantity\" value=\"-1\"/>";
              echo "<input type=\"hidden\" name=\"stock\" value=\"-1\"/>";
              echo "<input type=\"hidden\" name=\"submitCart\" value=\"NEW QUANTITY\"/>";

              echo "<a href=\"javascript: confirmMsg('".$upc."', '".$a_stock."', '".$title."', '".$cart_qty."', '".$remaining_qty."');\">ERROR: Click here for more details.</a>";
              echo "</form>";
            }
           } else {
             echo "<b>Successfully added ".$qty. " of ".$title." to your cart</b>";
           }
                
          } elseif (isset($_POST["submitDelete"]) && $_POST["submitDelete"] == "DELETE") {
       /*
          Delete the selected book title using the title_id
        */
       
       // Create a delete query prepared statement with a ? for the title_id
       $stmt = $connection->prepare("DELETE FROM cart WHERE upc=?");
       $deleteUpc= $_POST['upc'];
       $deleteTitle= $_POST['title'];
       // Bind the title_id parameter, 's' indicates a string value
       $stmt->bind_param("s", $deleteUpc);
       
       // Execute the delete statement
       $stmt->execute();
          
       if($stmt->error) {
         printf("<b>Error: %s.</b>\n", $stmt->error);
       } else {
         echo "<b>Successfully deleted ".$deleteTitle."</b>";
       }
            
      } elseif (isset($_POST["submitSearch"]) && $_POST["submitSearch"] == "SEARCH"){

            $query = "SELECT I.upc, title, L.name, item_type, category, company, item_year, stock, price FROM item I, leadSinger L WHERE I.upc=L.upc AND (";

            $category = $_POST['category_input'];
            $title = $_POST['title_input'];
            $leadSinger = $_POST['leadSinger_input'];
            $n = 0;
            if($category != '') {
             $query .= "I.category LIKE '%$category%'";
             $n++;
            }
          
            if($title != '') {
              if ($n > 0) { 
                $query .= " OR ";
              }
             $query .= "I.title LIKE '%$title%'";
             $n++;
            }

            if($leadSinger != '') {
              if ($n > 0) { 
                $query .= " OR ";
              }
             $query .= "L.name LIKE '%$leadSinger%'";
            }

            $query .= ");"; 

            if (! $result = $connection->query($query)) {
              die("No results found.");
            }

    echo "<form id=\"searchHidden\" name=\"search\" action=\"";
    echo htmlspecialchars($_SERVER["PHP_SELF"]);
    echo "\" method=\"POST\">";
    echo "<input type=\"hidden\" name=\"upc\" value=\"-1\"/>";
    echo "<input type=\"hidden\" name=\"item_title\" value=\"-1\"/>";
    echo "<input type=\"hidden\" name=\"qty\" value=\"-1\"/>";
    echo "<input type=\"hidden\" name=\"stock\" value=\"-1\"/>";
    echo "<input type=\"hidden\" name=\"submitCart\" value=\"ADD TO SHOPPING CART\"/>";

    echo "<h2>Search Results</h2>";
    echo "<table class=\"table table-striped\">";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Title</th>";
    echo "<th>Lead Singer</th>";
    echo "<th>Type</th>";
    echo "<th>Category</th>";
    echo "<th>Company</th>";
    echo "<th>Year</th>";
    echo "<th>Available Stock</th>";
    echo "<th>Price</th>";
    echo "<th>Quantity</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

            while($row = $result->fetch_assoc()){
              
              echo "<td>".$row['title']."</td>";
              echo "<td>".$row['name']."</td>";
              echo "<td>".$row['item_type']."</td>";
              echo "<td>".$row['category']."</td>";
              echo "<td>".$row['company']."</td>";
              echo "<td>".$row['item_year']."</td>";
              echo "<td>".$row['stock']."</td>";
              echo "<td>".$row['price']."</td>";
              echo "<td><select type=\"number\" size=1 name=\"quantity\"><option selected=\"selected\" value=\"1\">1</option>";
              $stock= $row['stock'];
              $i = 2;
              while($i <= $stock){
                echo "<option value=\"".$i."\">".$i."</option>";
                $i++;
              }
              echo "</select></td><td>";
              echo "<button name=\"add to cart\" class=\"btn btn-default\"><a href=\"javascript:formSubmitSearch('".$row['upc']."', '".$row['title']."', '".$row['stock']."', 0);\">ADD TO SHOPPING CART</a></button>";
              echo "</td></tr>";
            }  
        }
            echo "</form>";

    // Close the connection to the database once we're done with it.
    echo "</tbody>";
    echo "</table>";
       }
    ?>

    <h2>Your Shopping Cart</h2>

    <?php


   if (!$result = $connection->query("SELECT I.upc, I.title, item_type, price, quantity FROM item I, cart C WHERE I.upc = C.upc ORDER BY title;")) {
        echo "Your shopping cart is empty..or there's an error? I dunno...you figure it out.";
    }
     echo "<form id=\"cart\" name=\"cart\" action=\"";
    echo htmlspecialchars($_SERVER["PHP_SELF"]);
    echo "\" method=\"POST\">";
    // Hidden value is used if the delete link is clicked
    echo "<input type=\"hidden\" name=\"upc\" value=\"-1\"/>";
    echo "<input type=\"hidden\" name=\"title\" value=\"-1\"/>";
   // We need a submit value to detect if delete was pressed 
    echo "<input type=\"hidden\" name=\"submitDelete\" value=\"DELETE\"/>";

    echo "<table class=\"table table-striped\">";
    echo "<thead>";
    echo "<tr>";
    echo "<td>Title</th>";
    echo "<td>Type</th>";
    echo "<td>Price</th>";
    echo "<td>Quantity</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while($row = $result->fetch_assoc()){
        
       echo "<td>".$row['title']."</td>";
       echo "<td>".$row['item_type']."</td>";
       echo "<td>".$row['price']."</td>";
       echo "<td>".$row['quantity']."</td><td>";
       
       //Display an option to delete this title using the Javascript function and the hidden title_id
       echo "<button><a href=\"javascript:formSubmitDelete('".$row['upc']."', '".$row['title']."');\">DELETE</a></button>";
       echo "</td></tr>";
        
    }
    echo "</form>";
       mysqli_close($connection);

    echo "</tbody>";
    echo "</table>";
   ?>

  
  <table border=0 cellpadding=0 cellspacing=0>
    
        <button type="button" class="btn btn-default" onclick="location.href='checkout.php'"> Check Out</button>


    
  </table>
    </body>
    </html>
