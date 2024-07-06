<?php

session_start();

if (!isset($_SESSION['Reg_UID'])) {
  header("Location: user_login.php");
  exit();
}

$Reg_UID = $_SESSION['Reg_UID'];

if (!isset($_GET['App_ID'])) {
  echo "No appointment ID provided.";
  exit();
}

$App_ID = $_GET['App_ID'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch appointment details with user information
$sql = "SELECT a.App_ID, a.App_Date, a.App_Time, a.App_Status, DATE(a.Created_At) AS Created_At, r.R_First_Name, r.R_Last_Name, r.R_Email, r.Phone
FROM appointments a
INNER JOIN reg_users r ON a.User_ID = r.Reg_UID
WHERE a.App_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $App_ID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $appointment = $result->fetch_assoc();
} else {
  echo "No appointment found with the given ID.";
  exit();
}

$stmt->close();
$conn->close();

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
    td:not(:last-child) {
      border: 1px solid #020202;
    }

    td:nth-child(2) {
      border-top: 1px solid #020202;
    }

    td:nth-child(odd) {
      font-weight: bold;
    }

    #view_app,
    #service_details {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #000000;
    }

    #first-column {
      background-color: #f9f3f3;
      border: 1px solid #000000;
    }

    td:last-child {
      border-right: 1px solid #000000;
    }

    .form-container {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    /* Adjusting column widths */
    #view_app table td,
    #service-details table td {
      width: 10%;
    }

    #service-details table td:first-child {
      width: 8%;
    }

    /* Separating columns with lines */
    td:not(:last-child) {
      border: 1px solid #020202;
    }

    /* Style for the image button */
    .image-button {
      background-color: transparent;
      border: none;
      padding: 0;
      cursor: pointer;
    }

    .image-button img {
      width: 50px;
      height: 50px;
    }

    .btn-secondary {
      background-color: #4a007a;
      border-color: #4a007a;
    }

    .btn-secondary:hover {
      background-color: #4a007a;
      border-color: #4a007a;
    }

    @media print {
      body * {
        visibility: hidden;
      }

      #print-content,
      #print-content * {
        visibility: visible;
      }

      #print-content {
        position: absolute;
        left: 0;
        top: 0;
      }

      .image-button {
        display: none;
      }
    }
  </style>
</head>

<!--View Appointment History for Registered Users-->

<body class="home">
  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link" href="regIndex.php">HOME</a>
    <a class="nav-item nav-link" href="regAbout_Us.php">ABOUT</a>
    <a class="nav-item nav-link" href="regServices.php">SERVICES</a>
    <a class="nav-item nav-link" href="regContact.php">CONTACT</a>
    <a class="nav-item nav-link" href="appointment.php">APPOINTMENT</a>
    <a class="nav-item nav-link active" href="booking_history.php">BOOKING HISTORY</a>
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
    <a class="nav-item nav-link profile-nav" href="index.php"
      onclick="return confirm('Are you sure you want to logout?');" style="color: white;">LOGOUT</a>
  </nav>

  <!--Up Panel-->
  <div class="container-image" style="text-align: center">
    <img src="images/booking_history.png" style="width: 100%" alt="Booking history background image" />
    <div class="text" style="color: #ffffff; font-size: 60px">
      Appointment Details
    </div>
  </div>

  <!--Down Panel-->
  <div class="container mt-5">
    <h4 style="padding-bottom: 20px; text-align: center; color: black">
      Appointment Details
    </h4>
    <div class="form-container">
      <br />
      <div id="print-content">
        <!--Code for the table-->
        <form id="contact-form" action="#" method="post" style="
                padding: 50px;
                background-color: #f0d4fe;
                border-radius: 10px;
                width: 1100px;
                ">
          <!-- Container for appointment details -->
          <div id="div_view_app" class="table-container">
            <table id="view_app" style="padding: 20px">
              <tr>
                <th id="first-column" colspan="6" style="font-size: 15px;">Appointment Details -
                  <?php echo $appointment['App_ID']; ?>
                </th>
              </tr>
              <tr>
                <td>Appointment Number</td>
                <td><?php echo $appointment['App_ID']; ?></td>
              </tr>
              <tr>
                <td>Name</td>
                <td><?php echo $appointment['R_First_Name'] . ' ' . $appointment['R_Last_Name']; ?></td>
              </tr>
              <tr>
                <td>Email</td>
                <td><?php echo $appointment['R_Email']; ?></td>
              </tr>
              <tr>
                <td>Mobile Number</td>
                <td><?php echo $appointment['Phone']; ?></td>
              </tr>
              <tr>
                <td>Appointment Date</td>
                <td><?php echo $appointment['App_Date']; ?></td>
              </tr>
              <tr>
                <td>Appointment Time</td>
                <td><?php echo $appointment['App_Time']; ?></td>
              </tr>
              <tr>
                <td>Applied Date/Time</td>
                <td><?php echo $appointment['Created_At']; ?></td>
              </tr>
              <tr>
                <td>Status</td>
                <td><?php echo $appointment['App_Status']; ?></td>
              </tr>
            </table>
            <br>
            <center><a href="javascript:history.back()" class="btn btn-secondary">Go Back</a></center>
          </div>
        </form>
      </div>
    </div>
  </div>
  <br>
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