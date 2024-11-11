<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>REQUEST</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/consumer_request.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<header>
<nav class="navbar navbar-expand-lg">
    <img class="logopic" src="../images/logo.png">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Home </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="services.php">Services</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="aboutus.php">About us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contactus.php">Contact us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="faq.php">F&Q</a>
            </li>
        </ul>
    </div>
</nav>

<div class="form-inline">
    <form method="POST" action="">
        <input class="form-control me-2" type="search" placeholder="Search here" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
</div>
</header>

<div class="wrapper">
    <nav id="sidebar">
        <ul class="list-unstyled components">
            <div class="parts">
            <li>
                <a href="consumer.php" class="dashboard">Dashboard</a>
            </li>
            </div>
            <div class="parts">
            <li>
                <a href="#filterRequestTitle" class="scroll-link">Requesting Filters</a>
            </li></div>
            <div class="parts">
            <li>
                <a href="#maintenanceRequestTitle" class="scroll-link">Requesting Maintenance</a>
            </li></div>
        </ul>
    </nav>
    <div id="content">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div id="filterRequestForm" class="request-form">
                        <h5 id="filterRequestTitle">Filter Request Form</h5>
                            <form method="POST" action="consumer_request.php">
                            <div class="mb-3">
                                <label for="meter_number" class="form-label">Meter Number</label>
                                <input type="text" class="form-control" id="meter_number" name="meter_number">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="mb-3">
                                <label for="filterQuantity" class="form-label">Filter Quantity</label>
                                <input type="number" class="form-control" id="filterQuantity" name="filterQuantity">
                            </div>
                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Payment Method</label>
                                <select class="form-control" id="paymentMethod" name="paymentMethod">
                                    <option disabled selected>Select the payment method</option>
                                    <option>Credit Card</option>
                                    <option>Debit Card</option>
                                    <option>PayPal</option>
                                    <option>Bank Transfer</option>
                                </select>
                            </div>
                                <button name='fRequest' class="btn btn-primary" type="submit">Request</button>
                            </form>



        <div id="maintenanceRequestForm" class="request-form">
        <h5 id="maintenanceRequestTitle">Maintenance Request Form</h5>
        <form method="post" action="consumer_request.php">
            <div class="mb-3">
                <label for="meter_number" class="form-label">Meter Number</label>
                <input type="text" class="form-control" id="meter_number" name="meter_number" required>
            </div>
            <div class="mb-3">
                <label for="phoneno" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phoneno" name="phoneno" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Address</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="mb-3">
                <label for="issueDescription" class="form-label">Issue Description</label>
                <input type="text" class="form-control" id="issueDescription" name="issueDescription" required>
            </div>
            <button type="submit" name="MRequest" class="btn btn-primary">Submit Maintenance Request</button>
        </form>

</div>
</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
<script src="script.js"></script>

</body>
<footer class="text-center text-lg-start text-white" style=" background: linear-gradient(108.9deg, rgb(18, 85, 150) 4.9%, rgb(100, 190, 150) 97%);  ">
    
        <div class="container text-center text-md-start mt-5">
            <div class="row mt-3">
                <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">AQUA LINK</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #7c4dff; height: 2px"/>
                    <p>The Water Supply Management System aims to revolutionize the traditional manual processes of water administration in rural areas. By leveraging modern technology, this system seeks to streamline water distribution, billing, and maintenance, ensuring a more efficient and reliable supply of water.</p>
                </div>

                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">Useful links</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #7c4dff; height: 2px"/>
                    <p><a href="#!" class="text-white">My Account</a></p>
                    <p><a href="annualreports.php" class="text-white">Annual Reports</a></p>
                    <p><a href="customerservices.php" class="text-white">Customer Services</a></p>
                    <p><a href="help.php" class="text-white">Help</a></p>
                </div>

                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <h6 class="text-uppercase fw-bold">Contact</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #7c4dff; height: 2px"/>
                    <p><i class="fas fa-home me-3"></i>Colombo, Sri Lanka</p>
                    <p><i class="fas fa-envelope me-3"></i>info@example.com</p>
                    <p><i class="fas fa-phone me-3"></i>+94 764 730 521</p>
                    <p><i class="fas fa-print mr-3"></i> + 94 760 557 356</p>
                </div>
            </div>
            <div class="text-center p-3" style="height: 50px;">
                © 2024 Copyright: <a class="text-white" href="">aqualink.lk</a>
            </div>
            </div>
    </section>
</footer>
</html>

<?php
include('db.php'); 

if (isset($_POST['fRequest'])) {
    // Process filter request form
    $meter_number = isset($_POST['meter_number']) ? $conn->real_escape_string($_POST['meter_number']) : null;
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : null;
    $phone = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : null;
    $filterQuantity = isset($_POST['filterQuantity']) ? (int)$_POST['filterQuantity'] : null;
    $paymentMethod = isset($_POST['paymentMethod']) ? $conn->real_escape_string($_POST['paymentMethod']) : null;

    // Debugging information
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";

    if ($meter_number && $name && $phone && $filterQuantity !== null && $paymentMethod) { 

        $sqlOrder = "INSERT INTO orders (meter_number, name, phone, filterQuantity, orderStatus)
                     VALUES ('$meter_number', '$name', '$phone', '$filterQuantity', '$orderStatus')";

        if ($conn->query($sqlOrder) === TRUE) {
            echo '
            <script>
                alert("Successful!.");
            </script>';
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        $missingFields = [];
        if (!$meter_number) $missingFields[] = "meter_number";
        if (!$name) $missingFields[] = "name";
        if (!$phone) $missingFields[] = "phone";
        if ($filterQuantity === null) $missingFields[] = "filterQuantity";
        if (!$paymentMethod) $missingFields[] = "paymentMethod";
        
        echo "Missing fields: " . implode(", ", $missingFields);
    }

}
if (isset($_POST['MRequest'])) {
    // Process maintenance request form
    $meter_number = isset($_POST['meter_number']) ? $conn->real_escape_string($_POST['meter_number']) : null;
    $phone = isset($_POST['phoneno']) ? $conn->real_escape_string($_POST['phoneno']) : null;
    $location = isset($_POST['location']) ? $conn->real_escape_string($_POST['location']) : null;
    $issueDescription = isset($_POST['issueDescription']) ? $conn->real_escape_string($_POST['issueDescription']) : null;

    // Debugging information
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";

    if ($meter_number && $phone && $location && $issueDescription) {
        // Fetch user name based on the given meter number
        $sql = "SELECT u.name, c.id as id 
                FROM consumer c 
                INNER JOIN users u ON c.id = u.id 
                WHERE c.meter_number = '$meter_number'";
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['name'];  // Get name from users table
            $id = $row['id'];  // Get id from consumer table
            
            // Insert data into maintenance_requests table
            $sqlMaintenanceRequest = "INSERT INTO maintenance_requests (meter_number, phone, location, issue_description, name, id)
                                      VALUES ('$meter_number', '$phone', '$location', '$issueDescription', '$name', '$id')";
            
            if ($conn->query($sqlMaintenanceRequest) === TRUE) {
                echo '
                <script>
                    alert("Successful!");
                </script>';
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Meter number not found in the database.";
        }
    } else {
        $missingFields = [];
        if (!$meter_number) $missingFields[] = "meter_number";
        if (!$phone) $missingFields[] = "phone";
        if (!$location) $missingFields[] = "location";
        if (!$issueDescription) $missingFields[] = "issueDescription";
        
        echo "Missing fields: " . implode(", ", $missingFields);
    }
}
?>