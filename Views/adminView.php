<?php
include('db.php');

// Initialize the stats array to avoid undefined variable errors
$stats = array(
    'user_count' => 0,
    'feedback_count' => 0,
    'total_sales' => 0,
    'request_count' => 0
);

// Fetch data for users, feedback, sales, and visits
$sqlStats = "
    SELECT 
        (SELECT COUNT(*) FROM users) AS user_count,
        (SELECT COUNT(*) FROM customer_feedback) AS feedback_count,
        (SELECT COUNT(*) FROM orders WHERE orderStatus = 'Delivered') AS total_sales,
        (SELECT COUNT(*) FROM maintenance_requests ) AS request_count
";

$resultStats = $conn->query($sqlStats);
if ($resultStats) {
    $stats = $resultStats->fetch_assoc();
} else {
    echo "Error fetching stats: " . $conn->error;
}

$results = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // SQL queries for each table
    $tables = [
        'admin' => "SELECT adminID, username, name, email, phone FROM admin",
        'users' => "SELECT id, name, email, phone, address, province, city, role FROM users",
        'bills' => "SELECT bill_id, meter_number, name, billing_date, current_consumption, previous_consumption, billingStatus FROM bills",
        'maintenance_requests' => "SELECT requestID, meter_number, phone, location, issue_description, requestStatus FROM maintenance_requests",
        'orders' => "SELECT orderID, meter_number, name, phone, filterQuantity, orderStatus FROM orders",
        'updatetask' => "SELECT taskID, priority, startDate, endDate, completedPercentage, status FROM updatetask",
        'customer_feedback' => "SELECT feedback_id, feedback_text AS Text, timestamp FROM customer_feedback"
    ];

    // Execute queries
    foreach ($tables as $key => $query) {
        $result = $conn->query($query);
        $results[$key] = $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add a function to fetch user details
    function getUserDetails($userId) {
        global $conn;
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Check if there's a user detail request
    if (isset($_GET['view_user_id'])) {
        $userDetails = getUserDetails($_GET['view_user_id']);
    }
    
    // Fetching data from the bills table
    $sql = "SELECT billing_date, current_consumption, previous_consumption FROM bills ORDER BY billing_date";
    $result = $conn->query($sql);

    $dates = [];
    $currentConsumption = [];
    $previousConsumption = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dates[] = $row['billing_date'];
            $currentConsumption[] = $row['current_consumption'];
            $previousConsumption[] = $row['previous_consumption'];
        }
    } else {
        echo "No results found";
    }


    // Get the current month and year
$month = date('n'); // Current month as a number (1 to 12)
$year = date('Y');  // Current year

// Query to get total maintenance requests for the current month and year
$sqlMaintenance = "SELECT COUNT(*) as totalMaintenanceRequests 
                   FROM maintenance_requests 
                   WHERE MONTH(created_at) = $month AND YEAR(created_at) = $year";
$resultMaintenance = $conn->query($sqlMaintenance);
$totalMaintenanceRequests = $resultMaintenance->fetch_assoc()['totalMaintenanceRequests'];

// Query to get total orders for the current month and year
$sqlOrders = "SELECT COUNT(*) as totalOrders 
              FROM orders 
              WHERE MONTH(created_at) = $month AND YEAR(created_at) = $year";
$resultOrders = $conn->query($sqlOrders);
$totalOrders = $resultOrders->fetch_assoc()['totalOrders'];

