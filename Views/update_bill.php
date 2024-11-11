<?php
include('db.php');

// Ensure the content type is JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meter_number = $_POST['meter_number'];
    $total_amount = $_POST['total_amount'];

    // Prepare the SQL query to update only if this_month_bill is empty or null
    $sql = "UPDATE bills SET this_month_bill = ? WHERE meter_number = ? AND (this_month_bill IS NULL OR this_month_bill = '')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ds', $total_amount, $meter_number); // 'd' for double (total_amount), 's' for string (meter_number)

    if ($stmt->execute()) {
        // Check if any rows were affected by the update
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Bill updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No bill was updated. The bill may already exist.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update the bill.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>
