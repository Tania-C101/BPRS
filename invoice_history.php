<?php

session_start();

if (!isset($_SESSION['Reg_UID'])) {
  header("Location: user_login.php");
  exit();
}

$Reg_UID = $_SESSION['Reg_UID'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch invoices for the logged-in user
$sql = "SELECT i.Invoice_ID, i.App_ID, a.Created_At AS Invoice_Date, r.R_First_Name, r.R_Last_Name, r.Phone
        FROM invoices i
        JOIN appointments a ON i.App_ID = a.App_ID
        JOIN reg_users r ON a.User_ID = r.Reg_UID
        WHERE r.Reg_UID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $Reg_UID);
$stmt->execute();
$result = $stmt->get_result();

$invoices = array();

while ($row = $result->fetch_assoc()) {
  $invoice_data = array(
    'Invoice_ID' => $row['Invoice_ID'],
    'App_ID' => $row['App_ID'],
    'Invoice_Date' => $row['Invoice_Date'],
    'Customer_Name' => $row['R_First_Name'] . ' ' . $row['R_Last_Name'],
    'Customer_Mobile' => $row['Phone']
  );

  $invoices[] = $invoice_data;
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
  <title>Invoice History - Locks&Curls</title>
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

<!--Invoice history page viewable for Registered users-->

<body class="home">

  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link" href="regIndex.php">HOME</a>
    <a class="nav-item nav-link" href="regAbout_Us.php">ABOUT</a>
    <a class="nav-item nav-link" href="regServices.php">SERVICES</a>
    <a class="nav-item nav-link" href="regContact.php">CONTACT</a>
    <a class="nav-item nav-link" href="appointment.php">APPOINTMENT</a>
    <a class="nav-item nav-link" href="booking_history.php">BOOKING HISTORY</a>
    <a class="nav-item nav-link active" href="invoice_history.php">INVOICE HISTORY</a>
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
    <img src="images/invoice_history.png" style="width: 100%" alt="Invoice history background image" />
    <div class="text" style="color: #ffffff; font-size: 60px">
      Invoice History
    </div>
  </div>

  <!--Down Panel-->
  <div class="container mt-5">
    <h4 style="padding-bottom: 20px; text-align: center; color: black">
      Invoice History
    </h4>
    <table class="table table-bordered" style="border-color: black">
      <thead>
        <tr>
          <th class="text-center">#</th>
          <th class="text-center">Invoice ID</th>
          <th class="text-center">Customer Name</th>
          <th class="text-center">Customer Mobile Number</th>
          <th class="text-center">Invoice Date</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody style="font-size: 14px">
        <?php foreach ($invoices as $key => $invoice): ?>
          <tr>
            <td class='text-center'><?= $key + 1 ?></td>
            <td class='text-center'><?= $invoice['Invoice_ID'] ?></td>
            <td class='text-center'><?= $invoice['Customer_Name'] ?></td>
            <td class='text-center'><?= $invoice['Customer_Mobile'] ?></td>
            <td class='text-center'><?= $invoice['Invoice_Date'] ?></td>
            <td class='text-center'><a href="view_invoice.php?invoice_id=<?= $invoice['Invoice_ID'] ?>"
                class="btn btn-primary btn-view front-button">View</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
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
</body>

</html>