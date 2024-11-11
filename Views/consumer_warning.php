<?php
session_start();

// Assuming the user's ID is stored in the session after they log in
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];  // Retrieve the logged-in user's ID from the session
    $billingStatus = null;  // Initialize billingStatus

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "water_supply_management_system";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to get the meter_number from the consumer table using the user_id
    $sql = "SELECT meter_number FROM consumer WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);  // Bind the user_id to the statement
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $meter_number = $row['meter_number'];  // Retrieve the meter_number

        // Now that we have the meter_number, query the bills table for billing status
        $sql2 = "SELECT billingStatus FROM bills WHERE meter_number = ? AND billingStatus = 'unpaid'";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("s", $meter_number);  // Bind the meter_number
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            $billingStatus = $row2['billingStatus'];  // Get the billingStatus
        } else {
            $billingStatus = 'No unpaid bills found';  // Default message if no unpaid bills
        }

        $stmt2->close();
    } else {
        $billingStatus = 'Meter number not found for this user';  // Default message if meter_number is not found
    }

    // Close connections
    $stmt->close();
    $conn->close();
} else {
    echo "User not logged in";  // Handle case where user is not logged in
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Consumer wanings</title>
    <link rel="stylesheet" href="../css/concumer_feedback.css">
    <script>
        // Function to fetch billing status using AJAX
        function fetchBillingStatus() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'getBillingStatus.php', true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    // Display the billing status in the textarea
                    document.getElementById('billingWarning').value = response.status;
                }
            };
            xhr.onerror = function() {
                console.log('Request error...');
            };
            xhr.send();
        }

        // Call the function when the page loads
        window.onload = fetchBillingStatus;
    </script>
</head>
<body>
<header>
    <div class="logo">
        <img class="logopic" src="../images/logo.png" alt="Aqua Link Logo">
    </div>
    <nav>
        <ul>
            <li><a class="nav-link" href="#">Home</a></li>
            <li><a class="nav-link" href="#">Services</a></li>
            <li><a class="nav-link" href="#">About Us</a></li>
            <li><a class="nav-link" href="#">Contact Us</a></li>
            <li><a class="nav-link" href="#">FAQ</a></li>
        </ul>
    </nav>
    <div class="search-bar">
        <form>
            <input class="form-control me-2" type="search" placeholder="Search here" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
</header>

<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Water Management</h3>
        </div>
        <ul class="list-unstyled components">
            <li>
                <a href="consumer.php" class="dashboard">Dashboard</a>
            </li>
          
        </ul>
    </nav>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        td {
            width: 200px;
            height: 60px;
            text-align: center;
        }
    </style>
    <div class="container" style="background-color: #b4b9bc;">
        <div class="text-section" style="color: #0f0f0f; width:800px;">
            <h1>warnings</h1>
            <textarea id="billingWarning">
                <?php
                if ($billingStatus === 'unpaid') {
                    echo "Warning: You have unpaid bills!";
                } else {
                    echo $billingStatus;  // Display the message if no unpaid bills or other message
                }
                ?>
           </textarea>
        </div>
        
    </div>
</div>

<footer class="footer">
    <div class="footer-section">
        <h3>AQUA LINK</h3>
        <p>
            The Water Supply Management System aims to revolutionize the traditional manual processes of water administration in rural areas. By leveraging modern technology, this system seeks to streamline water distribution, billing, and maintenance, ensuring a more efficient and reliable supply of water.
        </p>
    </div>
    <div class="footer-section">
        <h3>USEFUL LINKS</h3>
        <ul>
            <li><a href="#">My Account</a></li>
            <li><a href="#">Annual Reports</a></li>
            <li><a href="#">Customer Services</a></li>
            <li><a href="#">Help</a></li>
        </ul>
    </div>
    <div class="footer-section contact-info">
        <h3>CONTACT</h3>
        <p>Colombo, Sri Lanka</p>
        <p>info@aqualink.lk</p>
        <p>+ 94 764 730 521</p>
        <p>+ 94 760 557 356</p>
    </div>
</footer>
<div class="footer-bottom">
    <p>&copy; 2024 Copyright: aqualink.lk</p>
</div>
</body>


</html>
