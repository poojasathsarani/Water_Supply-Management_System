<?php 
// Database connection
$host = 'localhost';
$db = 'water_supply_management_system';
$user = 'root'; // replace with your DB username
$pass = ''; // replace with your DB password

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize report data array
$reportData = [];

// Initialize separate report data arrays
$existingReportData = [];
$newReportData = [];

// Function to export data as CSV
function exportToCSV($data) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report.csv"');

    $output = fopen('php://output', 'w');

    // Output header row
    if (!empty($data)) {
        fputcsv($output, array_keys($data[0]));

        // Output data rows
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
    }

    fclose($output);
    exit();
}

// Function to export data as PDF
function exportToPDF($data) {
    // Load the library for PDF generation
    require_once 'C:\wamp64\www\Water_Supply_Management_System\tcpdf\TCPDF-main\tcpdf.php';

    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    // Generate HTML content for the PDF
    $html = '<h1>Report Data</h1>';
    if (!empty($data)) {
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<thead><tr>';

        // Output header row
        foreach ($data[0] as $key => $value) {
            $html .= '<th>' . htmlspecialchars(ucfirst($key)) . '</th>';
        }
        $html .= '</tr></thead><tbody>';

        // Output data rows
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $value) {
                $html .= '<td>' . htmlspecialchars($value) . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
    } else {
        $html .= '<p>No data available for export.</p>';
    }

    // Write HTML to PDF
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('report.pdf', 'D');
    exit();
}

