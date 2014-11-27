    <html>
    <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    <title>Register New Customer</title>
    <!--
        A simple stylesheet is provided so you can modify colours, fonts, etc.
    -->
        <link href="bookbiz.css" rel="stylesheet" type="text/css">

    <!--
        Javascript to submit a title_id as a POST form, used with the "delete" links
    -->
    <script>
    function formSubmitSearch(upc, title, stock, qty) {
        'use strict';
          // Set the value of a hidden HTML element in this form
          var form = document.getElementById('search');
          form.upc.value = upc;
          form.item_title.value = title;
          form.stock.value = stock;
          if (qty != null) {
            form.quantity.value = qty;
          }
          // Post this form
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
    </script>
    </head>

    <body>
    <h1>Are you a new Customer?</h1>

    <h2>I am an existing Customer</h2>
    <button type="button" class="btn" onclick="location.href='customer.php'"> Go to the Store</button>

     <h2>Register New Customer</h2>




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

  if (isset($_POST["submit"]) && $_POST["submit"] ==  "REGISTER") {

	

           $stmt = $connection->prepare("INSERT INTO customer (cid, password, name, address, phone) VALUES (?,?,?,?,?)");
           $cid = $_POST['id_input'];
           $password = $_POST['password_input'];
           $name = $_POST['name_input'];
           $address = $_POST['address_input'];
	   $phone = $_POST['phone_input'];

       
           // Bind the title_id parameter, 's' indicates a string value
           $stmt->bind_param("sssss", $cid, $password, $name, $address, $phone);
           
           $stmt->execute();
           $err = $stmt->error;

           if($stmt->error) {
              printf("Uh oh! That ID was already chosen, please pick a different one!");
              }else{
		 die("$name, you have successfully registered! Click the button above to go to the store!");

		}   
             
}
}


?>


    <form id="register" name="register" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table border=0 cellpadding=0 cellspacing=0>
      <tr><td>Name</td><td><input type="text" size=30 name="name_input"</td></tr>
            <tr><td>Address</td><td><input type="text" size=30 name="address_input"</td></tr>
            <tr><td>Phone Number</td><td> <input type="text" size=30 name="phone_input"></td></tr>
<tr><td>ID</td><td> <input type="text" size=30 name="id_input"></td></tr>
<tr><td>Password</td><td> <input type="text" size=30 name="password_input"></td></tr>
            <tr><td></td><td><input type="submit" name="submit" border=0 value="REGISTER"></td></tr>
        </table>
    </form>

    </body>
    </html>
