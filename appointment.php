<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

session_start();

if (!isset($_SESSION['Reg_UID'])) {

  header("Location: user_login.php");
  exit;
}

$user_id = $_SESSION['Reg_UID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $app_date = $_POST['appDate'];
  $app_time = $_POST['appTime'];

  $today = date('Y-m-d');
  if ($app_date < $today) {
    echo "<script>alert('Please select a date today or after.'); window.location.href = 'appointment.php'; </script>";
    exit;
  }

  // Server-side validation for time (between 9:00 AM and 5:00 PM)
  $selected_time = strtotime($app_time);
  $start_time = strtotime('09:00:00');
  $end_time = strtotime('17:00:00');

  if ($selected_time < $start_time || $selected_time > $end_time) {
    echo "<script>alert('Please select a time between 9:00 AM and 5:00 PM.'); window.location.href = 'appointment.php'; </script>";
    exit;
  }

  if (isset($_POST['options'])) {
    $services = $_POST['options'];
  }

  $created_at = date('Y-m-d H:i:s');

  $stmt = $conn->prepare("INSERT INTO appointments (App_Date, App_Time, App_Services, App_Status, User_ID, Created_At) VALUES (?, ?, ?, 'Pending', ?, ?)");
  $services_str = implode(',', $services); // Convert array to comma-separated string
  $stmt->bind_param("sssis", $app_date, $app_time, $services_str, $user_id, $created_at);

  if ($stmt->execute()) {
    echo "<script>alert('New Appointment request sent successfully!'); window.location.href = 'appointment.php'; </script>";
  } else {
    echo "<script>alert('Error: Sending appointment request failed. Please try again later!');</script>";
  }

  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Appointments - Locks&Curls</title>
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

<!--Appointment page viewable for Registered users-->

<body class="home">

  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link" href="regIndex.php">HOME</a>
    <a class="nav-item nav-link" href="regAbout_Us.php">ABOUT</a>
    <a class="nav-item nav-link" href="regServices.php">SERVICES</a>
    <a class="nav-item nav-link" href="regContact.php">CONTACT</a>
    <a class="nav-item nav-link active" href="appointment.php">APPOINTMENT</a>
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
  <div class="container-image" style="text-align: center">
    <img src="images/appointment.png" style="width: 100%" alt="Appointment page background image" />
    <div class="text" style="color: #ffffff; font-size: 60px">
      Appointment Booking
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
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <div class="side-image">
                <center>
                  <img src="images/appStaff.jpg" alt="salon staff" />
                </center>
              </div>
            </div>
          </div>

          <!--Right Panel-->
          <div class="col-md-6">
            <div class="map-content-9 mt-lg-0 mt-4">
              <br />
              <label style="margin: 20px; text-align: center; line-height: 30px">Please fill the below form and submit.
                One of our Salon
                Professionals will get back to you at the earliest to discuss
                further details about your appointment</label>
              <form action="" method="post" class="form-style">
                <div style="padding-top: 30px">
                  <label>Appointment Date</label>
                  <br />
                  <br />
                  <input type="date" class="form-control appointment_date" placeholder="Date" name="appDate"
                    id="appDate" value="" style="font-size:14px;" />
                </div>
                <div style="padding-top: 30px">
                  <label>Appointment Time</label>
                  <br />
                  <br />
                  <input type="time" class="form-control appointment_time" placeholder="Time" name="appTime"
                    id="appTime" value="" style="font-size:14px;" />
                </div>
                <div style="padding-top: 30px">
                  <label>Select Required Services</label>
                  <br />
                  <br />

                  <!--Hair services-->

                  <div id="appServicesLeft" style="margin-top: 10px">
                    <div id="hairServices">
                      <label class="appServLabel">Hair Services</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Haircut" />
                      <label for="options">Hair Cut</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Hair Highlight" />
                      <label for="options">Hair Highlight</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Hair Relaxing" />
                      <label for="options">Hair Relaxing</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Hair Rebonding" />
                      <label for="options">Hair Rebonding</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Hair Braiding" />
                      <label for=" options">Hair Braiding</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Keratin Therapy" />
                      <label for="options">Keratin Therapy</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Hair Treatments" />
                      <label for="options">Hair Treatments</label>
                      <br />
                      <br />
                    </div>
                    <br />

                    <!--Waxing services-->

                    <div id="waxingServices">
                      <label class="appServLabel">Waxing Services</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Full-body Waxing" />
                      <label for="options">Full-body Waxing</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Legs Waxing" />
                      <label for="options">Legs Waxing</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Arms Waxing" />
                      <label for="options">Arms Waxing</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Facial Waxing" />
                      <label for="options">Facial Waxing</label>
                      <br />
                      <br />
                      <br />
                      <br />
                    </div>
                  </div>

                  <!--Facial services-->

                  <div id="appServicesRight" style="margin-top: 10px">
                    <div id="facialServices">
                      <label class="appServLabel">Facial Services</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Facial Cleanup" />
                      <label for="options">Facial Cleanup</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Facial Treatments" />
                      <label for="options">Facial Treatments</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Brightening Treatments">
                      <label for="options">Brightening Treatments</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Threading" />
                      <label for="options">Threading</label>
                      <br />
                      <br />
                      <input type="checkbox" id="options" name="options[]" value="Makeup" />
                      <label for="options">Makeup</label>
                      <br />
                      <br />
                    </div>
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />

                    <!--Nail services-->

                    <div id="nailServices">
                      <label class="appServLabel">Nail Services</label>
                      <br />
                      <br />
                      <input type="checkbox" id="manicure" name="options[]" value="Manicure" />
                      <label for="manicure">Manicure</label>
                      <br />
                      <br />
                      <input type="checkbox" id="pedicure" name="options[]" value="Pedicure" />
                      <label for="pedicure">Pedicure</label>
                      <br />
                      <br />
                      <input type="checkbox" id="nail_art" name="options[]" value="Nail Art" />
                      <label for="nail_art">Nail Art</label>
                      <br />
                      <br />
                    </div>
                  </div>
                </div>
                <br />
                <br />
                <div style="
                      padding: 30px;
                      text-align: center;
                      margin: 420px 275px 10px -60px;
                    ">
                  <button type="submit" id="app_Submit_Button" class="btn btn-primary btn-lg front-button"
                    style="width:200px">
                    Make an Appointment
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <br />
  <br>
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

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      function handleNotification() {
        // Handle the notification (update content, refresh data, etc.)
        console.log('Notification received');
        location.reload();
      }

      setInterval(function () {
        fetch('notify.php')
          .then(response => response.json())
          .then(data => {
            if (data.message === 'Notification received') {
              handleNotification();
            }
          })
          .catch(error => console.error('Error checking for notifications:', error));
      }, 600000); d
    });

    const form = document.querySelector('form');

    // Function to handle form submission validation
    form.addEventListener('submit', function (event) {
      const appDate = document.getElementById('appDate').value;
      const appTime = document.getElementById('appTime').value;
      const checkboxes = document.querySelectorAll('input[name="options[]"]:checked');

      // Validate date
      if (!appDate) {
        alert('Please select a date!');
        event.preventDefault();
        return false;
      }

      // Validate time
      if (!appTime) {
        alert('Please select a time!');
        event.preventDefault();
        return false;
      }

      // Validate date
      const today = new Date().toISOString().split('T')[0];
      if (appDate < today) {
        alert('Please select an upcoming date!');
        event.preventDefault();
        return false;
      }

      // Validate time (between 9:00 AM and 5:00 PM)
      const selectedTime = new Date(`2000-01-01T${appTime}`);
      const startTime = new Date(`2000-01-01T09:00:00`);
      const endTime = new Date(`2000-01-01T17:00:00`);

      if (selectedTime < startTime || selectedTime > endTime) {
        alert('Please select a time between 9:00 AM and 5:00 PM!');
        event.preventDefault();
        return false;
      }

      // Validate at least one service selected
      if (checkboxes.length === 0) {
        alert('Please select at least one service!');
        event.preventDefault();
        return false;
      }
      return true; // Form submission allowed
    });

  </script>
</body>

</html>