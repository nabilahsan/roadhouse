<?php
include_once('db_conn.php');
?>
<!DOCTYPE html>
<!-- Main Sign In Page -->
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <!-- Our stylesheet -->
  <link rel="stylesheet" href="resources/style.css">

  <title>HOME PAGE</title>

</head>

<body>

  <main class="container">

    <?php
    $uname = $_SESSION['user_name'];

    $query1 = "SELECT * FROM log WHERE user_name='$uname'";


    // Information from Login Table
    if ($result1 = $dbconn->query($query1)) {

      while ($row = $result1->fetch_assoc()) {
        $userID = $row["user_id"];
        $fullName = $row["full_name"];
        $cityName = $row["city"];
        $stateName = $row["state"];
        $zipCode = $row["zip"];

        echo nl2br("\r\nName: " . $fullName);
        echo nl2br("\r\nCity: " . $cityName);
        echo nl2br("\r\nState: " . $stateName);
        echo nl2br("\r\nZip: " . $zipCode);
        echo nl2br("\r\nUser ID_Login: " . $userID);
      }
    }

    // Trying to display fuel calc info JUST for logged user

    // $query2 = "SELECT cust_user_id FROM fuelcalc where cust_username = $uname";
    // $result2 = $dbconn->query($query2);

    // if ($result2->num_rows > 0) {
    //   while ($row = $result2->fetch_assoc()) {
    //     echo nl2br("\r\n User ID from fuel table is: " . $row["cust_user_id"]);
    //   }
    // } else {
    //   echo "<h2> NOOOOOOOOOOOOOOOOOOOOOOOO </h2>";
    // }

    // Form Validation
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      if (isset($_POST['calculate'])) {
        $numGallons = test_input($_POST["numGallons"]);
        $chooseMonth = test_input($_POST["chooseMonth"]);
        $chooseDay = test_input($_POST["chooseDay"]);
        $chooseYear = test_input($_POST["chooseYear"]);

        // Checking if the input is numeric
        if (!preg_match("/^[0-9]*$.", $numGallons)) {
          $errorMessage = "Only numbers allowed.";
        }

        if (empty($numGallons) || empty($chooseMonth) ||empty($chooseDay) || empty($chooseYear))
        {
          $errorMessage = "Please fill form completely";
        }
      }
    }

    function test_input($data)
    {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }


    // Fuel Calculator
    // Extra variables
    $transportationCost = 0.02;
    $discount = 0.01;
    // Pricing module - to be added later
    $pricePerGallon = 100;


    $totalPrice = $numGallons * ($transportationCost + $discount + $pricePerGallon);

    $query3 = "INSERT INTO fuelcalc (num_gallons, c_month, c_day, c_year, price_per_gallon, trans_cost, discount, total_price, cust_user_id) VALUES ('$numGallons', '$chooseMonth', '$chooseDay', '$chooseYear', '$pricePerGallon', '$transportationCost', '$discount', '$totalPrice', '$userID')";

    if ($uname == true ) {
      # code...
      if(!empty($numGallons) || !empty($chooseMonth) || !empty($chooseDay) || !empty($chooseYear)){
      $result3 = $dbconn->query($query3);
      }
      if ($result3 == true) {
  
          echo "Yay";
  
      } else {
        
        echo nl2br("\r\n ERROR: Cannot execute $result3. " . $dbconn->error);
      }
    }

    ?>


    <div class="container" id="buttons">

    </div>

    <!-- Testing the calculator -->
    <section>


      <!-- Beginning of Fuel Calculator -->

      <div class="container" id="main">

        <form method="post" action="#" class="form-group">
          <div class="container" style="margin-top: 40px">

            <h2>Enter information below</h2>

            <div class="form-group">
            <label>Requested Number of Gallons</label>
            <div class="input-group">
            <input class="form-control" type="number" id="numGallons" name="numGallons" required placeholder="This must be a number" min="1" max="100000">
            </div>
          </div>

            <div id="date_value" name="date_value">
              <h3> For Delivery Date</h3>

              <!-- Assign values to Month -->
              <div class="form-group">
              <label>Choose the month:</label>
              <?php
              $MonthArray = array("1" => "January", "2" => "February", "3" => "March", "4" => "April", "5" => "May", "6" => "June", "7" => "July", "8" => "August", "9" => "September", "10" => "October", "11" => "November", "12" => "December");
              ?>
              <select name="chooseMonth" class="form-control" required>
                <option value="">Select Month</option>
                <?php
                foreach ($MonthArray as $monthNum => $chooseMonth) {
                  $selected = (isset($getMonth) && $getMonth == $monthNum) ? 'selected' : '';
                  echo '<option ' . $selected . ' value="' . $monthNum . '">' . $chooseMonth . '</option>';
                }
                ?>
              </select>
              </div>

              <!-- Enter year -->
              <div class="form-group">
              <label>Choose the Day of the month:</label>
              <div class="input-group">
              <input name="chooseDay" id="chooseDay" type="text" class="form-control" placeholder="Enter the day as a number, like 14." required>
              </div>
              </div>

              <!-- Enter year -->
              <div class="form-group">
              <label>Enter the year:</label>
              <div class="input-group">
              <input name="chooseYear" id="chooseYear" type="text" class="form-control" placeholder="2019" min="2019" required>
              </div>
              </div>

            <?php
            if(!empty($uname)) {
            ?>
            <button class="btn btn-success" name="calculate" id="calculate" type="Submit">Calculate </button>
            <a class="btn btn-secondary" href="register.php" style="float: right;">Edit Profile</a>
            <?php
            }  else {
            ?>
              <button class="btn btn-success" name="calculate" id="calculate" type="Submit" disabled>Calculate </button>
              <small class="text-danger">Please login with link below</small>

              <?php
              }
              ?>
            <br>
            <a href="login.php">Don't wanna be here? LEAVE!</a>


            <h2 style="margin-top: 25px"> The Bill:
              <?php
              if (isset($_POST['calculate'])) {
                $selected_val = $_POST["chooseMonth"];
                echo "You ordered " . $numGallons . " gallons" . "<br>";
                echo "Your order will be delivered on " . $selected_val . " / " . $chooseDay . " / " . $chooseYear . "<br>";
                echo "Your transportation cost is $" . $transportationCost . "<br>";
                echo "Your discount is $" . $discount . "<br>";
                echo "Your pricePerGallon is $" . $pricePerGallon . "<br>";
                echo "Total Price is $ " . $totalPrice;
              }
              ?>
            </h2>

            <!-- This where the calculation will take place -->
            <!-- Should be PHP script -->
            <!-- With database calls -->



          </div>


    </section>

  </main>

</body>

</html>