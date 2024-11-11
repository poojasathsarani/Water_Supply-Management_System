<?php
session_start();
include 'db.php';

// Get the user ID from the session
$serviceProviderID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($serviceProviderID === null) {
    die("User not logged in or session expired."); // Error handling if user is not logged in
}

// Initialize arrays
$requests = array();
$technicians = array();

// Fetch requests from the database, including the assigned_technician column
$sql = "SELECT requestID, meter_number, phone, location, issue_description, requestStatus, assigned_technician 
        FROM maintenance_requests";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
} else {
    die("Error fetching requests: " . $conn->error);
}

// Fetch all technicians from the users table
$sqlTechnician = "SELECT id, name FROM users WHERE role = 'technician'";
$resultTechnician = $conn->query($sqlTechnician);
if ($resultTechnician && $resultTechnician->num_rows > 0) {
    while ($row = $resultTechnician->fetch_assoc()) {
        $technicians[] = $row;
    }
} else {
    die("Error fetching technicians: " . $conn->error);
}

// Handle the AJAX request to update the assigned technician
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['requestID']) && isset($_POST['id'])) {
    $requestID = intval($_POST['requestID']);
    $id = intval($_POST['id']);

    // Log received data
    error_log("Received data: requestID = $requestID, id = $id");

    // Update the assigned_technician in the database
    $updateSql = "UPDATE maintenance_requests SET assigned_technician = ? WHERE requestID = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param('ii', $id, $requestID);

    if ($stmt->execute()) {
        echo "Technician assigned successfully!";
    } else {
        echo "Error assigning technician: " . $conn->error;
    }

    $stmt->close();
    exit;
}

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handle Requests</title>
    <link rel="stylesheet" type="text/css" href="../css/handlingrequests.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg">
        <img class="logopic" src="../images/logo.png" alt="Logo">
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
            <li>
                <a href="" class="dashboard">All</a>
            </li>
            <li>
                <a href="#" class="dashboard" id="maintenanceDetailsLink">Maintenance Details</a>
            </li>
        </ul>
    </nav>

    <div id="content">
    <div class="search-bar-container">
            <input class="form-control" id="searchRequestID" type="text" placeholder="Search by Request ID">
            <button class="btn btn-primary" onclick="searchOrder()">Search</button>
            <button class="btn btn-primary" onclick="refresh()">Refresh</button>
        </div>
        <div class="tablecontainer">
            <table id="requestsTable">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Meter Number</th>
                        <th>Phone Number</th>
                        <th>View Details</th>
                        <th>Technician</th>
                        <th>Action</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['requestID']); ?></td>
                        <td><?php echo htmlspecialchars($request['meter_number']); ?></td>
                        <td><?php echo htmlspecialchars($request['phone']); ?></td>
                        <td><button class="btn btn-info" onclick="viewDetails(<?php echo $request['requestID']; ?>)">Click to View</button></td>
                        
                        <!-- Disable technician dropdown and button if status is 'verify' -->
                        <td>
                        <select class="form-control technician-select" data-request-id="<?php echo $request['requestID']; ?>" <?php echo ($request['requestStatus'] == 'Verify') ? 'disabled' : ''; ?>>
                            <option value="">Select Technician</option>
                            <?php foreach ($technicians as $technician) : ?>
                                <option value="<?php echo $technician['id']; ?>"><?php echo $technician['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        </td>

                        <td>
                            <button class="btn btn-success" 
                                    onclick="sendRequest(<?php echo $request['requestID']; ?>)" 
                                    <?php echo ($request['requestStatus'] == 'Verify') ? 'disabled' : ''; ?>>
                                Send
                            </button>
                        </td>
                        
                        <td>
                            <select class="form-control request-status" data-request-id="<?php echo $request['requestID']; ?>">
                                <option value="Pending" <?php echo ($request['requestStatus'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Completed" <?php echo ($request['requestStatus'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Details Box -->
        <div id="detailsBox" class="details-box">
            <h4>Maintenance Details</h4>
            <p><strong>Location:</strong> <span id="detailLocation"></span></p>
            <p><strong>Issue Description:</strong> <span id="detailDescription"></span></p>
        </div>
    </div>
</div>

<footer class="text-center text-lg-start text-white" style="background: linear-gradient(108.9deg, rgb(18, 85, 150) 4.9%, rgb(100, 190, 150) 97%);">
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
                    <p><i class="fas fa-envelope mr-3"></i>info@example.com</p>
                    <p><i class="fas fa-phone mr-3"></i> + 94 123 456 789</p>
                    <p><i class="fas fa-print mr-3"></i> + 94 987 654 321</p>
                </div>
            </div>
            <div class="text-center p-3" style="height: 50px;">
                © 2024 Copyright: <a class="text-white" href="">aqualink.lk</a>
            </div>
        </div>
    </section>
</footer>

<script>
    $(document).ready(function() {
        // Fetch request data from PHP
        var requests = <?php echo json_encode($requests); ?>;
        var technicians = <?php echo json_encode($technicians); ?>;

        // Function to populate the table
        window.populateTable = function(data) {
            var tableBody = $('#requestsTable tbody');
            tableBody.empty();

            data.forEach(function(request) {
                var technicianOptions = technicians.map(function(technician) {
                    // Pre-select the technician if assigned
                    return `<option value="${technician.id}" ${request.assigned_technician == technician.id ? 'selected' : ''}>${technician.name}</option>`;
                }).join('');

                var isDisabled = request.requestStatus === 'Verify' || request.requestStatus === 'Accepted' ? 'disabled' : '';

                var row = `<tr>
                    <td>${request.requestID}</td>
                    <td>${request.meter_number}</td>
                    <td>${request.phone}</td>
                    <td><button class="btn btn-info" onclick="viewDetails(${request.requestID})">Click to view</button></td>
                    <td>
                        <select class="form-control technician-select" data-request-id="${request.requestID}" ${isDisabled}>
                            <option value="">Select Technician</option>
                            ${technicianOptions}
                        </select>
                    </td>
                    <td><button class="btn btn-success" onclick="sendRequest(${request.requestID})" ${isDisabled}>Send</button></td>
                    <td>${request.requestStatus}</td>
                </tr>`;
                tableBody.append(row);
            });
        };



        // Populate the table on page load
        populateTable(requests);

        // Function to handle the View Details button click
        window.viewDetails = function(requestID) {
            var request = requests.find(function(req) {
                return req.requestID == requestID;
            });

            if (request) {
                $('#detailDate').text(request.datePeriodFrom + ' to ' + request.datePeriodTo);
                $('#detailLocation').text(request.location);
                $('#detailDescription').text(request.issue_description);

                // Scroll to the details box
                $('html, body').animate({
                    scrollTop: $("#detailsBox").offset().top
                }, 1000);
            }
        };

        // Function to handle the Send button
        window.sendRequest = function(requestID) {
            var id = $(`.technician-select[data-request-id='${requestID}']`).val(); // Get selected technician ID

            if (id) { // Check if a technician is selected
                console.log("Sending request to assign technician:", { requestID: requestID, id: id }); // Debug log
                $.ajax({
                    type: "POST",
                    url: "handlingrequests.php", // URL of the PHP script to handle update
                    data: {
                        requestID: requestID, // Request ID to be updated
                        id: id // Selected Technician ID
                    },
                    success: function(response) {
                        console.log("Server response:", response); // Debug log
                        alert('Technician assigned successfully!');

                        // Update the `requests` array with the new technician assignment
                        requests.forEach(function(request) {
                            if (request.requestID == requestID) {
                                request.assigned_technician = id; // Update the assigned_technician ID
                            }
                        });

                        // Re-populate the table with updated data
                        populateTable(requests);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error);
                        alert('Error assigning the technician.');
                    }
                });
            } else {
                alert('Please select a technician before sending.');
            }
        };




        // Search and refresh functions
        window.searchOrder = function() {
            var searchOrderID = $('#searchRequestID').val().trim();
            if (searchOrderID) {
                var filteredData = requests.filter(function(order) {
                    return order.requestID == searchOrderID;
                });
                populateTable(filteredData);
            } else {
                populateTable(requests);
            }
        };

        window.refresh = function() {
            $('#searchRequestID').val('');
            populateTable(requests);
        };
    });
</script>
</body>
</html>