// Prepare data for the chart
$chartData = json_encode([
    "months" => [$month],
    "totalMaintenanceRequests" => [$totalMaintenanceRequests],
    "totalOrders" => [$totalOrders]
]);
    // Close the database connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Supply Management Admin</title>
    <link rel="stylesheet" href="../css/Aview.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
    
    <div class="wrapper">
        <nav id="sidebar">
            <ul>
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="#admin">Admin Panel</a></li>
                <li><a href="#users">Users</a></li>
                <li><a href="#bills">Bills</a></li>
                <li><a href="#maintenance">Maintenance Requests</a></li>
                <li><a href="#orders">Orders</a></li>
                <li><a href="#tasks">Tasks</a></li>
                <li><a href="#feedback">Customer Feedback</a></li>
            </ul>
        </nav>
    </div>
    
    <div class="main-content">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <h3 id="userCount"><?php echo $stats['user_count']; ?></h3>
                    <p>Users</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <h3 id="totalSales"><?php echo $stats['total_sales']; ?></h3>
                    <p>Sales</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <h3 id="feedbackCount"><?php echo $stats['feedback_count']; ?></h3>
                    <p>Feedback</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <h3 id="visitCount"><?php echo $stats['request_count']; ?></h3>
                    <p>Requests</p>
                </div>
            </div>
        </div>
        
        <div class="container">
            <h3>Summary Charts</h3>
            <canvas id="lineChart"width="867" height="433" style="display: block; box-sizing: border-box; height: 315px; width: 631px;"></canvas>
            <canvas id="combinedChart" width="867" height="433" style="display: block; box-sizing: border-box; height: 315px; width: 631px;"></canvas>
        </div>

        <!-- Dynamic sections for each panel -->
        <section id="admin" class="admin-panel">
            <div class="card">
                <div class="card-body">
                    <h2>Admin Panel</h2>
                    <?php foreach ($results['admin'] as $admin): ?>
                        <div class="card">
                            <h3><?php echo $admin['name']; ?></h3>
                            <p><strong>ID:</strong> <?php echo $admin['adminID']; ?></p>
                            <p><strong>Username:</strong> <?php echo $admin['username']; ?></p>
                            <p><strong>Email:</strong> <?php echo $admin['email']; ?></p>
                            <p><strong>Contact No:</strong> <?php echo $admin['phone']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        
        <section id="users" class="admin-panel">
            <h2>Users List</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Province</th>
                        <th>City</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results['users'] as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['phone']; ?></td>
                            <td><?php echo $user['address']; ?></td>
                            <td><?php echo $user['province']; ?></td>
                            <td><?php echo $user['city']; ?></td>
                            <td><?php echo $user['role']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        
        <section id="bills" class="admin-panel">
            <h2>Bills</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Meter Number</th>
                        <th>Name</th>
                        <th>Billing Date</th>
                        <th>Current Consumption</th>
                        <th>Previous Consumption</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results['bills'] as $bill): ?>
                        <tr>
                            <td><?php echo $bill['bill_id']; ?></td>
                            <td><?php echo $bill['meter_number']; ?></td>
                            <td><?php echo $bill['name']; ?></td>
                            <td><?php echo $bill['billing_date']; ?></td>
                            <td><?php echo $bill['current_consumption']; ?></td>
                            <td><?php echo $bill['previous_consumption']; ?></td>
                            <td><?php echo $bill['billingStatus']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        
        <section id="maintenance" class="admin-panel">
            <h2>Maintenance Requests</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Meter Number</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Issue Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results['maintenance_requests'] as $request): ?>
                        <tr>
                            <td><?php echo $request['requestID']; ?></td>
                            <td><?php echo $request['meter_number']; ?></td>
                            <td><?php echo $request['phone']; ?></td>
                            <td><?php echo $request['location']; ?></td>
                            <td><?php echo $request['issue_description']; ?></td>
                            <td><?php echo $request['requestStatus']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section id="orders" class="admin-panel">
            <h2>Orders</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Meter Number</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results['orders'] as $order): ?>
                        <tr>
                            <td><?php echo $order['orderID']; ?></td>
                            <td><?php echo $order['meter_number']; ?></td>
                            <td><?php echo $order['name']; ?></td>
                            <td><?php echo $order['phone']; ?></td>
                            <td><?php echo $order['filterQuantity']; ?></td>
                            <td><?php echo $order['orderStatus']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section id="tasks" class="admin-panel">
            <h2>Tasks</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Task ID</th>
                        <th>Priority</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Completion %</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results['updatetask'] as $task): ?>
                        <tr>
                            <td><?php echo $task['taskID']; ?></td>
                            <td><?php echo $task['priority']; ?></td>
                            <td><?php echo $task['startDate']; ?></td>
                            <td><?php echo $task['endDate']; ?></td>
                            <td><?php echo $task['completedPercentage']; ?>%</td>
                            <td><?php echo $task['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section id="feedback" class="admin-panel">
            <h2>Customer Feedback</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Feedback ID</th>
                        <th>Feedback</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results['customer_feedback'] as $feedback): ?>
                        <tr>
                            <td><?php echo $feedback['feedback_id']; ?></td>
                            <td><?php echo $feedback['Text']; ?></td>
                            <td><?php echo $feedback['timestamp']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>

    <footer>
        <p>&copy; 2024 Water Supply Management. All Rights Reserved.</p>
    </footer>

    <script>
        // Chart.js for displaying charts
        const ctxLine = document.getElementById('lineChart').getContext('2d');

        const lineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [
                    {
                        label: 'Current Consumption',
                        data: <?php echo json_encode($currentConsumption); ?>,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false,
                    },
                    {
                        label: 'Previous Consumption',
                        data: <?php echo json_encode($previousConsumption); ?>,
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 2,
                        fill: false,
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Consumption'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });

       // Data from PHP
       const chartData = <?php echo $chartData; ?>;

// Create the chart
const ctx = document.getElementById('combinedChart').getContext('2d');
const combinedChart = new Chart(ctx, {
    type: 'bar', // Chart type
    data: {
        labels: chartData.months.map(month => `Month ${month}`),
        datasets: [
            {
                label: 'Maintenance Requests',
                data: chartData.totalMaintenanceRequests,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            },
            {
                label: 'Orders',
                data: chartData.totalOrders,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Count'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Months'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            title: {
                display: true,
                text: 'Combined Chart for Maintenance Requests and Orders'
            }
        }
    }
});
</script>
</body>
</html>
