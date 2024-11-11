<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Meter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/updatemeter.css">
</head>
<body>
    <header class="header">
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
            <form onsubmit="return false;">
                <input class="form-control me-2" id="meterIdInput" type="search" placeholder="Search here" aria-label="Search">
                <button class="btn btn-outline-success" type="button" onclick="searchByMeterId()">Search</button>
            </form>
        </div>
    </header>

    <div class="wrapper">
        <nav id="sidebar">
            <ul class="list-unstyled components">
                <li><a href="meterreader.php" class="dashboard">Dashboard</a></li>
                <li><a href="warningmeter.php" class="dashboard">Warning</a></li>
                <li><a href="meterprofile.php" class="dashboard">Profile Management</a></li>
            </ul>
        </nav>

        <div class="tablecontainer">
            <h2>Bill Data Update Table</h2>
            <form id="billForm">
                <table border="3" cellspacing="1" cellpadding="10" id="billTable" class="table" style="border-color:#fff">
                    <thead>
                        <tr>
                            <th>Service Provider ID</th>
                            <th>Meter Number</th>
                            <th>Consumer Name</th>
                            <th>Billing Date</th>
                            <th>Current Consumption</th>
                            <th>Previous Consumption</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td contenteditable="true"></td>
                            <td contenteditable="true"></td>
                            <td contenteditable="true"></td>
                            <td contenteditable="true"></td>
                            <td contenteditable="true"></td>
                            <td contenteditable="true"></td>
                            <td>
                                <button class="btn btn-success" type="button" onclick="enterRow(this)">Enter</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-primary" type="button" id="addRowBtn">Add Row</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to add a new row
        document.getElementById('addRowBtn').addEventListener('click', function() {
            var newRow = `<tr>
                <td contenteditable="true"></td>
                <td contenteditable="true"></td>
                <td contenteditable="true"></td>
                <td contenteditable="true"></td>
                <td contenteditable="true"></td>
                <td contenteditable="true"></td>
                <td>
                    <button class="btn btn-success" type="button" onclick="enterRow(this)">Enter</button>
                </td>
            </tr>`;
            $('#billTable tbody').append(newRow);
        });

        function enterRow(button) {
            var row = $(button).closest('tr');
            var serviceProviderId = row.find('td:eq(0)').text().trim();
            var meterNumber = row.find('td:eq(1)').text().trim();
            var name = row.find('td:eq(2)').text().trim();
            var billingDate = row.find('td:eq(3)').text().trim();
            var currentConsumption = row.find('td:eq(4)').text().trim();
            var previousConsumption = row.find('td:eq(5)').text().trim();

            $.ajax({
                url: 'insert_bill.php',
                method: 'POST',
                data: {
                    service_provider_id: serviceProviderId,
                    meter_number: meterNumber,
                    name: name,
                    billing_date: billingDate,
                    current_consumption: currentConsumption,
                    previous_consumption: previousConsumption
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    alert(data.message);
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        }
    </script>
</body>
</html>
