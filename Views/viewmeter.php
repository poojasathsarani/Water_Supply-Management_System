<?php
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch all data from the 'bills' table
$sql = "SELECT * FROM bills";
$result = $conn->query($sql);

$response = [];
// Check if the query returns any rows
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    $response = []; // Return empty array if no results
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Bills</title>
<link rel="stylesheet" type="text/css" href="../css/viewmeter.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg">
    <img class="logopic" src="../images/logo.png">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Home</a>
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
    <form onsubmit="return false;">
        <input class="form-control me-2" id="meterIdInput" type="search" placeholder="Search by Meter ID" aria-label="Search">
        <button class="btn btn-outline-success" type="button" onclick="searchByMeterId()">Search</button>
    </form>
</div>
</header>

<div class="wrapper">
    <nav id="sidebar">
        <ul class="list-unstyled components">
            <li>
                <a href="updatemeter.php" class="dashboard">Dashboard</a>
            </li>
            <li>
                <a href="warningmeter.php" class="dashboard">Warnings</a>
            </li>
            <li>
                <a href="meterprofile.php" class="dashboard">Profile Management</a>
            </li>
        </ul>
    </nav>

    <div id="content">
        <div class="tablecontainer">
            <h2>Bill Data</h2>
            <table id="requestsTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Service Provider ID</th>
                        <th>Meter Number</th>
                        <th>Name</th>
                        <th>Billing Date</th>
                        <th>Current Consumption</th>
                        <th>Previous Consumption</th>
                        <th>This Month's Bill</th>
                        <th>Billing Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be inserted here by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Parse the PHP data into a JavaScript variable
const billsData = <?php echo json_encode($response); ?>;

document.addEventListener("DOMContentLoaded", function() {
    const tableBody = document.querySelector('#requestsTable tbody');
    if (billsData.length > 0) {
        billsData.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.bill_id}</td>
                <td>${row.service_provider_id}</td>
                <td>${row.meter_number}</td>
                <td>${row.name}</td>
                <td>${row.billing_date}</td>
                <td>${row.current_consumption}</td>
                <td>${row.previous_consumption}</td>
                <td>${row.this_month_bill}</td>
                <td>${row.billingStatus}</td>
            `;
            tableBody.appendChild(tr);
        });
    } else {
        tableBody.innerHTML = '<tr><td colspan="9">No data found</td></tr>';
    }
});

// Search function for filtering by Meter Number
function searchByMeterId() {
    const meterId = document.getElementById('meterIdInput').value.toLowerCase();
    const tableBody = document.querySelector('#requestsTable tbody');
    tableBody.innerHTML = ''; // Clear previous results

    const filteredData = billsData.filter(row => row.meter_number.toLowerCase().includes(meterId));
    
    if (filteredData.length > 0) {
        filteredData.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.bill_id}</td>
                <td>${row.service_provider_id}</td>
                <td>${row.meter_number}</td>
                <td>${row.name}</td>
                <td>${row.billing_date}</td>
                <td>${row.current_consumption}</td>
                <td>${row.previous_consumption}</td>
                <td>${row.this_month_bill}</td>
                <td>${row.billingStatus}</td>
            `;
            tableBody.appendChild(tr);
        });
    } else {
        tableBody.innerHTML = '<tr><td colspan="9">No data found</td></tr>';
    }
}
</script>

<footer class="text-center text-lg-start text-white" style="background: linear-gradient(108.9deg, rgb(18, 85, 150) 4.9%, rgb(100, 190, 150) 97%);">
    <section class="text-center text-md-start mt-5">
        <div class="container">
            <div class="row mt-3">
                <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">AQUA LINK</h6>
                    <p>The Water Supply Management System aims to revolutionize the traditional manual processes of water administration in rural areas...</p>
                </div>

                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">Useful links</h6>
                    <p><a href="#!" class="text-white">My Account</a></p>
                    <p><a href="annualreports.php" class="text-white">Annual Reports</a></p>
                    <p><a href="customerservices.php" class="text-white">Customer Services</a></p>
                </div>

                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <h6 class="text-uppercase fw-bold">Contact</h6>
                    <p><i class="fas fa-home mr-3"></i> Colombo, Sri Lanka</p>
                    <p><i class="fas fa-envelope mr-3"></i> info@example.com</p>
                </div>
            </div>
        </div>
    </section>
</footer>
</body>
</html>