// Fetch report data for existing and new reports
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Existing Reports
    if (isset($_POST['existingReportType'])) {
        $reportType = $_POST['existingReportType'];

        // Existing Reports SQL queries
        $sql = '';
        switch ($reportType) {
            case 'annual_consumption':
                $sql = "SELECT YEAR(billing_date) AS year, SUM(current_consumption - previous_consumption) AS total_consumption 
                        FROM bills 
                        GROUP BY year";
                break;

            case 'monthly_consumption':
                $sql = "SELECT MONTH(billing_date) AS month, SUM(current_consumption - previous_consumption) AS total_consumption 
                        FROM bills 
                        GROUP BY month";
                break;

            case 'service_provider_performance':
                $sql = "SELECT service_provider_id, COUNT(bill_id) AS total_bills 
                        FROM bills 
                        GROUP BY service_provider_id";
                break;

            case 'consumer_feedback':
                $sql = "SELECT feedback_text, timestamp 
                        FROM customer_feedback 
                        ORDER BY timestamp DESC";
                break;

            case 'maintenance_request_summary':
                $sql = "SELECT requestStatus, COUNT(requestID) AS total_requests 
                        FROM maintenance_requests 
                        GROUP BY requestStatus";
                break;

            case 'order_status':
                $sql = "SELECT orderStatus, COUNT(orderID) AS total_orders 
                        FROM orders 
                        GROUP BY orderStatus";
                break;

            default:
                $sql = '';
                break;
        }

        // Execute SQL for existing reports
        if ($sql) {
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $existingReportData[] = $row;
                }
            }
        }
    }

    // New Individual Reports
    if (isset($_POST['newReportType'])) {
        $reportType = $_POST['newReportType'];
        $meterNumber = isset($_POST['meter_number']) ? $conn->real_escape_string($_POST['meter_number']) : ''; // Escape input

        // Ensure meter number is not empty
        if (!empty($meterNumber)) {
            // New Individual Reports SQL queries
            $sql = '';
            switch ($reportType) {
                case 'individual_annual_consumption':
                    $sql = "SELECT YEAR(billing_date) AS year, SUM(current_consumption - previous_consumption) AS total_consumption 
                            FROM bills 
                            WHERE meter_number = '$meterNumber'
                            GROUP BY year";
                    break;

                case 'individual_monthly_consumption':
                    $sql = "SELECT MONTH(billing_date) AS month, SUM(current_consumption - previous_consumption) AS total_consumption 
                            FROM bills 
                            WHERE meter_number = '$meterNumber'
                            GROUP BY month";
                    break;

                case 'individual_maintenance_request_summary':
                    $sql = "SELECT requestStatus, COUNT(requestID) AS total_requests 
                            FROM maintenance_requests 
                            WHERE meter_number = '$meterNumber'
                            GROUP BY requestStatus";
                    break;

                case 'individual_order_status':
                    $sql = "SELECT orderStatus, COUNT(orderID) AS total_orders 
                            FROM orders 
                            WHERE meter_number = '$meterNumber'
                            GROUP BY orderStatus";
                    break;

                default:
                    $sql = '';
                    break;
            }

            // Execute SQL for new reports
            if ($sql) {
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $newReportData[] = $row;
                    }
                } else {
                    // Optional: handle case when no records are found
                    $newReportData[] = ['message' => 'No records found for this meter number.'];
                }
            }
        } else {
            $newReportData[] = ['message' => 'Please provide a valid meter number.'];
        }
    }

    // Handle form submission for exporting data
    if (isset($_POST['exportType'])) {
        $exportType = $_POST['exportType'];

        // Combine existing and new report data for export
        $combinedReportData = array_merge($existingReportData, $newReportData);

        if ($exportType === 'csv') {
            exportToCSV($combinedReportData);
        } elseif ($exportType === 'pdf') {
            exportToPDF($combinedReportData);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reports</title>
    <link rel="stylesheet" href="../css/GReport.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <img class="logopic" src="../images/logo.png">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="aboutus.php">About us</a></li>
                    <li class="nav-item"><a class="nav-link" href="contactus.php">Contact us</a></li>
                    <li class="nav-item"><a class="nav-link" href="faq.php">F&Q</a></li>
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
            <ul class="list-unstyled components">
                <li><a href="admin.php" class="dashboard">Dashboard</a></li>
            </ul>
        </nav>

        <div class="container">
            <h1>Generate Reports</h1>

            <form method="post" action="">
            <h2>Export Reports</h2>
                <select name="exportType">
                    <option value="">Select Export Type</option>
                    <option value="csv">Export as CSV</option>
                    <option value="pdf">Export as PDF</option>
                </select>
                
                <h2>Existing Reports</h2>
                <select name="existingReportType">
                    <option value="">Select Report Type</option>
                    <option value="annual_consumption">Annual Consumption</option>
                    <option value="monthly_consumption">Monthly Consumption</option>
                    <option value="service_provider_performance">Service Provider Performance</option>
                    <option value="consumer_feedback">Consumer Feedback</option>
                    <option value="maintenance_request_summary">Maintenance Request Summary</option>
                    <option value="order_status">Order Status</option>
                </select>
                <button type="submit">Generate</button>

                <h2>New Individual Reports</h2>
                <input type="text" name="meter_number" placeholder="Enter Meter Number">
                <select name="newReportType">
                    <option value="">Select Report Type</option>
                    <option value="individual_annual_consumption">Individual Annual Consumption</option>
                    <option value="individual_monthly_consumption">Individual Monthly Consumption</option>
                    <option value="individual_maintenance_request_summary">Individual Maintenance Request Summary</option>
                    <option value="individual_order_status">Individual Order Status</option>
                </select>
                <button type="submit">Generate</button>

               
            </form>

            <!-- Display reports -->
            <div class="report-data">
                <table>
                    <thead>
                        <tr>
                            <?php if (!empty($existingReportData)) : ?>
                                <?php foreach (array_keys($existingReportData[0]) as $header) : ?>
                                    <th><?php echo htmlspecialchars(ucfirst($header)); ?></th>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($existingReportData as $row) : ?>
                            <tr>
                                <?php foreach ($row as $value) : ?>
                                    <td><?php echo htmlspecialchars($value); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <?php if (!empty($newReportData)) : ?>
                                <?php foreach (array_keys($newReportData[0]) as $header) : ?>
                                    <th><?php echo htmlspecialchars(ucfirst($header)); ?></th>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($newReportData as $row) : ?>
                            <tr>
                                <?php foreach ($row as $value) : ?>
                                    <td><?php echo htmlspecialchars($value); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; 2024 Water Supply Management System. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
