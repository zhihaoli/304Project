<?php
    $server = "localhost:3306";
    $user = "root";
    $password = "";
    $database = "store";

    $connection = new mysqli($server, $user, $password, $database);

    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
    }

    //TODO: Form Validation

    switch($_POST['id']){

      case 'item_select':

        if (!$result = $connection->query("SELECT * FROM item")) {
            die('There was an error running the query [' . $database->error . ']');
            }
        else{
          while($row = $result->fetch_assoc()){
             echo "<tr><td>".$row['upc']."</td>";
             echo "<td>".$row['title']."</td>";
             echo "<td>".$row['item_type']."</td>";
             echo "<td>".$row['category']."</td>";
             echo "<td>".$row['company']."</td>";
             echo "<td>".$row['item_year']."</td>";
             echo "<td>".$row['price']."</td>";
             echo "<td>".$row['stock']."</td></tr>";
          }
        }
        break;
      

      case 'item_insert':

        $stmt = $connection->prepare("INSERT INTO item (upc, title, item_type, category, company, item_year, price, stock) VALUES (?,?,?,?,?,?,?,?)");

        $new_upc = $_POST["new_item_upc"];
        $new_title = $_POST["new_item_title"];
        $new_item_type = $_POST["new_item_type"];
        $new_category = $_POST["new_item_category"];
        $new_company = $_POST["new_item_company"];
        $new_year = $_POST["new_item_year"];
        $new_price = $_POST["new_item_price"];
        $new_stock = $_POST["new_item_stock"];

        $stmt->bind_param("sssssidi", $new_upc, $new_title, $new_item_type, $new_category, $new_company, $new_year, $new_price, $new_stock);

        $stmt->execute();

        if($stmt->error) {       
          printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
          echo "Successfully inserted the following data:<br/>";
          echo "upc: ".$new_upc."<br/>";
          echo "title: ".$new_title."<br/>";
          echo "item_type: ".$new_item_type."<br/>";
          echo "category: ".$new_category."<br/>";
          echo "company: ".$new_company."<br/>";
          echo "year: ".$new_year."<br/>";
          echo "price: ".$new_price."<br/>";
          echo "stock: ".$new_stock."<br/>";
        }
        break;

        case 'item_delete':

          $stmt = $connection->prepare("DELETE FROM item WHERE upc=?");

          $del_upc = $_POST["del_item_upc"];

          $stmt->bind_param("s", $del_upc);

          $stmt->execute();

          if($stmt->error) {       
            printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
            echo "Successfully deleted item with upc ".$del_upc;
        }
        break;

        case 'leadSinger_select':

          if (!$result = $connection->query("SELECT * FROM LeadSinger")) {
              die('There was an error running the query [' . $database->error . ']');
              }
            else{
            while($row = $result->fetch_assoc()){
               echo "<tr><td>".$row['upc']."</td>";
               echo "<td>".$row['name']."</td></tr>";
            }
          }
        break;

        case 'leadSinger_insert':

        $stmt = $connection->prepare("INSERT INTO LeadSinger (upc, name) VALUES (?, ?)");

        $new_upc = $_POST["new_leadSinger_upc"];
        $new_name = $_POST["new_leadSinger_name"];

        $stmt->bind_param("ss", $new_upc, $new_name);
        $stmt->execute();

        if($stmt->error) {       
          printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
          echo "Successfully inserted the following data:<br/>";
          echo "upc: ".$new_upc."<br/>";
          echo "name: ".$new_name."<br/>";
        }
        break;

        case 'leadSinger_delete':

          $stmt = $connection->prepare("DELETE FROM LeadSinger WHERE upc=? AND name=?");

          $del_upc = $_POST["del_leadSinger_upc"];
          $del_name = $_POST["del_leadSinger_name"];

          $stmt->bind_param("ss", $del_upc, $del_name);

          $stmt->execute();

          if($stmt->error) {       
            printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
            echo "Successfully deleted item with upc ".$del_upc." and name ".$del_name;
        }
        break;

        case 'hasSong_select':

          if (!$result = $connection->query("SELECT * FROM hasSong")) {
              die('There was an error running the query [' . $database->error . ']');
              }
            else{
            while($row = $result->fetch_assoc()){
               echo "<tr><td>".$row['upc']."</td>";
               echo "<td>".$row['title']."</td></tr>";
            }
          }
        break;

        case 'hasSong_insert':

        $stmt = $connection->prepare("INSERT INTO HasSong (upc, title) VALUES (?, ?)");

        $new_upc = $_POST["new_hasSong_upc"];
        $new_title = $_POST["new_hasSong_title"];

        $stmt->bind_param("ss", $new_upc, $new_title);
        $stmt->execute();

        if($stmt->error) {       
          printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
          echo "Successfully inserted the following data:<br/>";
          echo "upc: ".$new_upc."<br/>";
          echo "name: ".$new_title."<br/>";
        }
        break;

        case 'hasSong_delete':

        $stmt = $connection->prepare("DELETE FROM HasSong WHERE upc=? AND title=?");

          $del_upc = $_POST["del_hasSong_upc"];
          $del_title = $_POST["del_hasSong_title"];

          $stmt->bind_param("ss", $del_upc, $del_title);

          $stmt->execute();

          if($stmt->error) {       
            printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
            echo "Successfully deleted item with upc ".$del_upc." and title ".$del_title;
        }
        break;

        case 'order_select':

          if (!$result = $connection->query("SELECT * FROM i_order")) {
            die('There was an error running the query [' . $database->error . ']');
            }
        else{
          while($row = $result->fetch_assoc()){
             echo "<tr><td>".$row['receiptId']."</td>";
             echo "<td>".$row['order_date']."</td>";
             echo "<td>".$row['cid']."</td>";
             echo "<td>".$row['cardNumber']."</td>";
             echo "<td>".$row['expiryDate']."</td>";
             echo "<td>".$row['expectedDate']."</td>";
             echo "<td>".$row['deliveredDate']."</td></tr>";
          }
        }
        break;

        case 'order_insert':

        $stmt = $connection->prepare("INSERT INTO i_order (receiptId, order_date, cid, cardNumber, expiryDate, expectedDate, deliveredDate) VALUES (?,?,?,?,?,?,?)");

        $new_receiptId = $_POST["new_order_receiptId"];
        $new_orderDate = $_POST["new_order_orderDate"];
        $new_cid = $_POST["new_order_cid"];
        $new_cardNumber = $_POST["new_order_cardNumber"];
        $new_expiryDate = $_POST["new_order_expiryDate"];
        $new_expectedDate = $_POST["new_order_expectedDate"];
        $new_deliveredDate = $_POST["new_order_deliveredDate"];

        $stmt->bind_param("sssssss", $new_receiptId, $new_orderDate, $new_cid, $new_cardNumber, $new_expiryDate, $new_expectedDate, $new_deliveredDate);

        $stmt->execute();

        if($stmt->error) {       
          printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
          echo "Successfully inserted the following data:<br/>";
          echo "receiptId".$new_receiptId."<br/>";
          echo "orderDate".$new_orderDate."<br/>";
          echo "cid".$new_cid."<br/>";
          echo "cardNumber".$new_cardNumber."<br/>";
          echo "expiryDate".$new_expiryDate."<br/>";
          echo "expectedDate".$new_expectedDate."<br/>";
          echo "deliveredDate".$new_deliveredDate."<br/>";
        }
        break;

        case 'order_delete':

        $stmt = $connection->prepare("DELETE FROM i_order WHERE receiptId=?");

          $del_receiptId = $_POST["del_order_receiptId"];

          $stmt->bind_param("s", $del_receiptId);

          $stmt->execute();

          if($stmt->error) {       
            printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
            echo "Successfully deleted item with Receipt ID ".$del_receiptId;
        }
        break;

        case 'purchaseItem_select':

          if (!$result = $connection->query("SELECT * FROM purchaseItem")) {
            die('There was an error running the query [' . $database->error . ']');
            }
        else{
          while($row = $result->fetch_assoc()){
             echo "<tr><td>".$row['receiptId']."</td>";
             echo "<td>".$row['upc']."</td>";
             echo "<td>".$row['quantity']."</td></tr>";
          }
        }
        break;

        case 'purchaseItem_insert':

        $stmt = $connection->prepare("INSERT INTO PurchaseItem (receiptId, upc, quantity) VALUES (?, ?, ?)");

        $new_receiptId = $_POST["new_purchaseItem_receiptId"];
        $new_upc = $_POST["new_purchaseItem_upc"];
        $new_quantity = $_POST["new_purchaseItem_quantity"];

        $stmt->bind_param("ssi", $new_receiptId, $new_upc, $new_quantity);
        $stmt->execute();

        if($stmt->error) {       
          printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
          echo "Successfully inserted the following data:<br/>";
          echo "receiptId: ".$new_receiptId."<br/>";
          echo "upc: ".$new_upc."<br/>";
          echo "quantity: ".$new_quantity."<br/>";
        }
        break;

        case 'purchaseItem_delete':

        $stmt = $connection->prepare("DELETE FROM PurchaseItem WHERE receiptId=? AND upc=?");

          $del_receiptId = $_POST["del_purchaseItem_receiptId"];
          $del_upc = $_POST["del_purchaseItem_upc"];

          $stmt->bind_param("ss", $del_receiptId, $del_upc);

          $stmt->execute();

          if($stmt->error) {       
            printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
            echo "Successfully deleted item with Receipt ID ".$del_receiptId." and UPC ".$del_upc;
        }
        break;

        case 'customer_select':

          if (!$result = $connection->query("SELECT * FROM customer")) {
            die('There was an error running the query [' . $database->error . ']');
            }
        else{
          while($row = $result->fetch_assoc()){
             echo "<tr><td>".$row['cid']."</td>";
             echo "<td>".$row['password']."</td>";
             echo "<td>".$row['name']."</td>";
             echo "<td>".$row['address']."</td>";
             echo "<td>".$row['phone']."</td></tr>";
          }
        }
        break;

        case 'customer_insert':

        $stmt = $connection->prepare("INSERT INTO Customer (cid, password, name, address, phone) VALUES (?,?,?,?,?)");

        $new_cid = $_POST["new_customer_cid"];
        $new_password = $_POST["new_customer_password"];
        $new_name = $_POST["new_customer_name"];
        $new_address = $_POST["new_customer_address"];
        $new_phone = $_POST["new_customer_phone"];

        $stmt->bind_param("sssss", $new_cid, $new_password, $new_name, $new_address, $new_phone);

        $stmt->execute();

        if($stmt->error) {       
          printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
          echo "Successfully inserted the following data:<br/>";
          echo "cid: ".$new_cid."<br/>";
          echo "password: ".$new_password."<br/>";
          echo "name: ".$new_name."<br/>";
          echo "address: ".$new_address."<br/>";
          echo "phone: ".$new_phone."<br/>";
        }
        break;



        case 'customer_delete':

        $stmt = $connection->prepare("DELETE FROM Customer WHERE cid=?");

          $del_cid = $_POST["del_customer_cid"];

          $stmt->bind_param("s", $del_cid);

          $stmt->execute();

          if($stmt->error) {       
            printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
            echo "Successfully deleted item with CID ".$del_cid;
        }
        break;

        case 'return_select':

          if (!$result = $connection->query("SELECT * FROM c_return")) {
            die('There was an error running the query [' . $database->error . ']');
            }
        else{
          while($row = $result->fetch_assoc()){
             echo "<tr><td>".$row['retid']."</td>";
             echo "<td>".$row['return_date']."</td>";
             echo "<td>".$row['receiptId']."</td></tr>";
          }
        }
        break;

        case 'return_insert':

        $stmt = $connection->prepare("INSERT INTO c_return (retid, return_date, receiptId) VALUES (?, ?, ?)");

        $new_retId = $_POST["new_return_retid"];
        $new_returnDate = $_POST["new_return_returnDate"];
        $new_receiptId = $_POST["new_return_receiptId"];

        $stmt->bind_param("sss", $new_retId, $new_returnDate, $new_receiptId);
        $stmt->execute();

        if($stmt->error) {       
          printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
          echo "Successfully inserted the following data:<br/>";
          echo "retId: ".$new_retId."<br/>";
          echo "returnDate: ".$new_returnDate."<br/>";
          echo "receiptId: ".$new_receiptId."<br/>";
        }
        break;

        case 'return_delete':

        $stmt = $connection->prepare("DELETE FROM c_return WHERE retid=?");

          $del_retid = $_POST["del_return_retid"];

          $stmt->bind_param("s", $del_retid);

          $stmt->execute();

          if($stmt->error) {       
            printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
            echo "Successfully deleted item with RetID ".$del_retid;
        }
        break;

        case 'returnItem_select':

          if (!$result = $connection->query("SELECT * FROM returnItem")) {
            die('There was an error running the query [' . $database->error . ']');
            }
        else{
          while($row = $result->fetch_assoc()){
             echo "<tr><td>".$row['retid']."</td>";
             echo "<td>".$row['upc']."</td>";
             echo "<td>".$row['quantity']."</td></tr>";
          }
        }
        break;

        case 'returnItem_insert':

        $stmt = $connection->prepare("INSERT INTO ReturnItem (retid, upc, quantity) VALUES (?, ?, ?)");

        $new_retId = $_POST["new_returnItem_retid"];
        $new_upc = $_POST["new_returnItem_upc"];
        $new_quantity = $_POST["new_returnItem_quantity"];

        $stmt->bind_param("sss", $new_retId, $new_upc, $new_quantity);
        $stmt->execute();

        if($stmt->error) {       
          printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
          echo "Successfully inserted the following data:<br/>";
          echo "retId: ".$new_retId."<br/>";
          echo "upc: ".$new_upc."<br/>";
          echo "quantity: ".$new_quantity."<br/>";
        }
        break;

        case 'returnItem_delete':

        $stmt = $connection->prepare("DELETE FROM returnItem WHERE retid=?");

          $del_retid = $_POST["del_returnItem_retid"];

          $stmt->bind_param("s", $del_retid);

          $stmt->execute();

          if($stmt->error) {       
            printf("<b>Error: %s.</b>\n", $stmt->error);
        } else {
            echo "Successfully deleted item with RetID ".$del_retid;
        }
        break;
    }
?>
