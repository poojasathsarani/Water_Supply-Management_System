<?php
session_start();
include 'db.php';

// Get the user ID from the session
$serviceProviderID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($serviceProviderID === null) {
    die("User not logged in or session expired."); // Error handling if user is not logged in
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if deleteSlot data is posted
    if (isset($_POST['deleteSlot'])) {
        $slotData = json_decode($_POST['deleteSlot'], true);
        $slotID = $slotData['slotID'] ?? null;

        if ($slotID !== null) {
            // Prepare the SQL statement for deletion
            $stmt = $conn->prepare("DELETE FROM timeSlots WHERE timeSlotID = ? AND serviceProviderID = ?");
            $stmt->bind_param("ii", $slotID, $serviceProviderID);

            // Execute the deletion and check for errors
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }

            // Close the statement
            $stmt->close();
            exit();
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid slot ID']);
            exit();
        }
    }

    // Check if timeSlot data is posted
    if (isset($_POST['timeSlot'])) {
        $timeSlotData = json_decode($_POST['timeSlot'], true);

        // Extract the data
        $slotID = $timeSlotData['timeSlotID'] ?? null; // If updating, this will be set
        $location = $timeSlotData['location'];
        $distributionDate = $timeSlotData['distributionDate'];
        $startTime = $timeSlotData['startTime'];
        $endTime = $timeSlotData['endTime'];
        $notes = $timeSlotData['notes'];

        if ($slotID) {
            // Update existing time slot
            $stmt = $conn->prepare("UPDATE timeSlots SET location = ?, distributionDate = ?, startTime = ?, endTime = ?, notes = ? WHERE timeSlotID = ? AND serviceProviderID = ?");
            $stmt->bind_param("sssssii", $location, $distributionDate, $startTime, $endTime, $notes, $slotID, $serviceProviderID);
        } else {
            // Insert new time slot
            $stmt = $conn->prepare("INSERT INTO timeSlots (serviceProviderID, location, distributionDate, startTime, endTime, notes) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $serviceProviderID, $location, $distributionDate, $startTime, $endTime, $notes);
        }

        // Execute the insertion/updating and check for errors
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        // Close the statement
        $stmt->close();
        exit();
    }
}

// Fetch existing time slots from the database
$existingSlots = [];
$stmt = $conn->prepare("SELECT * FROM timeSlots WHERE serviceProviderID = ?");
$stmt->bind_param("i", $serviceProviderID);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $existingSlots = $result->fetch_all(MYSQLI_ASSOC); // Store results in an associative array
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Time Slots</title>
    <link rel="stylesheet" type="text/css" href="../css/viewTimeSlots.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg">
        <img class="logopic" src="../images/logo.png">
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

<!-- sidebar -->
<div class="wrapper">
    <nav id="sidebar">
        <ul class="list-unstyled components">
            <li><a href="serviceprovider.php" class="dashboard">Dashboard</a></li>
        </ul>
    </nav>

    <div class="content" id="content">
        <!-- Time Slots Table -->
        <div class="tablecontainer">
            <table id="timeSlotsTable" class="table table-bordered">
            <h2>Available Time Slots</h2>
                <thead>
                    <tr>
                        <th>Service Provider ID</th>
                        <th>Location</th>
                        <th>Distribution Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($existingSlots) && count($existingSlots) > 0): ?>
                        <?php foreach ($existingSlots as $slot): ?>
                            <tr data-timeslot-id="<?php echo $slot['timeSlotID']; ?>">
                                <td><input type="number" class="form-control serviceProviderID" value="<?php echo $slot['serviceProviderID']; ?>" readonly></td>
                                <td><input type="text" class="form-control location" value="<?php echo htmlspecialchars($slot['location']); ?>"></td>
                                <td><input type="date" class="form-control distributionDate" value="<?php echo $slot['distributionDate']; ?>"></td>
                                <td><input type="time" class="form-control startTime" value="<?php echo $slot['startTime']; ?>"></td>
                                <td><input type="time" class="form-control endTime" value="<?php echo $slot['endTime']; ?>"></td>
                                <td><input type="text" class="form-control notes" value="<?php echo htmlspecialchars($slot['notes']); ?>"></td>
                                <td>
                                    <button class="btn btn-primary saveBtn">Save</button>
                                    <button class="btn btn-danger removeBtn">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No time slots available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <button class="btn btn-success" id="addRowBtn">Add Row</button>
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
    $(document).ready(function() {
        // Add row button functionality
        $('#addRowBtn').on('click', function() {
            var newRow = `<tr>
                <td><input type="number" class="form-control serviceProviderID" value="<?php echo $serviceProviderID; ?>" readonly></td>
                <td><input type="text" class="form-control location" placeholder="Enter Location"></td>
                <td><input type="date" class="form-control distributionDate"></td>
                <td><input type="time" class="form-control startTime"></td>
                <td><input type="time" class="form-control endTime"></td>
                <td><input type="text" class="form-control notes" placeholder="Enter Notes"></td>
                <td>
                    <button class="btn btn-primary saveBtn">Save</button>
                    <button class="btn btn-danger removeBtn">Remove</button>
                </td>
            </tr>`;
            $('#timeSlotsTable tbody').append(newRow);
        });

        // Save button functionality
        $(document).on('click', '.saveBtn', function() {
            var row = $(this).closest('tr');
            var serviceProviderID = row.find('.serviceProviderID').val();
            var location = row.find('.location').val();
            var distributionDate = row.find('.distributionDate').val();
            var startTime = row.find('.startTime').val();
            var endTime = row.find('.endTime').val();
            var notes = row.find('.notes').val();

            // Check for empty values
            if (!serviceProviderID || !location || !distributionDate || !startTime || !endTime) {
                alert("Can't store empty values!");
                return; // Exit the function if validation fails
            }

            var timeSlot = {
                timeSlotID: row.data('timeslot-id'), // Add this line
                serviceProviderID: serviceProviderID,
                location: location,
                distributionDate: distributionDate,
                startTime: startTime,
                endTime: endTime,
                notes: notes
            };

            $.ajax({
                url: 'viewTimeSlots.php',
                type: 'POST',
                data: { timeSlot: JSON.stringify(timeSlot) },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        alert("Time slot saved successfully.");
                    } else {
                        alert("Failed to save time slot: " + (data.error || 'Unknown error.'));
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + error);
                }
            });
        });

        // Remove button functionality
        $(document).on('click', '.removeBtn', function() {
            var row = $(this).closest('tr');
            var slotID = row.data('timeslot-id'); // Make sure this matches the data attribute used in your HTML

            if (!slotID) {
                alert("Cannot delete unsaved slot. Please refresh the page.");
                return;
            }

            if (confirm("Are you sure you want to delete this time slot?")) {
                $.ajax({
                    url: 'viewTimeSlots.php',
                    type: 'POST',
                    data: { deleteSlot: JSON.stringify({ slotID: slotID }) },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            alert("Time slot deleted successfully.");
                            row.remove(); // Remove the row from the table
                        } else {
                            alert("Failed to delete time slot.");
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred: " + error);
                    }
                });
            }
        });
    });
</script>
</body>
</html>
