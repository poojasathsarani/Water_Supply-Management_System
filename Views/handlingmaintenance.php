<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['taskID'])) {
        $taskID = $_POST['taskID'];

        // Update query to set taskStatus to "Done"
        $sql = "UPDATE updatetask SET done = 1 WHERE taskID = $taskID";

        if ($conn->query($sql) === TRUE) {
            echo "Task status updated successfully";
        } else {
            echo "Error updating task status: " . $conn->error;
        }
    }
    exit; // End the script after processing the POST request
}

// Fetch tasks to display in the table
$sql = "SELECT taskID,name, priority, startDate, endDate, completedPercentage, status, budget, notes, done FROM updatetask";
$result = $conn->query($sql);

$tasks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handling Maintenance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/handlingmaintenance.css">
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

<!-- Maintenance Tasks Table -->
<div class="table-container">
    <table id="maintenanceTable" class="table table-bordered">
    <h2>Maintenance Tasks</h2>
        <thead>
            <tr>
                <th>Task ID</th>
                <th>Name</th>
                <th>Priority</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>% Completed</th>
                <th>Status</th>
                <th>Budget</th>
                <th>Notes</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= $task['taskID']; ?></td>
                    <td><?= $task['name']; ?></td>
                    <td><?= $task['priority']; ?></td>
                    <td><?= $task['startDate']; ?></td>
                    <td><?= $task['endDate']; ?></td>
                    <td><?= $task['completedPercentage']; ?>%</td>
                    <td><?= $task['status']; ?></td>
                    <td><?= $task['budget']; ?></td>
                    <td><?= $task['notes']; ?></td>
                    <td>
                        <button class="btn btn-success mark-done" data-task-id="<?= $task['taskID']; ?>" <?= $task['done'] ? 'disabled' : ''; ?>>
                            <?= $task['done'] ? 'Completed' : 'Ok'; ?>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<a href="serviceprovider.php">
    <button style="margin-top:-200px; position: absolute; left: 20px; background-color:blue; width:170px; height:60px;border-radius:30px;">Dashboard</button>
</a>
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
        $(document).on('click', '.mark-done', function() {
            var taskID = $(this).data('task-id');
            var button = $(this);

            $.ajax({
                type: "POST",
                url: "handlingmaintenance.php",
                data: {
                    taskID: taskID
                },
                success: function(response) {
                    alert(response);
                    button.prop('disabled', true).text('Completed');
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                }
            });
        });
    });
</script>
</body>
</html>
