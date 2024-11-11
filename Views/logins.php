<?php
session_start(); // Start the session at the very top

include('db.php'); // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize inputs
    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';

    if (empty($username) || empty($password)) {
        echo '<script>
            alert("Please fill in both fields.");
            window.location="logins.php";
            </script>';
        exit();
    }

    // First check if it's an admin login
    $adminQuery = mysqli_query($conn, "SELECT adminID, username, password FROM admin WHERE username='$username' AND password='$password'");
    if (!$adminQuery) {
        die('Query failed: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($adminQuery) == 1) {
        // Admin login successful
        $admin = mysqli_fetch_assoc($adminQuery);
        $_SESSION['admin'] = $admin['username'];
        $_SESSION['admin_id'] = $admin['adminID']; // Store adminID in session

        echo '
        <script>
            alert("Admin login successful.");
            window.location="admin.php";
        </script>';
        exit();
    }

    // If not an admin, check in the users table
    $userQuery = "SELECT id, username, password, role FROM users WHERE username='$username'";
    $userResult = mysqli_query($conn, $userQuery);

    if (!$userResult) {
        die('Query failed: ' . mysqli_error($conn));
    }

    // Check if user exists
    if (mysqli_num_rows($userResult) == 1) {
        $user = mysqli_fetch_assoc($userResult);

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables for user
            $_SESSION['user_id'] = $user['id']; // Store user ID
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            switch ($user['role']) {
                case 'consumer':
                    header('Location: consumer.php');
                    break;
                case 'meterreader':
                    header('Location: meterreader.php');
                    break;
                case 'technician':
                    header('Location: technician.php');
                    break;
                case 'serviceprovider':
                    header('Location: serviceprovider.php');
                    break;
                default:
                    echo '<script>
                        alert("Unknown role.");
                        window.location="logins.php";
                        </script>';
                    break;
            }
            exit();
        } else {
            echo '<script>
                alert("Invalid password.");
                window.location="logins.php";
                </script>';
        }
    } else {
        echo '<script>
            alert("Invalid username.");
            window.location="logins.php";
            </script>';
    }
}
?>



<html>
<head>
    <style></style>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../css/logins.css">
</head>
<body>
    <video autoplay muted loop id="background-video">
        <source src="../images/background.mp4" type="video/mp4">
    </video>

    <div class="white-box">
        <!-- Unified Login Form -->
        <form id="loginform" action="" method="POST" class="input-group">
            <h2>Login</h2>
            <div class="x">
                <label for="username">Username</label><br>
                <input id="username" value="" name="username" type="text">
            </div>
            <br><br>
            <div class="y">
                <label for="password">Password</label><br>
                <input id="password" value="" name="password" type="password" placeholder="Enter Your Password">
            </div>
            <div>
                <button name='login' class="btn" id="placelogin" type="submit">Log In</button>
            </div>
        </form>

        <script>
            function toggleLogin() {
                var form = document.getElementById("loginform");
                form.style.display = form.style.display === "none" ? "block" : "none";
            }
        </script>

        <?php
        include('db.php'); // Include the database connection file

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Retrieve and sanitize inputs
            $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
            $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';

            if (empty($username) || empty($password)) {
                echo '<script>
                    alert("Please fill in both fields.");
                    window.location="logins.php";
                    </script>';
                exit();
            }

            // First check if it's an admin login
            $adminQuery = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
            if (!$adminQuery) {
                die('Query failed: ' . mysqli_error($conn));
            }

            if (mysqli_num_rows($adminQuery) == 1) {
                // Admin login successful
                $_SESSION['admin'] = 'admin';
                echo '
                <script>
                    alert("Admin login successful.");
                    window.location="admin.php";
                </script>';
                exit();
            }

            // If not an admin, check in the users table
            $userQuery = "SELECT id, username, password, role FROM users WHERE username='$username'";
            $userResult = mysqli_query($conn, $userQuery);

            if (!$userResult) {
                die('Query failed: ' . mysqli_error($conn));
            }

            // Check if user exists
            if (mysqli_num_rows($userResult) == 1) {
                $user = mysqli_fetch_assoc($userResult);

                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Set session variables for user
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect based on user role
                    switch ($user['role']) {
                        case 'consumer':
                            header('Location: consumer.php');
                            break;
                        case 'meterreader':
                            header('Location: meterreader.php');
                            break;
                        case 'technician':
                            header('Location: technician.php');
                            break;
                        case 'serviceprovider':
                            header('Location: serviceprovider.php');
                            break;
                        default:
                            echo '<script>
                                alert("Unknown role.");
                                window.location="logins.php";
                                </script>';
                            break;
                    }
                    exit();
                } else {
                    echo '<script>
                        alert("Invalid password.");
                        window.location="logins.php";
                        </script>';
                }
            } else {
                echo '<script>
                    alert("Invalid username.");
                    window.location="logins.php";
                    </script>';
            }
        }
        ?>
    </div>
</body>
</html>
