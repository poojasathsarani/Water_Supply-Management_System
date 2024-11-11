<?php
// Fetch the stats from the database using PHP
include('db.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize an array to hold the bills
$bills = array();

// Prepare the SQL query
$sql = "SELECT bill_id, service_provider_id, meter_number, name, billing_date, current_consumption, previous_consumption FROM bills";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Fetch results
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bills[] = $row;
    }
} else {
    echo "No records found";
}

$conn->close(); // Close the connection to the database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/GReport.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg">
    <img class="logopic" src="../images/logo.png">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
            <li class="nav-item"><a class="nav-link" href="aboutus.php">About us</a></li>
            <li class="nav-item"><a class="nav-link" href="contactus.php">Contact us</a></li>
            <li class="nav-item"><a class="nav-link" href="faq.php">F&Q</a></li>
        </ul>
    </div>
</nav>
</header>

<div class="wrapper">
    <nav id="sidebar" style="z-index:-1000;">
        <ul class="list-unstyled components">
            <li><a href="admin.php" class="dashboard">Dashboard</a></li>
            <li><a href="GenerateBill.php" class="scroll-link">Generate Bills</a></li>
        </ul>
    </nav>
    <div id="container">
        <div class="row">
            <div class="col-12">
                <div class="card margin-left: 250px;">
                    <div class="card-body">
                        <h3>Generate Bills</h3>

                        <!-- Add search input field and button -->
                        <div class="form-group row">
                            <label for="searchMeterNumber" class="col-sm-2 col-form-label">Meter Number</label>
                            <div class="col-sm-6">
                                <input type="text" id="searchMeterNumber" class="form-control" placeholder="Enter Meter Number">
                            </div>
                            <div class="col-sm-4">
                                <button class="btn btn-primary" onclick="filterByMeterNumber()">Search</button>
                            </div>
                        </div>

                        <!-- Table to display bills -->
                        <table class="table table-bordered" id="billsTable">
                            <thead>
                                <tr>
                                    <th>Service Provider</th>
                                    <th>Meter Number</th>
                                    <th>Consumer Name</th>
                                    <th>Billing Date</th>
                                    <th>Current Consumption (units)</th>
                                    <th>Previous Consumption (units)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be populated dynamically -->
                            </tbody>
                        </table>

                        <div id="billOutput" style="margin-top:20px;"></div>
                        <button type="button" class="btn btn-secondary" onclick="printBill()">Print Bill</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var data = <?php echo json_encode($bills); ?>; // Fetch PHP data to JavaScript

$(document).ready(function() {
    populateTable(data);
});

// Function to populate the bills table with data
function populateTable(bills) {
    var tableBody = $('#billsTable tbody');
    tableBody.empty();
    bills.forEach(function(bill) {
        var row = `<tr>
            <td>Service Provider ${bill.service_provider_id}</td>
            <td>${bill.meter_number}</td>
            <td>${bill.name}</td>
            <td>${bill.billing_date}</td>
            <td>${bill.current_consumption}</td>
            <td>${bill.previous_consumption}</td>
            <td><button class="btn btn-info" onclick="generateBill('${bill.meter_number}')">Generate Bill</button></td>
        </tr>`;
        tableBody.append(row);
    });
}

// Function to filter by meter number
function filterByMeterNumber() {
    var meterNumber = $('#searchMeterNumber').val().trim();
    if (meterNumber === '') {
        alert('Please enter a meter number.');
        return;
    }

    // Filter the data based on the meter number
    var filteredData = data.filter(b => b.meter_number.includes(meterNumber));
    populateTable(filteredData);
}

// Function to generate the bill
window.generateBill = function(meterNumber) {
    var bill = data.find(b => b.meter_number == meterNumber);
    if (!bill) {
        alert('Bill not found for meter number: ' + meterNumber);
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
        { maxUnits: 10, rate: 100.00 },   // $1.00 per unit for first 10 units
        { maxUnits: 20, rate: 200.00 },   // $2.00 per unit for next 10 units
        { maxUnits: 30, rate: 250.00 },   // $2.50 per unit for next 10 units
        { maxUnits: 71, rate: 300.00 },   // $3.00 per unit for next 41 units
        { maxUnits: Infinity, rate: 400.00 } // $4.00 per unit for consumption above 71 units
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

    // Generate the bill details to display on the page
    var billData = `
        <div style="border: 1px solid #000; padding: 20px;">
            <div style="text-align: center;">
                <img src="../images/logo.png" alt="Company Logo" style="width: 100px;">
                <h2>Water Supply Management System</h2>
            </div>
            <p><strong>Bill ID:</strong> ${bill.bill_id}</p>
            <p><strong>Service Provider:</strong> Service Provider ${bill.service_provider_id}</p>
            <p><strong>Account Number:</strong> ${bill.meter_number}</p>
            <p><strong>Consumer Name:</strong> ${bill.name}</p>
            <p><strong>Billing Date:</strong> ${bill.billing_date}</p>
            <p><strong>Previous Consumption:</strong> ${bill.previous_consumption} units</p>
            <p><strong>Current Consumption:</strong> ${bill.current_consumption} units</p>
            <p><strong>Consumption:</strong> ${consumption} units</p>
            <p><strong>Total Amount:</strong> Rs. ${totalAmount.toFixed(2)}</p>
            <p>Thank you for your service!</p>
        </div>
    `;
    $('#billOutput').html(billData);

    // AJAX request to save the total amount in the database
    $.ajax({
        url: 'update_bill.php',
        type: 'POST',
        dataType: 'json',
        data: {
            meter_number: bill.meter_number,
            total_amount: totalAmount
        },
        success: function(response) {
            if (response.success) {
                alert('Bill generated and saved successfully!');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
            alert('Failed to save the bill. Error: ' + error);
        }
    });
};


// Function to print the bill
window.printBill = function() {
    if ($('#billOutput').html() === '') {
        alert('No bill generated to print!');
        return;
    }
    
    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print Bill</title></head><body>');
    printWindow.document.write($('#billOutput').html());
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
};
</script>
</body>
</html>
