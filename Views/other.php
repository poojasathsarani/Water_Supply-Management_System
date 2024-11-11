<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Service Roles</title>
    <style>
    body {
    background: linear-gradient(108.9deg, rgb(28, 139, 203) 4.9%, rgb(120, 195, 232) 97%);
    font-family: Arial, sans-serif;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    box-sizing: border-box;
    margin: 0;
    padding: 0
    }
    header {
    background-color: #2a414e;
    margin-top: -15px;
    height: 130px;
    position: fixed;
    top: 0;
    z-index: 1000;
    width: 100%;
}

.nav-link {
    margin-right: 10px;
    margin-left: 20px;
    margin-top: 10px;
    color: rgb(0, 0, 0);
    text-transform: uppercase;
    font-size: 25px;
    font-weight: bold;
    font-family: "Courier New";
}

.nav-link:hover {
    color: rgb(64, 169, 233);
}

.navbar {
    display: flex;
    align-items: center;
    width: 100%;
    justify-content: space-between;
}

.navbar-collapse {
    flex-grow: 1;
    justify-content: flex-end;
}

.navbar-nav {
    display: flex;
    list-style: none;
    padding-left: 0;
}

.logopic {
    margin-left: 20px;
    margin-top: 10px;
    width: 100px;
    height: auto;
    border: none;
}

.form-inline {
    position: absolute;
    margin-left: 1090px;
    margin-top: 8px;
}

.me-2 {
    width: 350px;
    height: 45px;
}

header .logopic {
    width: 100px;
    height: auto;
}

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 130px;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 15px;
            width: 250px;
            padding: 20px;
            text-align: center;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
            margin-left:50px;
            
        }
        .card h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }
        .card p {
            color: #555;
            font-size: 16px;
        }
        .link{
            margin-left:2px;
        }
        /* Footer Styles */
footer {
    background: linear-gradient(108.9deg, rgb(18, 85, 150) 4.9%, rgb(100, 190, 150) 97%);
    color: white;
    width: 100%;
    text-align: center;
}

footer .text-uppercase {
    font-weight: bold;
}

footer .fw-bold {
    font-weight: bold;
}

footer .mb-4,
footer .mt-0,
footer .mx-auto {
    margin-bottom: 1rem !important;
    margin-top: 0 !important;
    margin-right: auto !important;
    margin-left: auto !important;
}

footer .mt-5 {
    margin-top: 3rem !important;
}

footer .container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}

footer .row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}

footer .text-md-start {
    text-align: left !important;
}

footer .text-white {
    color: white !important;
}

    </style>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg">
        <img class="logopic" src="../images/logo.png" alt="Logo">
        <div class="navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
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
        <div class="form-inline">
            <form>
                <input class="form-control me-2" type="search" placeholder="Search here" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </nav>
</header>

    <div class="container" >
        <!-- Meter Reader Card -->
        <div class="card">
        <img src="../images/R.jpg" alt="Meter Reader  Card">
            <h3>Meter Reader</h3>
            <p>Monitors and records usage data for utility billing.</p>
            <a class="link" href="meterreader.php">Meter Reader  Card </a>
        </div>
        <!-- Service Provider Card -->
         
        

        <!-- Consumer Card -->
        <div class="card">
        <img src="../images/R.jpg" alt=" Consumer  Card">
            <h3>Consumer</h3>
            <p>The end-user of the provided services, receiving and paying for utilities.</p>
            <a class="link" href="consumer.php">Consumer Card </a>
        </div>

        <!-- Technician Card -->
        <div class="card">
            <img src="../images/R.jpg" alt="Technician Card">
            <h3>Technician</h3>
            <p>Handles the technical aspects like installations, repairs, and maintenance.</p>
            <a class="link" href="technician.php">Technician Card </a>
        </div>

      <!-- Service Provider Card -->
         
      <div class="card">
      <img src="../images/R.jpg" alt="Service Provider Card">
            <h3>Service Provider</h3>
            <p>Responsible for managing services and delivering utility.</p>
            <a class="link" href="serviceprovider.php">Service Provider Card </a>
        </div>
    </div>
    <a href="admin.php">
    <button style="margin-top:-700px; position: absolute; left: 20px; background-color:blue; width:170px; height:60px;border-radius:30px;">Dashboard</button>
</a>
    <footer class="text-center text-lg-start text-white">
    <section class="">
        <div class="container text-center text-md-start mt-5">
            <div class="row mt-3">
                <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">AQUA LINK</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto"/>
                    <p>The Water Supply Management System aims to revolutionize the traditional manual processes of water administration in rural areas. By leveraging modern technology, this system seeks to streamline water distribution, billing, and maintenance, ensuring a more efficient and reliable supply of water.</p>
                </div>
                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">Useful links</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto"/>
                    <p><a href="#!" class="text-white">My Account</a></p>
                    <p><a href="annualreports.php" class="text-white">Annual Reports</a></p>
                    <p><a href="customerservices.php" class="text-white">Customer Services</a></p>
                    <p><a href="help.php" class="text-white">Help</a></p>
                </div>
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <h6 class="text-uppercase fw-bold">Contact</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto"/>
                    <p><i class="fas fa-home mr-3"></i>Colombo, Sri Lanka</p>
                    <p><i class="fas fa-envelope mr-3"></i> info@aqualink.lk</p>
                    <p><i class="fas fa-phone mr-3"></i> + 94 764 730 521</p>
                    <p><i class="fas fa-print mr-3"></i> + 94 760 557 356</p>
                </div>
            </div>
        </div>
        <div class="text-center p-3">
            © 2024 Copyright: <a class="text-white" href="">aqualink.lk</a>
        </div>
    </section>
</footer>


</body>
</html>
