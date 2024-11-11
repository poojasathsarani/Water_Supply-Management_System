<?php
ob_start(); // Start output buffering

include('db.php');

// Query to fetch service providers for the dropdown
$serviceProviderQuery = "SELECT id, name FROM users WHERE role = 'serviceprovider'";
$serviceProviderResult = mysqli_query($conn, $serviceProviderQuery);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize common data from the form
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : '';
    $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';
    $province = isset($_POST['province']) ? mysqli_real_escape_string($conn, $_POST['province']) : '';
    $city = isset($_POST['city']) ? mysqli_real_escape_string($conn, $_POST['city']) : '';
    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';
    $confirmPassword = isset($_POST['confirm-password']) ? mysqli_real_escape_string($conn, $_POST['confirm-password']) : '';
    $role = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : '';

    // Check if all required fields are filled
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($province) || empty($city) || empty($username) || empty($password) || empty($confirmPassword) || empty($role)) {
        echo '<script>
            alert("Please fill in all the required fields.");
            window.location="register.php";
            </script>';
        exit();
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo '<script>
            alert("Passwords do not match.");
            window.location="register.php";
            </script>';
        exit();
    }

    // Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert common user data into 'users' table
    $query = "INSERT INTO users (name, email, phone, address, province, city, username, password, role) 
              VALUES ('$name', '$email', '$phone', '$address', '$province', '$city', '$username', '$hashedPassword', '$role')";
    
    if (mysqli_query($conn, $query)) {
        // Get the last inserted user ID
        $user_id = mysqli_insert_id($conn);

        // Insert role-specific data
        if ($role == 'consumer') {
            $postal = isset($_POST['postal']) ? mysqli_real_escape_string($conn, $_POST['postal']) : '';
            $meter_number = isset($_POST['meter_number']) ? mysqli_real_escape_string($conn, $_POST['meter_number']) : '';
            $query = "INSERT INTO consumer (postal_code, meter_number, id) VALUES ('$postal', '$meter_number', '$user_id')";
        } elseif ($role == 'technician') {
            $serviceprovider = isset($_POST['serviceprovider']) ? mysqli_real_escape_string($conn, $_POST['serviceprovider']) : '';
            
            // Retrieve the service provider's name based on the selected service provider ID
            $serviceProviderNameQuery = "SELECT name FROM users WHERE id = '$serviceprovider'";
            $serviceProviderNameResult = mysqli_query($conn, $serviceProviderNameQuery);
            
            if ($serviceProviderNameResult && mysqli_num_rows($serviceProviderNameResult) > 0) {
                $serviceProviderRow = mysqli_fetch_assoc($serviceProviderNameResult);
                $serviceProviderName = $serviceProviderRow['name'];
                
                // Insert the service provider's name into the technician table
                $query = "INSERT INTO technician (id, service_provider) VALUES ('$user_id', '$serviceProviderName')";
            } else {
                echo '<script>
                    alert("Error retrieving service provider name.");
                    window.location="register.php";
                    </script>';
                exit();
            }
        } elseif ($role == 'serviceprovider') {
            $servicearea = isset($_POST['servicearea']) ? mysqli_real_escape_string($conn, $_POST['servicearea']) : '';
            $query = "INSERT INTO serviceProvider (id, service_area) VALUES ('$user_id', '$servicearea')";
        }

        if (isset($query) && mysqli_query($conn, $query)) {
            // Redirect to the relevant dashboard based on role
            header('Location: logins.php');
            exit(); // Ensure exit after header redirect
        } else {
            echo '<script>
                alert("Error inserting role-specific data.");
                window.location="register.php";
                </script>';
        }
    } else {
        echo '<script>
            alert("Error registering user.");
            window.location="register.php";
            </script>';
    }
}

ob_end_flush(); // End output buffering and flush output
?>





<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create an Account</title>
<link rel="stylesheet" type="text/css" href="../css/register.css">
</head>
<body>
<video autoplay muted loop id="background-video">
  <source src="../images/background.mp4" type="video/mp4">
</video>
<div class="container">
  <div class="form-box">
    <h2>Create an Account</h2>
    <form id="registerForm" action="register.php" method="POST">
    <div class="input-group">
        <label for="status">Status</label>
        <select id="status" name="status" required>
          <option disabled selected>Select Your Role</option>
          <option value="consumer">Consumer</option>
          <option value="meterreader">Meter Reader</option>
          <option value="technician">Technician</option>
          <option value="serviceprovider">Service Provider</option>
        </select>
      </div>
      <div class="form-row">
        <div class="form-column">
          <div id="common-fields">
            <div class="input-group">
              <label for="name">Name</label>
              <input type="text" id="name" name="name" required>
            </div>
            <div class="input-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
              <label for="phone">Phone Number</label>
              <input type="text" id="phone" name="phone" required>
            </div>
            <div class="input-group">
              <label for="address">Address</label>
              <input type="text" id="address" name="address" required>
            </div>
            <div class="input-group">
              <label for="province">Province</label>
              <select id="province" name="province" required>
                <option disabled selected>Select Province</option>
                <option value="western">Western Province</option>
                <option value="central">Central Province</option>
                <option value="southern">Southern Province</option>
                <option value="northern">Northern Province</option>
                <option value="eastern">Eastern Province</option>
                <option value="north_western">North Western Province</option>
                <option value="north_central">North Central Province</option>
                <option value="uva">Uva Province</option>
                <option value="sabaragamuwa">Sabaragamuwa Province</option>
              </select>
            </div>
            <div class="input-group">
              <label for="city">City</label>
              <input type="text" id="city" name="city" required>
            </div>
          </div>
          <div id="consumer-fields">
              <div class="input-group">
                <label for="postal">Postal Code</label>
                <input type="text" id="postal" name="postal">
              </div>
              <div class="input-group">
                <label for="meter_number">Meter Number</label>
                <input type="text" id="meter_number" name="meter_number">
              </div>
          </div>

        </div>
        <div class="form-column">
          <div id="technician-fields">
            <div class="input-group">
              <label for="serviceprovider">Service Provider</label>
              <select id="serviceprovider" name="serviceprovider" required>
              <option disabled selected>Select Service Provider</option>
              <?php
                // Loop through the service providers and display them in the dropdown
                while ($row = mysqli_fetch_assoc($serviceProviderResult)) {
                  echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                }
              ?>
              </select>
            </div>
          </div>
          <div id="serviceprovider-fields">
              <div class="input-group">
                <label for="servicearea">Service Area</label>
                <input type="text" id="servicearea" name="servicearea">
              </div>
          </div>
          <div class="input-group">
            <label for="username">User Name</label>
            <input type="text" id="username" name="username" required>
          </div>
          <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
          </div>
          <div class="input-group">
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
          </div>
        </div>
      </div>
      <button type="submit">Sign Up</button>
      <p>Already have an Account? <a href="logins.php">Log in</a></p>
    </form>
  </div>
</div>

<script src="../js/register.js"></script>
</body>
</html>