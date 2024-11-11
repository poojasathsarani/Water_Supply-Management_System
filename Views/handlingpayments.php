<?php
session_start();
include 'db.php';

// Get the user ID from the session
$serviceProviderID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($serviceProviderID === null) {
    die("User not logged in or session expired."); // Error handling if user is not logged in
}

// Check if the request is to update the billing status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateBillingStatus'])) {
    $bill_id = $_POST['bill_id'];
    $billingStatus = $_POST['billingStatus'];

    // Update billing status in the database
    $sql = "UPDATE bills SET billingStatus = ? WHERE bill_id = ? AND service_provider_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $billingStatus, $bill_id, $serviceProviderID); // Use $serviceProviderID here

    if ($stmt->execute()) {
        echo "Billing status updated successfully.";
    } else {
        echo "Error updating billing status: " . $conn->error;
    }

    $stmt->close();
    exit; // End the script after handling the AJAX request
}

// Retrieve data from the bills table where service_provider_id matches the logged-in service provider
$sql = "SELECT * FROM bills WHERE service_provider_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $serviceProviderID); // Use $serviceProviderID here
$stmt->execute();
$result = $stmt->get_result();

// Fetch the data as an associative array and encode it as JSON for JavaScript
$bills_data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bills_data[] = $row;
    }
}

// Convert bills data to JavaScript variable
echo "<script>var billsData = " . json_encode($bills_data) . ";</script>";

$stmt->close();
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Handle Payments</title>
<link rel="stylesheet" type="text/css" href="../css/handlingpayments.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg">
    <img class="logopic" src="../images/logo.png">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Home <span class="sr-only">(current)</span></a>
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
    <form>
        <input class="form-control me-2" type="search" placeholder="Search here" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
</div>
</header>
<!-- sidebar -->
<div class="wrapper">
    <nav id="sidebar">
        <ul class="list-unstyled components">
            <li>
                <a href="serviceprovider.php" class="dashboard">Dashboard</a>
            </li>
        </ul>
    </nav>

    <div id="content">
        <div class="search-bar-container">
            <input class="form-control" id="searchBillID" type="text" placeholder="Search by Bill ID">
            <button class="btn btn-primary" onclick="searchBill()">Search</button>
            <button class="btn btn-primary" onclick="refresh()">Refresh</button>
        </div>

        <div class="tablecontainer">
          <table id="billTable" class="table table-bordered">
            <thead>
              <tr>
                <th>Bill ID</th>
                <th>Meter Number</th>
                <th>Consumer Name</th>
                <th>Billing Date</th>
                <th>This Month Bill (RS.)</th>
                <th>Bill Status</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
    </div>
</div>

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



<script>
document.addEventListener("DOMContentLoaded", function() {
    var originalBillsData = billsData; // Save a copy of the original bills data

    // Populate the table with data when the DOM is fully loaded
    populateTable(billsData);

    // Function to populate the table with data
    function populateTable(data) {
        var tableBody = document.querySelector("#billTable tbody");
        tableBody.innerHTML = ""; // Clear existing rows

        data.forEach(function(bill) {
            var row = document.createElement("tr");

            // Create cells for each piece of data
            var billIdCell = document.createElement("td");
            billIdCell.textContent = bill.bill_id;
            row.appendChild(billIdCell);

            var meterNumberCell = document.createElement("td");
            meterNumberCell.textContent = bill.meter_number;
            row.appendChild(meterNumberCell);

            var consumerNameCell = document.createElement("td");
            consumerNameCell.textContent = bill.name;
            row.appendChild(consumerNameCell);

            var billingDateCell = document.createElement("td");
            billingDateCell.textContent = bill.billing_date;
            row.appendChild(billingDateCell);

            var thisMonthBillCell = document.createElement("td");
            thisMonthBillCell.textContent = + bill.this_month_bill;
            row.appendChild(thisMonthBillCell);

            var billStatusCell = document.createElement("td");

            // Create buttons for billing status
            var paidButton = createStatusButton("Paid", "btn-success", bill.bill_id, bill.billingStatus);
            var unpaidButton = createStatusButton("Unpaid", "btn-danger", bill.bill_id, bill.billingStatus);
            var pendingButton = createStatusButton("Pending", "btn-warning", bill.bill_id, bill.billingStatus);

            // Append the buttons to the cell
            billStatusCell.appendChild(paidButton);
            billStatusCell.appendChild(unpaidButton);
            billStatusCell.appendChild(pendingButton);

            // Append the cell to the row
            row.appendChild(billStatusCell);
            tableBody.appendChild(row);
        });
    }

    // Helper function to create a status button
    function createStatusButton(status, btnClass, billId, currentStatus) {
        var button = document.createElement("button");
        button.textContent = status;
        button.className = "btn " + btnClass;

        // Disable "Unpaid" and "Pending" buttons if the bill's current status is "Paid"
        if (currentStatus === "Paid") {
            if (status === "Unpaid" || status === "Pending") {
                button.disabled = true;
            }
        } else {
            button.onclick = function() { updateBillingStatus(billId, status); }; // Bind click event to update status
        }

        return button;
    }

    // Function to update billing status
    function updateBillingStatus(billId, status) {
        $.ajax({
            url: 'handlingpayments.php',
            method: 'POST',
            data: {
                updateBillingStatus: true,
                bill_id: billId,
                billingStatus: status
            },
            success: function(response) {
                alert('Billing status updated to ' + status);
                refresh(); // Refresh table data
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
                alert('Failed to update billing status.');
            }
        });
    }

    // Function to search for a specific Bill ID
    window.searchBill = function() {
        var searchValue = document.getElementById("searchBillID").value.toLowerCase();
        var filteredData = originalBillsData.filter(function(bill) {
            return bill.bill_id.toString().toLowerCase().includes(searchValue);
        });
        populateTable(filteredData); // Populate the table with filtered data
    }

    // Function to refresh the table
    window.refresh = function() {
        populateTable(originalBillsData); // Populate the table with original data
    }
});
</script>
</body>
</html>