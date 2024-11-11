<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Consumer Feedback Survey</title>
    <link rel="stylesheet" href="../css/concumer_feedback.css">
</head>
<body>
<header>
    <div class="logo">
        <img class="logopic" src="../images/logo.png" alt="Aqua Link Logo">
    </div>
    <nav>
        <ul>
            <li><a class="nav-link" href="#">Home</a></li>
            <li><a class="nav-link" href="#">Services</a></li>
            <li><a class="nav-link" href="#">About Us</a></li>
            <li><a class="nav-link" href="#">Contact Us</a></li>
            <li><a class="nav-link" href="#">FAQ</a></li>
        </ul>
    </nav>
    <div class="search-bar">
        <form>
            <input class="form-control me-2" type="search" placeholder="Search here" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
</header>

<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Water Management</h3>
        </div>
        <ul class="list-unstyled components">
            <li>
                <a href="consumer.php" class="dashboard">Dashboard</a>
            </li>
          
        </ul>
    </nav>
    <div class="container">
        <div class="text-section">
            <h1>Consumer Feedback</h1>
            <p>Help us improve your water management experience. Share your thoughts and suggestions.</p>
            <!-- Feedback Form -->
          <form method="POST" action="">
    <textarea id="textarea" name="textarea" rows="4" cols="80"></textarea>
    <button type="submit">Submit Feedback</button>
         </form>
        </div>
        <div class="image-section">
            <img src="../images/images.png" alt="Water Management Device">
        </div>
    </div>
</div>

<footer class="footer">
    <div class="footer-section">
        <h3>AQUA LINK</h3>
        <p>
            The Water Supply Management System aims to revolutionize the traditional manual processes of water administration in rural areas. By leveraging modern technology, this system seeks to streamline water distribution, billing, and maintenance, ensuring a more efficient and reliable supply of water.
        </p>
    </div>
    <div class="footer-section">
        <h3>USEFUL LINKS</h3>
        <ul>
            <li><a href="#">My Account</a></li>
            <li><a href="#">Annual Reports</a></li>
            <li><a href="#">Customer Services</a></li>
            <li><a href="#">Help</a></li>
        </ul>
    </div>
    <div class="footer-section contact-info">
        <h3>CONTACT</h3>
        <p>Colombo, Sri Lanka</p>
        <p>info@aqualink.lk</p>
        <p>+ 94 764 730 521</p>
        <p>+ 94 760 557 356</p>
    </div>
</footer>
<div class="footer-bottom">
    <p>&copy; 2024 Copyright: aqualink.lk</p>
</div>
</body>
</html>
<?php
// Include database connection file
include('db.php'); // Replace with your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve feedback from the form
    $feedback = $_POST['textarea'];

    // Check if feedback is not empty
    if (!empty($feedback)) {
        // Prepare SQL insert query
        $sql = "INSERT INTO customer_feedback (feedback_text) VALUES (?)";

        // Prepare and bind statement
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $feedback);

            // Execute the statement
            if ($stmt->execute()) {
                echo "Feedback submitted successfully.";
            } else {
                echo "Error: Could not submit feedback. Please try again later.";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error: Could not prepare SQL statement.";
        }
    } else {
        echo "Please provide feedback before submitting.";
    }

    // Close the database connection
    $conn->close();
}
?>