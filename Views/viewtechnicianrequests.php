<?php
session_start();
include 'db.php';

// Get the user ID from the session
$id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($id === null) {
    die("User not logged in or session expired."); // Error handling if user is not logged in
}

// Fetch requests assigned to the logged-in technician
$stmt = $conn->prepare("SELECT * FROM maintenance_requests WHERE assigned_technician = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$requests = $result->fetch_all(MYSQLI_ASSOC);

// Handle AJAX requests for details
if (isset($_POST['requestID']) && !isset($_POST['newStatus'])) {
    $requestID = $_POST['requestID'];
    $stmt = $conn->prepare("SELECT meter_number, phone, location, issue_description FROM maintenance_requests WHERE requestID = ? AND assigned_technician = ?");
    $stmt->bind_param("ii", $requestID, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode([]);
    }
    exit; // Important to stop further processing
}

// Handle status update
if (isset($_POST['requestID']) && isset($_POST['newStatus'])) {
    $requestID = $_POST['requestID'];
    $newStatus = $_POST['newStatus'];

    $stmt = $conn->prepare("UPDATE maintenance_requests SET requestStatus = ? WHERE requestID = ? AND assigned_technician = ?");
    $stmt->bind_param("sii", $newStatus, $requestID, $id);

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "error" => "Database query failed: " . $stmt->error]);
    } else {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "No rows updated."]);
        }
    }
    exit; // Important to stop further processing
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handle Requests</title>
    <link rel="stylesheet" type="text/css" href="../css/handlingrequests.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg">
        <img class="logopic" src="../images/logo.png" alt="Logo">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="aboutus.php">About us</a></li>
                <li class="nav-item"><a class="nav-link" href="contactus.php">Contact us</a></li>
                <li class="nav-item"><a class="nav-link" href="faq.php">F&Q</a></li>
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

<div class="wrapper">
    <nav id="sidebar">
        <ul class="list-unstyled components">
            <li><a href="technician.php" class="dashboard">Dashboard</a></li>
            <li><a href="#" class="dashboard">All</a></li>
            <li><a href="#" class="dashboard" id="maintenanceDetailsLink">Maintenance Details</a></li>
        </ul>
    </nav>

    <div id="content">
        <div class="search-bar-container">
        </div>

        <div class="tablecontainer">
            <table id="requestsTable">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Meter Number</th>
                        <th>Phone Number</th>
                        <th>View Details</th>
                        <th>Verify</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($requests as $request) : ?>
                    <tr>
                        <td>
                            <button class="btn btn-link request-id" data-request-id="<?php echo htmlspecialchars($request['requestID']); ?>">
                                <?php echo htmlspecialchars($request['requestID']); ?>
                            </button>
                        </td>
                        <td class="meter-number"><?php echo htmlspecialchars($request['meter_number']); ?></td>
                        <td class="phone-number"><?php echo htmlspecialchars($request['phone']); ?></td>
                        <td><button class="btn btn-info" onclick="viewDetails(<?php echo $request['requestID']; ?>)">Click to View</button></td>
                        <td>
                            <?php if ($request['requestStatus'] == 'Accepted') : ?>
                                <button class="btn btn-success" disabled>Accepted</button>
                            <?php elseif ($request['requestStatus'] == 'Denied') : ?>
                                <button class="btn btn-danger" disabled>Deny</button>
                            <?php else : ?>
                                <button class="btn btn-success accept-btn" data-request-id="<?php echo $request['requestID']; ?>">Accept</button>
                                <button class="btn btn-danger deny-btn" data-request-id="<?php echo $request['requestID']; ?>">Deny</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="detailsBox" class="details-box" style="display:none;">
            <h4>Maintenance Details</h4>
            <p><strong>Location:</strong> <span id="detailLocation"></span></p>
            <p><strong>Issue Description:</strong> <span id="detailDescription"></span></p>
        </div>
    </div>
</div>

<footer class="text-center text-lg-start text-white" style="background: linear-gradient(108.9deg, rgb(18, 85, 150) 4.9%, rgb(100, 190, 150) 97%);">
    <section>
        <div class="container text-center text-md-start mt-5">
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
                    <p><a href="help.php" class="text-white">Help</a></p>
                </div>
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <h6 class="text-uppercase fw-bold">Contact</h6>
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
    function searchOrder() {
        const requestID = $('#searchRequestID').val().toLowerCase(); // Convert to lowercase for case-insensitive search

        // Show all rows initially
        $('#requestsTable tbody tr').show();

        // If there is a request ID entered, filter the rows
        if (requestID) {
            $('#requestsTable tbody tr').filter(function() {
                // Check if the Request ID in this row matches the entered ID
                const rowRequestID = $(this).find('.request-id').text().toLowerCase();
                return rowRequestID.indexOf(requestID) === -1; // Hide rows that don't match
            }).hide();
        }
    }

    function refresh() {
        $('#searchRequestID').val(''); // Clear the input field
        $('#requestsTable tbody tr').show(); // Show all rows
    }

    function viewDetails(requestID) {
        $.ajax({
            url: 'viewtechnicianrequests.php', // Ensure this script can handle the request
            type: 'POST',
            data: { requestID: requestID },
            success: function (response) {
                const details = JSON.parse(response);
                if (details) {
                    $('#detailLocation').text(details.location); // Assuming your DB has a 'location' field
                    $('#detailDescription').text(details.issue_description); // Assuming your DB has an 'issue_description' field
                    $('#detailsBox').show();
                } else {
                    alert('No details found for this request.');
                }
            },
            error: function () {
                alert('Error fetching details. Please try again.');
            }
        });
    }


    $(document).on('click', '.accept-btn', function () {
        const requestID = $(this).data('request-id');
        const newStatus = 'Accepted'; // Set the new status to "Accepted"
        const button = $(this); // Store reference to the clicked button

        $.ajax({
            url: 'viewtechnicianrequests.php',
            type: 'POST',
            data: { requestID: requestID, newStatus: newStatus },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    alert(`Request ID ${requestID} has been accepted.`);

                    // Disable the accept button and change its text
                    button.prop('disabled', true);
                    button.text('Accepted');
                    
                    // Optionally, you can disable the deny button too
                    button.siblings('.deny-btn').prop('disabled', true);

                    // location.reload(); // You can comment this out if you don't want to refresh the page.
                } else {
                    alert('Failed to update status: ' + (result.error || 'Unknown error occurred.'));
                }
            },
            error: function () {
                alert('Error updating status. Please try again.');
            }
        });
    });


    $(document).on('click', '.deny-btn', function () {
        const requestID = $(this).data('request-id');
        const newStatus = 'Denied'; // Set the new status to "Denied"

        $.ajax({
            url: 'viewtechnicianrequests.php',
            type: 'POST',
            data: { requestID: requestID, newStatus: newStatus },
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    alert(`Request ID ${requestID} has been denied.`);
                    location.reload(); // Refresh to show updated status
                } else {
                    alert('Failed to update status: ' + (result.error || 'Unknown error occurred.'));
                }
            },
            error: function () {
                alert('Error updating status. Please try again.');
            }
        });
    });

</script>

</body>
</html>
