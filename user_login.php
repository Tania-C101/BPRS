<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Input validation
  if (isset($_POST['user_login_username']) && isset($_POST['user_login_password'])) {
    $username = sanitize_input($_POST['user_login_username']);
    $password = sanitize_input($_POST['user_login_password']);

    if (empty($username) || empty($password)) {
      echo "<script>alert('Username and password cannot be empty.');</script>";
      exit();
    }

    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "bprs";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $user = validate_user($username, $password, $conn);

    //User authentication
    if ($user) {
      //Starts a session and store values in the session variables
      session_start();
      // Set session variables
      $_SESSION['Username'] = $user['Username'];
      $_SESSION['Reg_UID'] = $user['Reg_UID'];

      echo "<script>alert('Login Successful!');window.location.href='regIndex.php';</script>";
    } else {
      echo "<script>alert('Invalid credentials. Please try again!'); window.location.href='user_login.php';</script>";
    }

    $conn->close();
  }
}

// Function to validate user credentials
function validate_user($username, $password, $conn)
{
  $query = "SELECT * FROM reg_users WHERE Username = ? LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    // Compare the provided password with the stored hashed password
    if (password_verify($password, $user['Password'])) {
      return $user;
    }
  }
  return false;
}

// Function to sanitize input
function sanitize_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Login - Locks&Curls</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <link rel="stylesheet" href="styles.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <style>
    .error {
      color: red;
      font-size: 0.875em;
    }
  </style>
</head>

<body class="home">

  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link" href="index.php">HOME</a>
    <a class="nav-item nav-link" href="about_us.php">ABOUT</a>
    <a class="nav-item nav-link" href="services.php">SERVICES</a>
    <a class="nav-item nav-link" href="contact.php">CONTACT</a>
    <a class="nav-item nav-link" href="sign_up.php">SIGN UP</a>
    <a class="nav-item nav-link active" href="user_login.php">LOGIN</a>
    <a class="nav-item nav-link" href="admin_login.php">ADMIN</a>
  </nav>

  <!--Up Panel-->
  <div class="container-image" style="text-align: center">
    <img src="images/sign-up.png" style="width: 100%" alt="Sign up page background image" />
    <div class="text" style="color: #ffffff; font-size: 60px">
      Account Login
    </div>
  </div>

  <!--Down Panel-->
  <section class="w3l-contact-info-main" id="contact">
    <div class="contact-sec">
      <div class="container">
        <div class="row">

          <!--Left Panel-->
          <div class="col-md-6">
            <div class="map-content-9 mt-lg-0 mt-4">
              <div style="padding-top: 30px; margin-right: 50px">
                <div class="contact-info" style="border-radius: 20px">
                  <br>
                  <div class="contact-item">
                    <div class="icon">
                      <i class="bi bi-telephone-fill custom-icon" style="padding-left: 30px"></i>
                    </div>
                    <div>
                      <h6 class="contact-section-label">Call Us</h6>
                      <p class="contact-detail-label">
                        <a href="tel:0112 878 774" style="color: black; text-decoration: none">0112 878 774</a>
                      </p>
                    </div>
                  </div>
                  <div class="contact-item">
                    <div class="icon">
                      <i class="bi bi-envelope-fill custom-icon" style="padding-left: 30px"></i>
                    </div>
                    <div>
                      <h6 class="contact-section-label">Email Us</h6>
                      <p class="contact-detail-label">
                        <a href="mailto:locksncurls2024@gmail.com"
                          style="color: black; text-decoration: none">locksncurls2024@gmail.com</a>
                      </p>
                    </div>
                  </div>
                  <div class="contact-item">
                    <div class="icon">
                      <i class="bi bi-geo-alt-fill custom-icon" style="padding-left: 30px"></i>
                    </div>
                    <div>
                      <h6 class="contact-section-label">Address</h6>
                      <p class="contact-detail-label">
                        No. 05, Park Road, Colombo
                      </p>
                    </div>
                  </div>
                  <div class="contact-item">
                    <div class="icon">
                      <i class="bi bi-clock-fill custom-icon" style="padding-left: 30px"></i>
                    </div>
                    <div>
                      <h6 class="contact-section-label">Time</h6>
                      <p class="contact-detail-label">
                        9:00 AM - 5:00 PM
                      </p>
                      <br>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <br>
            </div>
          </div>

          <!--Right Panel-->
          <div class="col-md-6" style="margin-top: 30px">
            <div class="map-content-9 mt-lg-0 mt-4">
              <form id="login_form" method="post" class="form-style">
                <div style="padding-top: 10px">
                  <h4>Login to Your Profile</h4>
                </div>
                <div style="padding-top: 20px">
                  <label for="user_login_username">Username</label>
                  <input type="text" class="form-control" placeholder="xxxxxx" name="user_login_username"
                    id="user_login_username" required="true" style="height: 50px" />
                </div>
                <div style="padding-top: 30px">
                  <label for="user_login_password">Password</label>
                  <input type="password" class="form-control new_password" placeholder="Password"
                    name="user_login_password" id="user_login_password" required="true" style="height: 50px" />
                </div>
                <br />
                <div style="padding-bottom: 30px">
                  <button type="submit" class="btn btn-primary btn-lg front-button" style="width: 200px;">
                    Log in
                  </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <button type="button" class="btn btn-primary btn-lg front-button" style="width: 200px;"
                    onclick="window.location.href='index.php';">
                    Cancel
                  </button>
                </div>
              </form>
            </div>
            <br>
            <br>
            <br>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="footerContainer">
      <div class="sec1">
        <p style="font-size: 24px">Contact Us</p>
        <p class="bi bi-geo-alt-fill ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No. 05, Park Road, Colombo</p>
        <p class="bi bi-envelope-fill">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;locksncurls2024@gmail.com</p>
        <p class="bi bi-telephone-fill">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0112 878 774</p>
      </div>
      <div class="sec2">
        <p style="font-size: 24px">Useful Links</p>
        <a class="footerLink bi bi-link-45deg" href="index.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</a><br />
        <a class="footerLink bi bi-link-45deg" href="about_us.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;About</a><br />
        <a class="footerLink bi bi-link-45deg" href="services.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Services</a><br />
        <a class="footerLink bi bi-link-45deg" href="contact.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contact</a><br />
      </div>
      <div class="sec3">
        <p style="font-size: 24px">About Us</p>
        <p style="line-height: 30px;">
          Locks & Curls Beauty Parlor offers expert hair, skin, and nail
          services in a relaxing environment. Our skilled team uses premium,
          eco-friendly products to ensure you look and feel your best. Visit
          us for personalized care and a rejuvenating experience.
        </p>
      </div>
    </div>
  </footer>
</body>

</html>