    <html>
    <head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    <title>Register New Customer</title>
    <!--
        A simple stylesheet is provided so you can modify colours, fonts, etc.
    -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/Registration.css" rel="stylesheet" type="text/css">
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
                <h1>Customer Sign In</h1>
                <p class="lead">Please sign up or sign into your account.<br />
                </p>
            </div>
            <div class="col-md-6">
                <h2 class="customer_heading">I am an existing Customer</h2>
                <form role="form" id="customer_signIn" method="post">
                <div class="form-group">
                  <div class="col-md-9">
                  <label for="cid">Username</label>
                  <input type="text" id="cid" class="form-control" /><br/>
                  <label for="password">Password</label>
                  <input type="password" id="password" class="form-control" /><br/>
                  <button type="button" class="btn btn-success" onclick="location.href='customer.php'">Sign In</button>
                </form>
                </div>
                </div>
            </div>
                <div class="col-md-6">
                  <h2 class="customer_heading">Register New Customer</h2>
                  <?php

                    $connection = new mysqli("localhost", "root", "", "cs304");

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
                 die("$name, you have successfully registered! Please sign in to go to the store!");

                }   
                         
                }
                }


                ?>
            <form id="register" name="register" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <div class="col-md-9">
                    <label for="name_input">Name</label>
                    <input type="text" name="name_input" class="form-control">
                    <label for="address_input">Address</label>
                    <input type="text" name="address_input" class="form-control">
                    <label for="phone_input">Home Number</label>
                    <input type="text" name="phone_input" class="form-control">
                    <label for="id_input">ID</label>
                    <input type="text" name="id_input" class="form-control">
                    <label for="password_input">Password</label>
                    <input type="password" name="password_input" class="form-control"><br/>
                    <input type="submit" name="submit" value="REGISTER" class="btn btn-success">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</body>
</html>




