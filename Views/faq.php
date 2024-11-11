<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs</title>
    <link rel="stylesheet" href="../css/F&Q.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Basic styles for the FAQ items */
        .faq-item {
            margin: 10px 0;
        }
        .faq-answer {
            display: none; /* Hide the answer by default */
            margin-top: 5px;
        }
        .expanded .faq-answer {
            display: block; /* Show the answer when expanded */
        }
        .plus-sign {
            float: right;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="../images/logo.png" alt="Aqua Link Logo">
        </div>
        <nav>
        <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <li><a href="faq.php">FAQ</a></li>
            </ul>
        </nav>
        <div class="header-right">
            <div class="search-bar">
                <input type="text" placeholder="Search here">
                <button>Search</button>
            </div>
            <div class="icons">
                <a href="../views/logins.php" class="login-icon"><i class="fas fa-user"></i></a>
                <a href="../views/register.php" class="register-icon"><i class="fas fa-user-plus"></i></a>
            </div>
        </div>
    </header>

    <div class="content-wrapper">
        <div class="faq-container">
            <div class="fq">
        
                <button class="faq-button">FAQS</button>
                <h1>Frequently Asked Questions</h1>
                <p>Find questions and answers related to the water supply management system, purchase, updates, and support.</p>
    </div>
            <div class="faq-content">
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleAnswer(this)">What is the purpose of the Water Supply Management System?<span class="plus-sign">+</span></button>
                    <div class="faq-answer">The system streamlines water administration tasks like billing, requests, and profile management for rural communities.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleAnswer(this)">How do I register for an account?<span class="plus-sign">+</span></button>
                    <div class="faq-answer">To register for an account, visit our registration page, fill in the required details, and submit the form. You will receive a confirmation email upon successful registration.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleAnswer(this)">How do I log in to my account?<span class="plus-sign">+</span></button>
                    <div class="faq-answer">To log in, go to the login page and enter your registered email address and password. If you forget your password, you can reset it using the provided link.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleAnswer(this)">How can I pay my water bill online?<span class="plus-sign">+</span></button>
                    <div class="faq-answer">You can pay your water bill online through our secure payment portal. Simply log into your account, navigate to the billing section, and follow the instructions to make a payment.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleAnswer(this)">How do I manage my profile?<span class="plus-sign">+</span></button>
                    <div class="faq-answer">You can manage your profile by logging into your account and navigating to the profile settings. Here, you can update your personal information, contact details, and preferences.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleAnswer(this)">Can I view my profile and account details?<span class="plus-sign">+</span></button>
                    <div class="faq-answer">Yes, you can view your profile and account details by logging into your account. All relevant information about your usage, billing history, and personal details will be available there.</div>
                </div>
            </div>
            
           
        </div>
        <!-- Footer -->
        <footer>
            <div class="footer-section">
                <h2>AQUA LINK</h2>
                <p>The Water Supply Management System aims to revolutionize the traditional manual processes of water administration in rural areas. By leveraging modern technology, this system seeks to streamline water distribution, billing, and maintenance, ensuring a more efficient and reliable supply of water.</p>
            </div>
            <div class="footer-section">
                <h2>USEFUL LINKS</h2>
                <ul>
                    <li><a href="#">My Account</a></li>
                    <li><a href="#">Annual Reports</a></li>
                    <li><a href="#">Customer Services</a></li>
                    <li><a href="#">Help</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h2>CONTACT</h2>
                <p>Colombo, Sri Lanka</p>
                <p>info@aqualink.lk</p>
                <p>+94 764 730 521</p>
                <p>+94 760 557 356</p>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Copyright: aqualink.lk</p>
            </div>
        </footer>
    </div>

    <script>
        function toggleAnswer(button) {
            const faqItem = button.closest('.faq-item');
            faqItem.classList.toggle('expanded');
        }
    </script>
</body>
</html>

