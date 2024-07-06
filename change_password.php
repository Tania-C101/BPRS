<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['Reg_UID'])) {
  $Reg_UID = $_SESSION['Reg_UID'];

  try {
    // Create a PDO instance for database operations
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = $db->prepare("SELECT Password FROM reg_users WHERE Reg_UID = :Reg_UID");
    $query->bindParam(':Reg_UID', $Reg_UID, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
} else {
  echo "<script>alert('User ID is not set in session.')</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $current_password = $_POST['current_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  // Password validation pattern
  $passwordPattern = '/^(?=.*[A-Z])(?=.*\d).{8,}$/';

  // Validate new password
  $errors = [];
  if (!preg_match($passwordPattern, $new_password)) {
    $errors[] = "New password must be at least 8 characters long and contain at least one uppercase letter and one digit.";
  }

  if (password_verify($current_password, $user['Password'])) {
    if ($new_password === $confirm_password) {
      if (empty($errors)) {
        $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);

        $update_query = $db->prepare("UPDATE reg_users SET Password = :new_password WHERE Reg_UID = :Reg_UID");
        $update_query->bindParam(':new_password', $new_password_hash, PDO::PARAM_STR);
        $update_query->bindParam(':Reg_UID', $Reg_UID, PDO::PARAM_INT);

        if ($update_query->execute()) {
          echo "<script>alert('Password successfully updated!');</script>";
        } else {
          echo "<script>alert('Failed to update password. Please try again!');</script>";
        }
      } else {
        foreach ($errors as $error) {
          echo "<script>alert('$error');</script>";
        }
      }
    } else {
      echo "<script>alert('New password and confirm password do not match!');</script>";
    }
  } else {
    echo "<script>alert('Current password is incorrect!');</script>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Change Password - Locks&Curls</title>
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
</head>

<!--Change password page viewable for Registered users-->

<body class="home">

  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link" href="regIndex.php">HOME</a>
    <a class="nav-item nav-link" href="regAbout_Us.php">ABOUT</a>
    <a class="nav-item nav-link" href="regServices.php">SERVICES</a>
    <a class="nav-item nav-link" href="regContact.php">CONTACT</a>
    <a class="nav-item nav-link" href="appointment.php">APPOINTMENT</a>
    <a class="nav-item nav-link" href="booking_history.php">BOOKING HISTORY</a>
    <a class="nav-item nav-link" href="invoice_history.php">INVOICE HISTORY</a>
    <div class="dropdown">
      <a class="nav-item nav-link dropdown-toggle active" data-bs-toggle="dropdown">PROFILE SETTINGS</a>
      <ul class="dropdown-menu">
        <li>
          <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
        </li>
        <li>
          <a class="dropdown-item active" href="change_password.php" id="change-password-link">Change Password</a>
        </li>
      </ul>
    </div>
    <a class="nav-item nav-link profile-nav" href="index.php"
      onclick="return confirm('Are you sure you want to logout?');" style="color: white;">LOGOUT</a>
    <div id="change-password-form" style="display: none"></div>
  </nav>

  <!--Up Panel-->
  <div class="container-image" style="text-align: center">
    <img src="images/change_pw.png" style="width: 100%" alt="Change password page background image" />
    <div class="text" style="color: #ffffff; font-size: 60px">
      Change Password
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
              <div style="padding-top: 30px; margin-right: 50px;">
                <div class="contact-info" style="border-radius: 20px">
                  <br>
                  <br>
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
                        <br>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <br>
            </div>
          </div>

          <!--Right Panel-->
          <div class="col-md-6">
            <div class="map-content-9 mt-lg-0 mt-4">
              <br />
              <form method="post" class="form-style" style="margin-top:10px;">
                <div>
                  <h4>Change Password</h4>
                </div>
                <div style="padding-top: 20px">
                  <label style="font-size: 14px" for="current_password">Current Password</label>
                  <br />
                  <input type="password" class="form-control current_password" placeholder="Enter your current password"
                    name="current_password" id="current_password" required="true" style="font-size: 14px" />
                  <?php
                  // Check if form is submitted and current password is incorrect
                  if ($_SERVER['REQUEST_METHOD'] === 'POST' && !password_verify($current_password, $user['Password'])) {
                    echo "<span style='color: red; font-size: 12px;'>Current password is incorrect.</span>";
                  }
                  ?>
                </div>
                <div style="padding-top: 30px">
                  <label style="font-size: 14px" for="new_password">New Password</label>
                  <br />
                  <input type="password" class="form-control new_password" placeholder="Enter your new password"
                    name="new_password" id="new_password" required="true" style="font-size: 14px" />
                  <?php if (!empty($errors) && !preg_match($passwordPattern, $new_password)) {
                    echo "<span style='color: red; font-size: 12px;'>New password must be at least 8 characters long and contain at least one uppercase letter and one digit.</span>";
                  } ?>
                </div>
                <div style="padding-top: 30px">
                  <label style="font-size: 14px" for="confirm_password">Confirm Password</label>
                  <br />
                  <input type="password" class="form-control confirm_password" placeholder="Confirm your new password"
                    name="confirm_password" id="confirm_password" required="true" style="font-size: 14px" />
                  <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $new_password !== $confirm_password) {
                    echo "<span style='color: red; font-size: 12px;'>New password and confirm password do not match.</span>";
                  } ?>
                </div>
                <div style="padding-top: 30px; text-align: left">
                  <button type="submit" class="btn btn-primary btn-lg" style="
                  background-color: #a600fa;
                  color: white;
                  border-color: #000000;
                  border-radius: 10px;
                  border-style: none;
                  font-size: 14px;
                ">
                    Update Password
                  </button>
                </div>
              </form>
            </div>
            <br />
            <br />
          </div>
        </div>
      </div>
    </div>
  </section>
  <br>
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
        <a class="footerLink bi bi-link-45deg" href="regIndex.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</a><br />
        <a class="footerLink bi bi-link-45deg" href="regAbout_us.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;About</a><br />
        <a class="footerLink bi bi-link-45deg" href="regServices.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Services</a><br />
        <a class="footerLink bi bi-link-45deg" href="regContact.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contact</a><br />
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