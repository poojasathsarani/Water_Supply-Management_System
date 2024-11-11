<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" type="text/css" href="../css/handlingorders.css">
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

<!-- Sidebar -->
<div class="wrapper">
    <nav id="sidebar">
        <ul class="list-unstyled components">
            <li>
                <a href="admin.php" class="dashboard">Dashboard</a>
            </li>
        </ul>
    </nav>

    <div id="content">
        <div class="search-bar-container">
            <input class="form-control" id="searchUserID" type="text" placeholder="Search by User ID">
            <button class="btn btn-primary" onclick="searchUser()">Search</button>
            <button class="btn btn-primary" onclick="refresh()">Refresh</button>
        </div>

        <div class="tablecontainer">
            <table id="usersTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Current Role</th> <!-- New column for current role -->
                        <th>Update Role</th>  <!-- Updated column header -->
                    </tr>
                </thead>
                <tbody>
                    <!-- Users will be inserted here by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer class="text-center text-lg-start text-white" style="background: linear-gradient(108.9deg, rgb(18, 85, 150) 4.9%, rgb(100, 190, 150) 97%); font-family: Arial, sans-serif;">
    <section>
        <div class="container text-center text-md-start mt-5">
            <div class="row mt-3">
                <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">AQUA LINK</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #7c4dff; height: 2px"/>
                    <p>The Water Supply Management System aims to revolutionize the traditional manual processes of water administration in rural areas...</p>
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

<?php
include('db.php');

// Fetch users from the database
$sql = "SELECT id, name, email, phone, role FROM users";
$result = $conn->query($sql);

$users = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Update user role in the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = isset($_POST['userID']) ? intval($_POST['userID']) : 0;
    $role = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : '';

    if ($userID > 0 && !empty($role)) {
        $query = "UPDATE users SET role = '$role' WHERE id = $userID";
        if ($conn->query($query) === TRUE) {
            echo '
            <script>
                alert("User role updated successfully.");
            </script>';
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    } else {
        echo "Invalid input.";
    }
}
$conn->close();
?>

<script>
$(document).ready(function() {
    var data = <?php echo json_encode($users); ?>;

    function populateTable(data) {
        var tableBody = $('#usersTable tbody');
        tableBody.empty(); // Clear existing rows
        data.forEach(function(user) {
            var row = `<tr>
                <td>${user.id}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.phone}</td>
                <td>${user.role}</td> <!-- Display current role -->
                <td>
                    <select class="form-control user-role" data-user-id="${user.id}">
                        <option value="Consumer" ${user.role == 'Consumer' ? 'selected' : ''}>Consumer</option>
                        <option value="Technician" ${user.role == 'Technician' ? 'selected' : ''}>Technician</option>
                        <option value="Service Provider" ${user.role == 'Service Provider' ? 'selected' : ''}>Service Provider</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-success submit-btn" onclick="submitUser(${user.id})">Update</button>
                </td>
            </tr>`;
            tableBody.append(row); // Append the new row to the table body
        });
    }


    populateTable(data);

    window.searchUser = function() {
        var searchUserID = $('#searchUserID').val().trim();
        if (searchUserID) {
            var filteredData = data.filter(function(user) {
                return user.id == searchUserID;
            });
            populateTable(filteredData);
        } else {
            populateTable(data);
        }
    };

    window.refresh = function() {
        $('#searchUserID').val('');
        populateTable(data);
    };

    window.submitUser = function(userID) {
        var role = $(`.user-role[data-user-id='${userID}']`).val();
        $.ajax({
            type: "POST",
            url: "",
            data: { userID: userID, role: role },
            success: function() {
                alert("User updated successfully");
                refresh();
            },
            error: function() {
                alert("Error updating user");
            }
        });
    };
});
</script>
</body>
</html>