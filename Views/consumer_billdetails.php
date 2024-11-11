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
$user_id= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id === null) {
    die("User not logged in or session expired."); // Error handling if user is not logged in
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Consumer View </title>
    <link rel="stylesheet" href="../css/concumer_feedback.css">


    
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
    <nav id="sidebar" style=" height:1200px;">
        <div class="sidebar-header">
            <h3>Water Management</h3>
        </div>
        <ul class="list-unstyled components">
            <li>
                <a href="consumer.php" class="dashboard">Dashboard</a>
            </li>
          
        </ul>
    </nav>
    <div class="dashboard" style="display: flex; flex-direction: column; gap: 20px; margin: 20px;">
        

      
<?php
$sql = "
SELECT  b.meter_number, b.name, b.billing_date, 
       b.current_consumption, b.previous_consumption,b.this_month_bill, b.billingStatus 
FROM bills b
INNER JOIN consumer c ON b.meter_number = c.meter_number
WHERE c.id = ?
";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing SQL statement: " . $conn->error);
}

// Bind the user_id (from the session) to the query
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>







<div class="summary" style="position: absolute; width: 77%; background-color:#6f9ee8; left: 280px; padding:20px; top:300px; ">
<h3>Bill Summary</h3>

<table border="1" cellspacing="0" cellpadding="10" style="width: 100%; background-color: #ffffff; " >
        <thead>
            <tr style="background-color: #4CAF50; color: white;">
                
                <th>Meter Number</th>
                <th>Consumer Name</th>
                <th>Billing Date</th>
                <th>Current Consumption (units)</th>
                <th>Previous Consumption (units)</th>
                <th>Billing Status</th>
                <th>This month Bill</th>
                <th>Action </th>
            </tr>
        </thead>
        <tbody>
        <?php
            // Check if there are results
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["meter_number"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["billing_date"] . "</td>";
                    echo "<td>" . $row["current_consumption"] . "</td>";
                    echo "<td>" . $row["previous_consumption"] . "</td>";
                    echo "<td>" . $row["this_month_bill"] . "</td>";
                    echo "<td>" . $row["billingStatus"] . "</td>";
                    echo "<td><button class='btn btn-info' onclick='generateBill(" . json_encode($row) . ")'>Generate Bill</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No bills found</td></tr>";
            }
        ?>
        </tbody>
        </table>
</div>
<div id="billOutput" style="margin-top: 600px;width:400px;"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function fetchBillDetails() {
    // Use AJAX to fetch bill details from the server (all bills)
    $.ajax({
        url: window.location.href,
        method: 'POST',
        success: function(response) {
            console.log("Response from server: ", response);

            try {
                var data = JSON.parse(response);
                
                if (data.error) {
                    alert(data.error);
                } else {
                    populateTable(data);
                    window.billData = data; // Store the bill data globally
                }
            } catch (e) {
                
            }
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error);
        }
    });
}

function populateTable(bills) {
    var tableBody = $('#billsTable tbody');
    tableBody.empty(); // Clear the table

    // Loop through all bills and populate the table
    bills.forEach(function(bill) {
        var row = `<tr>
            <td>${bill.name}</td>
            <td>${bill.billing_date}</td>
            <td>${bill.current_consumption}</td>
            <td>${bill.previous_consumption}</td>
            <td>${bill.billingStatus}</td>
            <td>${bill.this_month_bill}</td>
            <td><button class="btn btn-info" onclick="generateBill(${JSON.stringify(bill)})">Generate Bill</button></td>
        </tr>`;
        tableBody.append(row);
    });
}

// Function to generate the bill based on the bill data
window.generateBill = function(bill) {
    // Calculate the bill details
    var consumption = bill.current_consumption - bill.previous_consumption;
    if (consumption < 0) {
        alert('Invalid consumption data.');
        return;
    }
    
    // Calculate the consumption for the month
    var consumption = bill.current_consumption - bill.previous_consumption;

    // Ensure consumption is not negative
    if (consumption < 0) {
        alert('Error: Current consumption is less than previous consumption.');
        return;
    }

    // Define rate tiers (configurable)
    var rateTiers = [
        { maxUnits: 10, rate: 20.00 },   // $1.00 per unit for first 10 units
        { maxUnits: 20, rate: 40.00 },   // $2.00 per unit for next 10 units
        { maxUnits: 30, rate: 60.00 },   // $2.50 per unit for next 10 units
        { maxUnits: 71, rate: 80.00 },   // $3.00 per unit for next 41 units
        { maxUnits: Infinity, rate: 100.00 } // $4.00 per unit for consumption above 71 units
    ];

    // Calculate the totalAmount dynamically
    var totalAmount = 0;
    var remainingConsumption = consumption;
    for (let i = 0; i < rateTiers.length && remainingConsumption > 0; i++) {
        let tier = rateTiers[i];
        let unitsInTier = Math.min(remainingConsumption, tier.maxUnits - (rateTiers[i - 1]?.maxUnits || 0));
        totalAmount += unitsInTier * tier.rate;
        remainingConsumption -= unitsInTier;
    }

    // Display the bill details and calculation
    var billData = `
        <div style="border: 1px solid #000; padding: 20px;">
            <div style="text-align: center;">
                <img src="../images/logo.png" alt="Company Logo" style="width: 100px;">
                <h2>Water Supply Management System</h2>
            </div>
            
            <p><strong>Account Number:</strong> ${bill.meter_number}</p>
            <p><strong>Consumer Name:</strong> ${bill.name}</p>
            <p><strong>Billing Date:</strong> ${bill.billing_date}</p>
            <p><strong>Previous Consumption:</strong> ${bill.previous_consumption} units</p>
            <p><strong>Current Consumption:</strong> ${bill.current_consumption} units</p>
            <p><strong>Consumption:</strong> ${consumption} units</p>
            <p><strong>Total Amount:</strong> $${totalAmount.toFixed(2)}</p>
            <p>Thank you for your service!</p>
        </div>
    `;
    $('#billOutput').html(billData);
}

// Fetch all bills on page load
$(document).ready(function() {
    fetchBillDetails(); // Fetch all bills automatically
});
</script>





</div>
<footer class="footer" style=" top:1300px; margin-top: 1300px; width:100%; left:-50px;">
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

</body>
</html>
<?php
// Close the connection
$conn->close();
?>
