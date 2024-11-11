<?php

include('db.php');

// Determine the action to take
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'fetchRequests':
        fetchRequests($conn);
        break;
    case 'fetchTechnicians':
        fetchTechnicians($conn);
        break;
    case 'viewDetails':
        viewDetails($conn);
        break;
    case 'searchRequest':
        searchRequest($conn);
        break;
    default:
        echo json_encode(["message" => "Invalid action"]);
        break;
}

$conn->close();

function fetchRequests($conn) {
    $sql = "SELECT * FROM maintenance_requests";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        echo json_encode(["error" => "Query failed: " . $conn->error]);
        return;
    }

    $requests = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
    } else {
        echo json_encode(["message" => "No requests found"]);
        return;
    }

    echo json_encode($requests);
}

function fetchTechnicians($conn) {
    $sql = "SELECT id, name FROM technicians";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        echo json_encode(["error" => "Query failed: " . $conn->error]);
        return;
    }

    $technicians = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $technicians[] = $row;
        }
    } else {
        echo json_encode(["message" => "No technicians found"]);
        return;
    }

    echo json_encode($technicians);
}

function viewDetails($conn) {
    $requestId = isset($_GET['requestId']) ? intval($_GET['requestId']) : 0;
    $sql = "SELECT datePeriodFrom, datePeriodTo, location, issue_description FROM maintenance_requests WHERE requestID = $requestId";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        echo json_encode(["error" => "Query failed: " . $conn->error]);
        return;
    }

    if ($result->num_rows > 0) {
        $details = $result->fetch_assoc();
        echo json_encode($details);
    } else {
        echo json_encode(["message" => "Request not found"]);
    }
}

function searchRequest($conn) {
    $searchInput = isset($_GET['searchInput']) ? $conn->real_escape_string($_GET['searchInput']) : '';
    $sql = "SELECT * FROM maintenance_requests WHERE requestID LIKE '%$searchInput%' OR meter_number LIKE '%$searchInput%' OR phone LIKE '%$searchInput%'";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        echo json_encode(["error" => "Query failed: " . $conn->error]);
        return;
    }

    $requests = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
    } else {
        echo json_encode(["message" => "No requests found"]);
        return;
    }

    echo json_encode($requests);
}
?>
