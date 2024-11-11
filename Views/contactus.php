<?php
session_start(); // Start the session
include 'db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Prepare an SQL statement to insert the data
    $sql = "INSERT INTO contactus (name, email, message) VALUES (?, ?, ?)";

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Message sent successfully!"; // Set success message
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error; // Set error message
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to the same page to prevent form resubmission
    header("Location: contactus.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="../css/contactus.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        // Function to show alert if success or error message is set
        window.onload = function() {
            <?php if (isset($_SESSION['success_message'])): ?>
                alert("<?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>");
            <?php elseif (isset($_SESSION['error_message'])): ?>
                alert("<?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>");
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="../images/logo.png" alt="Aqua Link Logo">
        </div>
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <li><a href="faq.php">FAQ</a></li>
            </ul>
        </nav>
        <div class="header-right">
            <div class="search-bar">
                <input type="text" placeholder="Search here">
                <button>Search</button>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <h1>Contact us</h1>
            <form action="contactus.php" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="" required>

                <label for="email">Email address</label>
                <input type="email" id="email" name="email" placeholder="" required>

                <label for="message">Your message</label>
                <textarea id="message" name="message" placeholder="Enter your question or message" required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="footer-section">
            <h2>AQUA LINK</h2>
            <p>The Water Supply Management System aims to revolutionize the traditional manual processes of water administration in rural areas. By leveraging modern technology, this system seeks to streamline
                water distribution, billing, and maintenance, ensuring a more efficient and reliable supply of water.</p>
        </div>
        <div class="footer-section">
            <h2>USEFUL LINKS</h2>
            <ul>
                <li><a href="#">My Account</a></li>
                <li><a href="#">Annual Reports</a></li>
                <li><a href="#">Customer Services</a></li>
                <li><a href="#">Help</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h2>CONTACT</h2>
            <p>Colombo, Sri Lanka</p>
            <p>info@aqualink.lk</p>
            <p>+94 764 730 521</p>
            <p>+94 760 557 356</p>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Copyright: aqualink.lk</p>
        </div>
    </footer>
</body>
</html>