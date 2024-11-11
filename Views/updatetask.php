<?php
include('db.php');

// Check if task data was submitted
if (isset($_POST['taskID'])) {
    $taskIDs = $_POST['taskID'];
    $name = $_POST['name'];
    $priorities = $_POST['priority'];
    $startDates = $_POST['startDate'];
    $endDates = $_POST['endDate'];
    $completedPercentages = $_POST['completedPercentage'];
    $statuses = $_POST['status'];
    $budgets = $_POST['budget'];
    $notes = $_POST['notes'];

    // Loop through the submitted data and update each task
    for ($i = 0; $i < count($taskIDs); $i++) {
        $taskID = $taskIDs[$i];
        $name = $name[$i];
        $priority = $priorities[$i];
        $startDate = $startDates[$i];
        $endDate = $endDates[$i];
        $completedPercentage = $completedPercentages[$i];
        $status = $statuses[$i];
        $budget = $budgets[$i];
        $note = $notes[$i];

        // Check if taskID exists in the database
        $checkQuery = "SELECT * FROM updatetask WHERE taskID = '$taskID'";
        $result = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($result) > 0) {
            // If task exists, update the task
            $query = "UPDATE updatetask 
                      SET name = '$name', priority = '$priority', startDate = '$startDate', endDate = '$endDate', 
                          completedPercentage = '$completedPercentage', status = '$status', 
                          budget = '$budget', notes = '$note' 
                      WHERE taskID = '$taskID'";
        } else {
            // If task does not exist, insert a new task
            $query = "INSERT INTO updatetask (taskID, name, priority, startDate, endDate, completedPercentage, status, budget, notes) 
                      VALUES ('$taskID', '$name', '$priority', '$startDate', '$endDate', '$completedPercentage', '$status', '$budget', '$note')";
        }
        mysqli_query($conn, $query);
    }
}

// Retrieve all tasks from the database
$tasksQuery = "SELECT * FROM updatetask";
$tasksResult = mysqli_query($conn, $tasksQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Completion Chart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/updatetask.css">
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


<div class="contenheader">
<h2>Task Update Table</h2>
<div class="search-buttons" style="width: 17%;">
    <input type="text" id="searchTaskId" placeholder="Enter Task ID">
    <button onclick="searchRow()">Search</button>
    <button onclick="addRow()">Add Row</button>
    <button onclick="refresh()">Refresh</button>
</div>
</div>

<form id="taskForm" method="POST">
<div class="tablecontainer">
    <table id="taskTable" class="table">
    <h1>Task Completion Chart</h1>
        <thead>
            <tr>
                <th>Task ID</th>
                <th>Name</th>
                <th>Priority</th>
                <th>Start</th>
                <th>End</th>
                <th>% Complete</th>
                <th>Status</th>
                <th>Budget</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($tasksResult) {
                while ($task = mysqli_fetch_assoc($tasksResult)) {
                    echo "<tr>";
                    echo "<td><input type='text' name='taskID[]' value='{$task['taskID']}' required></td>";
                    echo "<td><input type='text' name='name[]' value='{$task['name']}' required></td>";
                    echo "<td>
                        <select name='priority[]' required>
                            <option value='High' " . ($task['priority'] == 'High' ? 'selected' : '') . ">High</option>
                            <option value='Medium' " . ($task['priority'] == 'Medium' ? 'selected' : '') . ">Medium</option>
                            <option value='Low' " . ($task['priority'] == 'Low' ? 'selected' : '') . ">Low</option>
                        </select>
                    </td>";
                    echo "<td><input type='date' name='startDate[]' value='{$task['startDate']}' required></td>";
                    echo "<td><input type='date' name='endDate[]' value='{$task['endDate']}' required></td>";
                    echo "<td><input type='number' name='completedPercentage[]' value='{$task['completedPercentage']}' min='0' max='100' required></td>";
                    echo "<td>
                        <select name='status[]' required>
                            <option value='Not Started' " . ($task['status'] == 'Not Started' ? 'selected' : '') . ">Not Started</option>
                            <option value='In progress' " . ($task['status'] == 'In progress' ? 'selected' : '') . ">In progress</option>
                            <option value='Completed' " . ($task['status'] == 'Completed' ? 'selected' : '') . ">Completed</option>
                        </select>
                    </td>";
                    echo "<td><input type='number' name='budget[]' value='{$task['budget']}' step='0.01' required></td>";
                    echo "<td><textarea name='notes[]' rows='2'>{$task['notes']}</textarea></td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
        </div>
        <div class="submit">
    <button type="button" class="btn btn-primary"  onclick="submitTasks()">Submit</button>
        </div>
</form>
<a href="technician.php">
    <button style="margin-top:-600px; position: absolute; left: 20px; background-color:blue; width:170px; height:60px;border-radius:30px;">Dashboard</button>
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
// Function to add a new row to the table with empty fields
function addRow() {
    const table = document.getElementById("taskTable").getElementsByTagName("tbody")[0];
    const row = table.insertRow();

    // Insert new cells in the row with dropdowns
    row.innerHTML = `
        <td><input type="text" name="taskID[]" required></td>
        <td><input type='text' name="name[]" required></td>
        <td>
            <select name="priority[]" required>
                <option value="High">High</option>
                <option value="Medium">Medium</option>
                <option value="Low">Low</option>
            </select>
        </td>
        <td><input type="date" name="startDate[]" required></td>
        <td><input type="date" name="endDate[]" required></td>
        <td><input type="number" name="completedPercentage[]" min="0" max="100" required></td>
        <td>
            <select name="status[]" required>
                <option value="Not Started">Not Started</option>
                <option value="In progress">In progress</option>
                <option value="Completed">Completed</option>
            </select>
        </td>
        <td><input type="number" name="budget[]" step="0.01" required></td>
        <td><textarea name="notes[]" rows="2"></textarea></td>
    `;
}

// Function to collect and submit task data via AJAX
function submitTasks() {
    const form = document.getElementById('taskForm');
    const formData = new FormData(form);

    fetch('updatetask.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tasks submitted successfully!');
            location.reload();  // Reload the page or update the UI as needed
        } else {
            alert('Error submitting tasks.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Function to search for a row based on the Task ID
function searchRow() {
    // Get the search input value
    const searchInput = document.getElementById("searchTaskId").value.trim().toLowerCase();

    // Get the table rows
    const table = document.getElementById("taskTable");
    const rows = table.getElementsByTagName("tr");

    // Loop through the table rows and hide those that don't match the search input
    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
        const taskID = rows[i].getElementsByTagName("td")[0].getElementsByTagName("input")[0].value.trim().toLowerCase();
        
        if (taskID.includes(searchInput)) {
            rows[i].style.display = ""; // Show row
        } else {
            rows[i].style.display = "none"; // Hide row
        }
    }
}

function refresh() {
    document.getElementById("searchTaskId").value = ""; // Clear the search input

    // Get all the table rows
    const table = document.getElementById("taskTable");
    const rows = table.getElementsByTagName("tr");

    // Loop through all rows and display them
    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
        rows[i].style.display = ""; // Show the row
    }
}


</script>
</body>
</html>
