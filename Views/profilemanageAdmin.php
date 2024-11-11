<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Admin not logged in or session expired.']);
    exit();
}

$adminID = $_SESSION['admin_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['firstName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['contactNumber']);
    $address = trim($_POST['address']);
    $province = trim($_POST['province']);
    $city = trim($_POST['city']);
    $username = trim($_POST['username']);
    $password = !empty($_POST['password']) ? password_hash(trim($_POST['password']), PASSWORD_DEFAULT) : null;

    $profilePicDestination = '';
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === 0) {
        // [Profile Pic Upload Logic]
    }

    $query = "UPDATE admin SET 
            name = ?, 
            email = ?, 
            phone = ?, 
            address = ?, 
            province = ?, 
            city = ?, 
            username = ?";

    $params = [$name, $email, $phone, $address, $province, $city, $username];

    if ($password !== null) {
        $query .= ", password = ?";
        $params[] = $password;
    }

    if ($profilePicDestination !== '') {
        $query .= ", profilePic = ?";
        $params[] = $profilePicDestination;
    }

    $query .= " WHERE adminID = ?";
    $params[] = $adminID;

    // Debug: Check the prepared SQL query and parameters
    echo "<pre>";
    print_r($query);
    print_r($params);
    echo "</pre>";

    if ($stmt = $conn->prepare($query)) {
        $types = str_repeat('s', count($params) - 1) . 'i';
        $stmt->bind_param($types, ...$params);
    
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating profile: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error preparing statement: ' . $conn->error]);
    }    
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="../css/profilemanage.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg">
        <img class="logopic" src="../images/logo.png" alt="Logo">
        <div class="navbar-collapse" id="navbarSupportedContent">
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
        <div class="form-inline">
            <form>
                <input class="form-control me-2" type="search" placeholder="Search here" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </nav>
</header>
<div class="container">
    <div class="">
        <h1>Edit profile</h1>
        <form id="profileForm" enctype="multipart/form-data" method="POST">
            <div class="profile-pic">
                <i id="profileIcon" class="fas fa-user-circle" onclick="triggerFileUpload()"></i>
                <img id="profilePic" src="" alt="Profile Picture" style="display:none;" onclick="triggerFileUpload()">
                <input type="file" id="profilePicUpload" name="profilePic" accept="image/*" onchange="loadFile(event)" style="display:none;">
            </div>
            <div class="form-group">
                <label for="firstName">Name</label>
                <input type="text" id="firstName" name="firstName" value="" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="" required>
            </div>
            <div class="form-group">
                <label for="contactNumber">Contact Number</label>
                <input type="text" id="contactNumber" name="contactNumber" value="" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="" required>
            </div>
            <div class="form-group">
                <label for="province">Province</label>
                <input type="text" id="province" name="province" value="" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" value="" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <small>If you do not want to change your password, leave this field blank.</small>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='profilemanage.php'">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
<a href="admin.php">
    <button style="margin-top:-600px; position: absolute; left: 20px; background-color:blue; width:170px; height:60px;border-radius:30px;">Dashboard</button>
</a>
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
        document.getElementById('profileForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this);

            fetch('profilemanageAdmin.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully!'); // Show success message
                    window.location.href = 'profilemanageAdmin.php'; // Redirect to profile page
                } else {
                    alert(data.message); // Show error message
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });


        function triggerFileUpload() {
            document.getElementById('profilePicUpload').click();
        }

        function loadFile(event) {
            const output = document.getElementById('profilePic');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.style.display = 'block';
            document.getElementById('profileIcon').style.display = 'none';
        }
</script>
</body>
</html>