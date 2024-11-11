<!DOCTYPE html>
<html>
<head>
<title>Consumer Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../css/sample.css">
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
<div class="container mt-4 Options">
    <div class="row">
        <div class="col-md-6">
            <a href="consumer_request.php">
                <div class="content">
                    <img src="../images/request.png" alt="fun2">
                    <h2>REQUEST FORM</h2>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="consumer_billdetails.php">
                <div class="content">
                    <img src="../images/billpayment1.png" alt="fun1">		
                    <h2>VIEW BILL DETAILS</h2>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="consumer_view.php">
                <div class="content">
                    <img src="../images/view.png" alt="fun5">
                    <h2>VIEW DETAILS</h2>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="consumer_warning.php">
                <div class="content">
                   <img src="../images/warnings.png" alt="fun4">
			    <h2>WARNING NOTIFICATIONS</h2>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="consumer_feedback.php">
                <div class="content">
                   <img src="../images/feedback.png" alt="fun4">
			        <h2>FEEDBACK</h2>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a href="profilemanage.php">
                <div class="content">
                    <img src="../images/profile.png" alt="fun3">
			        <h2>PROFILE MANAGE</h2>
                </div>
            </a>
        </div>
        </div>
    </div>
</div>
</body>
  <!-- Footer -->
  <footer
          class="text-center text-lg-start text-white"
          style="background: linear-gradient(108.9deg, rgb(18, 85, 150) 4.9%, rgb(100, 190, 150) 97%); margin-top:170px;padding:5px;"
          >

    <!-- Section: Links  -->
    <section class="">
      <div class="container text-center text-md-start mt-5">
        <!-- Grid row -->
        <div class="row mt-3">
          <!-- Grid column -->
          <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
            <!-- Content -->
            <h6 class="text-uppercase fw-bold">AQUA LINK</h6>
            <hr
                class="mb-4 mt-0 d-inline-block mx-auto"
                style="width: 60px; background-color: #7c4dff; height: 2px"
                />
            <p>
				The Water Supply Management System aims to revolutionize the traditional manual processes of water administration in rural areas. By leveraging modern technology, this system seeks to streamline 
			water distribution, billing, and maintenance, ensuring a more efficient and reliable supply of water.
            </p>
          </div>
          <!-- Grid column -->


          <!-- Grid column -->
          <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold">Useful links</h6>
            <hr
                class="mb-4 mt-0 d-inline-block mx-auto"
                style="width: 60px; background-color: #7c4dff; height: 2px"
                />
            <p>
              <a href="#!" class="text-white">My Account</a>
            </p>
            <p>
              <a href="annualreports.php" class="text-white">Annual Reports</a>
            </p>
            <p>
              <a href="customerservices.php" class="text-white">Customer Services</a>
            </p>
            <p>
              <a href="help.php" class="text-white">Help</a>
            </p>
          </div>
          <!-- Grid column -->

          <!-- Grid column -->
          <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold">Contact</h6>
            <hr
                class="mb-4 mt-0 d-inline-block mx-auto"
                style="width: 60px; background-color: #7c4dff; height: 2px"
                />
            <p><i class="fas fa-home mr-3"></i>Colombo, Sri Lanka</p>
            <p><i class="fas fa-envelope mr-3"></i> info@aqualink.lk</p>
            <p><i class="fas fa-phone mr-3"></i> + 94 764 730 521</p>
            <p><i class="fas fa-print mr-3"></i> + 94 760 557 356</p>
          </div>
        </div>
        <div class="text-center p-3" style="height:50px;">
            © 2024 Copyright: <a class="text-white" href="">aqualink.lk</a>
        </div>
      </div>
    </section>
  </footer>
</html>