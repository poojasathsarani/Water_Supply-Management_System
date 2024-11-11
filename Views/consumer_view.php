<?php
session_start(); 
// Database connection
$servername = "localhost"; // or your server name
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "water_supply_management_system"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from the session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id === null) {
    die("User not logged in or session expired."); // Error handling if user is not logged in
}

// Retrieve the logged-in user's name based on the session ID
$sql_user = "SELECT name FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
if ($stmt_user === false) {
    die("Error preparing SQL statement: " . $conn->error);
}
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $user_name = $row_user['name'];
} else {
    die("User not found.");
}




// SQL query to retrieve orders for the user
$sql_orders = "SELECT  o.meter_number, o.name, o.phone, o.filterQuantity, o.orderStatus, o.created_at
FROM orders o
INNER JOIN consumer c ON o.meter_number = c.meter_number
WHERE c.id = ?";
$stmt_orders = $conn->prepare($sql_orders);
if ($stmt_orders === false) {
    die("Error preparing SQL statement: " . $conn->error);
}
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();




// Query the updatetask table where the name matches the logged-in user's name
$sql_task = "SELECT name, priority, startDate, endDate, completedPercentage, status, budget FROM updatetask WHERE name = ?";
$stmt_task = $conn->prepare($sql_task);
if ($stmt_task === false) {
    die("Error preparing SQL statement: " . $conn->error);
}
$stmt_task->bind_param("s", $user_name); // Bind the name from the users table
$stmt_task->execute();
$result_task = $stmt_task->get_result();




// SQL query for Bill Summary
$sql_bills = "SELECT  b.meter_number, b.name, b.billing_date, 
       b.current_consumption, b.previous_consumption, b.billingStatus 
FROM bills b
INNER JOIN consumer c ON b.meter_number = c.meter_number
WHERE c.id = ?
";
$stmt_bills = $conn->prepare($sql_bills);
if ($stmt_bills === false) {
    die("Error preparing SQL statement: " . $conn->error);
}
$stmt_bills->bind_param("i", $user_id);
$stmt_bills->execute();
$result_bills = $stmt_bills->get_result();




// SQL query for time slots
$sql_timeslots = "SELECT ts.location, ts.distributionDate, ts.startTime, ts.endTime, ts.notes, ts.created_at, ts.updated_at 
        FROM timeSlots ts
        INNER JOIN users u ON ts.location = u.city
        WHERE u.id = ?";
$stmt_timeslots = $conn->prepare($sql_timeslots);
if ($stmt_timeslots === false) {
    die("Error preparing SQL statement: " . $conn->error);
}
$stmt_timeslots->bind_param("i", $user_id);
$stmt_timeslots->execute();
$result_timeslots = $stmt_timeslots->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Consumer View</title>
    <link rel="stylesheet" href="../css/consumer_view.css">
</head>
<body style="background-color: #a7e7ed;">
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
    <nav id="sidebar" style="height:1200px;">
        <div class="sidebar-header">
            <h3>Water Management</h3>
        </div>
        <ul class="list-unstyled components">
            <li>
                <a href="consumer.php" class="dashboard">Dashboard</a>
            </li>
        </ul>
    </nav>
    <div class="dashboard" style="display: flex; flex-direction: column; gap: 20px; margin: 90px;">

        <!-- Order Summary -->
        <div class="summary" style="position: absolute; width: 77%; background-color:#6f9ee8; left: 280px; padding:20px;">
            <h3>Order Summary</h3>
            <table border="1" cellspacing="0" cellpadding="10" style="width: 100%; background-color: #ffffff;">
                <thead>
                    <tr style="background-color: #4CAF50; color: white;">
                        <th>Meter Number</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Filter Quantity</th>
                        <th>Order Status</th>
                        <th>Order Placed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are results
                    if ($result_orders->num_rows > 0) {
                        // Output data of each row
                        while($row = $result_orders->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["meter_number"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["phone"] . "</td>";
                            echo "<td>" . $row["filterQuantity"] . "</td>";
                            echo "<td>" . $row["orderStatus"] . "</td>";
                            echo "<td>" . $row["created_at"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No orders found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Maintenance Summary -->
        <div class="summary" style="position: absolute; width: 77%; background-color:#6f9ee8; left: 280px; padding:20px; top:550px;">
            <h3>Maintenance Summary</h3>
            <table border="1" cellspacing="0" cellpadding="10" style="width: 100%; background-color: #ffffff;">
                <thead>
                    <tr style="background-color: #4CAF50; color: white;">
                        <th>Name</th>
                        <th>Priority</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Completed Percentage</th>
                        <th>Status</th>
                        <th>Budget</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_task->num_rows > 0) {
                        while ($row_task = $result_task->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row_task["name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row_task["priority"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row_task["startDate"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row_task["endDate"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row_task["completedPercentage"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row_task["status"]) . "</td>";
                            echo "<td>" . "Rs " . htmlspecialchars($row_task["budget"]) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No tasks found for the logged-in user</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>


        <!--Time Slots-->
        <div class="summary" style="position: absolute; width: 77%; background-color:#6f9ee8; left: 280px; padding:20px; top:880px; ">
        <h3>Time slots </h3>
        <table border="1" cellspacing="0" cellpadding="10" style="width: 100%; background-color: #ffffff; " >
                <thead>
                    <tr style="background-color: #4CAF50; color: white;">
                        
                        <th>Location</th>
                        <th>Distribution Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Notes</th>
                        <th>Created at</th>
                        <th>Updated at	</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                        // Check if there are results
                        if ($result_timeslots->num_rows > 0) {
                            // Output data of each row
                            while($row = $result_timeslots->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["location"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["distributionDate"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["startTime"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["endTime"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["notes"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["updated_at"]) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No time slots found</td></tr>";
                        }
                        ?>
            </tbody>
            </table>
        </div>
    </div>
</div>
</body>
<footer class="text-center text-lg-start text-white" style="background: linear-gradient(108.9deg, rgb(18, 85, 150) 4.9%, rgb(100, 190, 150) 97%);font-family: Arial, sans-serif;">
    <section class="">
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
                    <p><i class="fas fa-home mr-3"></i>Colombo, Sri Lanka</p>
                    <p><i class="fas fa-envelope mr-3"></i> info@aqualink.lk</p>
                    <p><i class="fas fa-phone mr-3"></i> + 94 764 730 521</p>
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
// Close the statement and connection
$stmt_user->close();
$stmt_orders->close();
$stmt_task->close();
$stmt_bills->close();
$stmt_timeslots->close();
$conn->close();
?>