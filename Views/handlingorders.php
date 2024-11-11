<?php
include('db.php');
// Fetch orders from the database
$sql = "SELECT orderID, meter_number, name, phone, filterQuantity, orderStatus FROM orders";
$result = $conn->query($sql);

$orders = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderID = isset($_POST['orderID']) ? intval($_POST['orderID']) : 0;
    $orderStatus = isset($_POST['orderStatus']) ? mysqli_real_escape_string($conn, $_POST['orderStatus']) : '';

    // Update order status in the database
    if ($orderID > 0 && !empty($orderStatus)) {
        $query = "UPDATE orders SET orderStatus = '$orderStatus' WHERE orderID = $orderID";
        if ($conn->query($query) === TRUE) {
            echo '
            <script>
                alert("Successful!.");
            </script>';
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    } else {
        echo "Invalid input.";
    }
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handle Orders</title>
    <link rel="stylesheet" type="text/css" href="../css/handlingorders.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
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
<!-- sidebar -->
<div class="wrapper">
    <nav id="sidebar">
        <ul class="list-unstyled components">
            <li>
                <a href="serviceprovider.php" class="dashboard">Dashboard</a>
            </li>
        </ul>
    </nav>

    <div id="content">
        <div class="search-bar-container">
            <input class="form-control" id="searchOrderID" type="text" placeholder="Search by Order ID">
            <button class="btn btn-primary" onclick="searchOrder()">Search</button>
            <button class="btn btn-primary" onclick="refresh()">Refresh</button>
        </div>

        <div class="tablecontainer">
          <table id="ordersTable" class="table table-bordered">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Meter Number</th>
                <th>Consumer Name</th>
                <th>Phone Number</th>
                <th>Filter Quantity</th>
                <th>Delivery Status</th>
                <th>Submit</th>
              </tr>
            </thead>
            <tbody>
              <!-- Orders will be inserted here by JavaScript -->
            </tbody>
          </table>
        </div>
    </div>
</div>

<footer class="text-center text-lg-start text-white" style="background: linear-gradient(108.9deg, rgb(18, 85, 150) 4.9%, rgb(100, 190, 150) 97%);font-family: Arial, sans-serif;">
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
    $(document).ready(function() {
        var data = <?php echo json_encode($orders); ?>;

        function populateTable(data) {
            var tableBody = $('#ordersTable tbody');
            tableBody.empty();
            data.forEach(function(order) {
                var isDelivered = order.orderStatus === 'Delivered'; // Check if status is Delivered
                
                var row = `<tr>
                    <td>${order.orderID}</td>
                    <td>${order.meter_number}</td>
                    <td>${order.name}</td>
                    <td>${order.phone}</td>
                    <td>${order.filterQuantity}</td>
                    <td>
                        <select class="form-control order-status" data-order-id="${order.orderID}" ${isDelivered ? 'disabled' : ''}>
                            <option disabled selected>Select Status</option>
                            <option value="Processing" ${order.orderStatus == 'Processing' ? 'selected' : ''}>Processing</option>
                            <option value="Delivered" ${order.orderStatus == 'Delivered' ? 'selected' : ''}>Delivered</option>
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-success submit-btn" onclick="submitOrder(${order.orderID})" ${isDelivered ? 'disabled' : ''}>Submit</button>
                    </td>
                </tr>`;
                tableBody.append(row);
            });
        }

        populateTable(data);

        window.searchOrder = function() {
            var searchOrderID = $('#searchOrderID').val().trim();
            if (searchOrderID) {
                var filteredData = data.filter(function(order) {
                    return order.orderID == searchOrderID;
                });
                populateTable(filteredData);
            } else {
                populateTable(data);
            }
        };

        window.refresh = function() {
            $('#searchOrderID').val('');
            populateTable(data);
        };

        window.submitOrder = function(orderID) {
            var orderStatus = $(`.order-status[data-order-id='${orderID}']`).val();
            $.ajax({
                type: "POST",
                url: "handlingorders.php", // Ensure this is the correct PHP file for updating order status
                data: {
                    orderID: orderID,
                    orderStatus: orderStatus
                },
                success: function(response) {
                    alert('Submit Successful!'); // Alert the response from the PHP script
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    alert('Error submitting order status.');
                }
            });
        };
    });
</script>
</body>
</html>