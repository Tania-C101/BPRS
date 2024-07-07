<?php

session_start();

if (!isset($_SESSION['Reg_UID'])) {
  header("Location: user_login.php");
  exit;
}

$Reg_UID = $_SESSION['Reg_UID'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

try {
  $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Fetch user data
  $query = $db->prepare("SELECT R_First_Name, R_Last_Name, R_Email, Phone FROM reg_users WHERE Reg_UID = :Reg_UID");
  $query->bindParam(':Reg_UID', $Reg_UID, PDO::PARAM_INT);
  $query->execute();
  $user = $query->fetch(PDO::FETCH_ASSOC);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input values
    $first_name = $_POST['user_fname'];
    $last_name = $_POST['user_lname'];
    $mobile = $_POST['user_mobile'];
    $email = $_POST['user_email'];
    $message = $_POST['user_message'];
    $user_type = 'Registered';

    $insert_query = $db->prepare("INSERT INTO inquiries (I_First_Name, I_Last_Name, I_Email, I_Mobile, Message, User_ID, User_Type) VALUES (:first_name, :last_name, :email, :mobile, :message, :Reg_UID, :user_type)");
    $insert_query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $insert_query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $insert_query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $insert_query->bindParam(':email', $email, PDO::PARAM_STR);
    $insert_query->bindParam(':message', $message, PDO::PARAM_STR);
    $insert_query->bindParam(':Reg_UID', $Reg_UID, PDO::PARAM_INT);
    $insert_query->bindParam(':user_type', $user_type, PDO::PARAM_STR);

    if ($insert_query->execute()) {
      echo "<script>alert('Inquiry successfully submitted!');</script>";
    } else {
      echo "<script>alert('Failed to submit inquiry. Please try again!');</script>";
    }
  }
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Contact Us- Locks&Curls</title>
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
    .dropdown-menu {
      position: absolute;
      top: 100%;
      left: 0;
      transform: translateY(0);
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
      border: none;
    }

    .dropdown-menu a.dropdown-items {
      padding: 10px 20px;
      font-size: 14px;
      color: #333;
    }

    .dropdown-menu a.dropdown-items:hover {
      background-color: #f8f9fa;
      color: white;
    }

    .dropdown-menu a.dropdown-items.active {
      background-color: #a600fa;
      color: white;
    }
  </style>
</head>

<!--Contact page viewable for Registered users-->

<body class="home">

  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link" href="regIndex.php">HOME</a>
    <a class="nav-item nav-link" href="regAbout_Us.php">ABOUT</a>
    <a class="nav-item nav-link" href="regServices.php">SERVICES</a>
    <a class="nav-item nav-link active" href="regContact.php">CONTACT</a>
    <a class="nav-item nav-link" href="appointment.php">APPOINTMENT</a>
    <a class="nav-item nav-link" href="booking_history.php">BOOKING HISTORY</a>
    <a class="nav-item nav-link" href="invoice_history.php">INVOICE HISTORY</a>
    <div class="dropdown">
      <a class="nav-item nav-link dropdown-toggle" data-bs-toggle="dropdown">PROFILE SETTINGS</a>
      <ul class="dropdown-menu">
        <li class="dropdown-tab">
          <a class="dropdown-item dropdown-link" href="edit_profile.php">Edit Profile</a>
        </li>
        <li class="dropdown-tab">
          <a class="dropdown-item dropdown-link" href="change_password.php" id="change-password-link">Change
            Password</a>
        </li>
      </ul>
    </div>
    <a class="nav-item nav-link" href="index.php" onclick="return confirm('Are you sure you want to logout?');"
      style="color: white;">LOGOUT</a>
  </nav>

  <!--Up Panel-->
  <div class="container-image" style="text-align: center; width: 100%">
    <img src="images/contact.png" style="width: 100%" alt="Contact page backround image" />
    <div class="text" style="color: #ffffff; font-size: 60px">Contact Us</div>
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
              <label style="margin: 20px; line-height: 30px; text-align: center">
                Please fill out the below inquiry form to know more about our
                services or to provide any feedback. Our staff will attend to
                your inquiry at the earliest.</label>
              <br />
              <form method="post" class="form-style">
                <?php if (!empty($user)): ?>
                  <div>
                    <div class="form-group twice-two">
                      <input type="text" class="form-control" id="user_fname" name="user_fname"
                        value="<?php echo htmlspecialchars($user['R_First_Name']); ?>" placeholder="First Name"
                        required="" style="font-size: 14px" />
                      <input type="text" class="form-control" id="user_lname" name="user_lname"
                        value="<?php echo htmlspecialchars($user['R_Last_Name']); ?>" placeholder="Last Name" required=""
                        style="font-size: 14px" />
                    </div>
                    <div class="form-group twice-two">
                      <input type="email" class="form-control" id="user_email" name="user_email"
                        value="<?php echo htmlspecialchars($user['R_Email']); ?>" placeholder="Email" required=""
                        style="font-size: 14px" />
                      <input type="text" class="form-control" id="user_mobile" name="user_mobile"
                        value="<?php echo htmlspecialchars($user['Phone']); ?>" placeholder="Phone" required=""
                        pattern="[0-9]+" maxlength="10" style="font-size: 14px" />
                    </div>
                    <div class="form-group">
                      <textarea class="form-control" id="user_message" name="user_message" placeholder="Message"
                        required="" style="font-size: 14px"></textarea>
                    </div>
                  </div>
                  <div>
                    <button type="submit" class="btn btn-primary btn-lg front-button" id="sendRegMessageButton">
                      Send Message
                    </button>
                  </div>
                <?php endif; ?>
              </form>
            </div>
            <br />
            <br />
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

  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
      document.getElementById('sendRegMessageButton').addEventListener('click', (event) => {
        checkValidation(event);
      });
    });

    function checkValidation() {
      let fname = document.getElementById("fnamer").value;
      let lname = document.getElementById("lnamer").value;
      let email = document.getElementById("emailr").value;
      let phone = document.getElementById("phoner").value;
      let message = document.getElementById("messager").value;

      // Regular expressions for validation
      let nameRegex = /^[A-Za-z]+$/;
      let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      let phoneRegex = /^\d{9}$/;

      if (!fname || !lname || !email || !phone || !message) {
        alert("One or more fields are empty!");
      } else if (!nameRegex.test(fname)) {
        alert("First name must contain only letters A-Z or a-z!");
      } else if (!nameRegex.test(lname)) {
        alert("Last name must contain only letters A-Z or a-z!");
      } else if (!emailRegex.test(email)) {
        alert("Invalid email format!");
      } else if (!phoneRegex.test(phone)) {
        alert("Phone number must be 10 digits!");
      } else {
        alert("Message sent successfully!");
      }
    }
  </script>
</body>

</html>