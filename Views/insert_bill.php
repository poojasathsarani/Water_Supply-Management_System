<?php
include('db.php');
$sql = "SELECT id, name,email, phone FROM users";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}


// Check if the request is a POST (for insertion)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from POST request
    $service_provider_id = $_POST['service_provider_id'];
    $meter_number = $_POST['meter_number'];
    $name = $_POST['name'];
    $billing_date = $_POST['billing_date'];
    $current_consumption = $_POST['current_consumption'];
    $previous_consumption = $_POST['previous_consumption'];

    // Prepare the SQL statement for insertion
    $sql = "INSERT INTO bills (service_provider_id, meter_number, name, billing_date, current_consumption, previous_consumption) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdd", $service_provider_id, $meter_number, $name, $billing_date, $current_consumption, $previous_consumption);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Bill inserted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
    }

    // Close statement
    $stmt->close();
}

// Check if the request is a GET (for searching)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get data from GET request (e.g., meter_number)
    $meter_number = $_GET['meter_number'];

    // Prepare the SQL statement for search
    $sql = "SELECT service_provider_id, meter_number, name, billing_date, current_consumption, previous_consumption FROM bills WHERE meter_number = ?";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $meter_number);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch data and return it as JSON
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No record found for the given meter number.']);
    }

    // Close statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
