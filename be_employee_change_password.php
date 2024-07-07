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

if (isset($_SESSION['Emp_ID'])) {
  $Emp_ID = $_SESSION['Emp_ID'];

  try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = $db->prepare("SELECT Password FROM employees WHERE Emp_ID = :Emp_ID");
    $query->bindParam(':Emp_ID', $Emp_ID, PDO::PARAM_INT);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
} else {
  echo "<script>alert('Employee ID is not set in session.')</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $current_password = $_POST['current_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  // Password validation pattern
  $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/';

  // Validate new password
  $errors = [];
  if (!preg_match($passwordPattern, $new_password)) {
    $errors[] = "New password must be at least 6 characters long and contain at least one uppercase letter, one lowercase letter and one digit.";
  }

  if (password_verify($current_password, $user['Password'])) {
    if ($new_password === $confirm_password) {
      if ($current_password === $new_password) {
        $errors[] = "The new password cannot be the same as the current password. Please choose a different password.";
      }

      if (empty($errors)) {
        $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);

        $update_query = $db->prepare("UPDATE employees SET Password = :new_password WHERE Emp_ID = :Emp_ID");
        $update_query->bindParam(':new_password', $new_password_hash, PDO::PARAM_STR);
        $update_query->bindParam(':Emp_ID', $Emp_ID, PDO::PARAM_INT);

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
  <title>Admin Panel - Locks&Curls</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
  <link rel="stylesheet" href="styles.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900&display=swap"
    rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</head>

<!--Manage System User Profile Sub Component Page-->

<body class="home">
  <div class="profile-bar">
    <div class="sec3">
      <h5 id="back-end-title">
        <center>RESERVATION MANAGEMENT SYSTEM - SALON LOCKS & CURLS</center>
      </h5>
    </div>
    <div class="sec4">
      <a class="nav-item nav-link dropdown-toggle" data-bs-toggle="dropdown">Admin</a>
      <ul class="dropdown-menu settings-dropdown-menu">
        <li class="dropdown-tab">
          <a class="dropdown-items dropdown-link settings-dropdown-items" href="index.php">Logout</a>
        </li>
        <br>
        <li class="dropdown-tab">
          <a class="dropdown-items dropdown-link settings-dropdown-items active" href="be_employee_change_password.php"
            id="change-password-link">Change
            Password</a>
        </li>
      </ul>
    </div>
  </div>

  <!--Backend Admin Panel Menu Guide-->
  <div class="main-container">
    <div class="left-Panel">
      <nav class="navbar navbar-light justify-content-start vertical-menu">
        <a class="nav-item nav-link backend-nav" href="be_dashboard.php">Dashboard</a>

        <!--Appointment Management Tab-->
        <div class="dropdown">

          <!--Main Component Link-->
          <a class="nav-item nav-link backend-nav btn dropdown-toggle" href="" role="button" id="dropdownMenuLink"
            data-bs-toggle="dropdown" aria-expanded="false">
            Appointment Management
          </a>

          <!--Sub Components Link-->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li>
              <a class="dropdown-item" href="be_appointment.php">Manage Appointments</a>
            </li>
            <li>
              <a class="dropdown-item" href="be_accepted_app.php">Accepted Appointments</a>
            </li>
            <li>
              <a class="dropdown-item" href="be_rejected_app.php">Rejected Appointments</a>
            </li>
          </ul>
        </div>
        <br />
        <br />

        <!--Invoice Management Tab-->
        <a class="nav-item nav-link backend-nav" href="be_system_invoice.php">Invoice Management</a>

        <!--Inquiry Management Tab-->
        <div class="dropdown">

          <!--Main Component Link-->
          <a class="nav-item nav-link backend-nav btn dropdown-toggle" href="be_inquiry_mgt.php" role="button"
            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            Inquiry Management
          </a>

          <!--Sub Components Link-->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li>
              <a class="dropdown-item" href="be_inquiry_mgt.php">Manage Inquiries</a>
            </li>
            <li>
              <a class="dropdown-item" href="be_inquiry_view.php">View Inquiries</a>
            </li>
          </ul>
        </div>
        <br />
        <br />

        <!--Content Management Tab-->
        <div class="dropdown">

          <!--Main Component Link-->
          <a class="nav-item nav-link backend-nav btn dropdown-toggle" href="be_manage_services.php" role="button"
            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            Content Management
          </a>
          <!--Sub Components Link-->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li>
              <a class="dropdown-item" href="be_manage_services.php">Manage Services</a>
            </li>
            <li>
              <a class="dropdown-item" href="be_manage_services_view.php">View Services</a>
            </li>
            <li>
              <a class="dropdown-item" href="be_content_mgt.php">Manage Page Content</a>
            </li>
          </ul>
        </div>

        <div class="dropdown">

          <!--Main Component Link-->
          <a class="nav-item nav-link backend-nav btn dropdown-toggle" href="be_manage_system_users.php" role="button"
            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            Profile Management
          </a>

          <!--Sub Components Link-->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li>
              <a class="dropdown-item" href="be_manage_system_users.php">Manage System Users</a>
            </li>
            <li>
              <a class="dropdown-item" href="be_manage_employee_profile.php">Manage Employee Profile</a>
            </li>
            <li>
              <a class="dropdown-item" href="be_manage_client_profile.php">Manage Client Profile</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>

  <!--Right Panel-->
  <div class="right-Panel">
    <br>
    <center>
      <h2>Change Password</h2>
    </center>
    <br>
    <form method="post" class="form-style" style="margin-top:10px;">
      <div>
        <h4 style="color: #37005a;">Change Password</h4>
      </div>
      <div style="padding-top: 20px">
        <label style="font-size: 14px; color:black;" for="current_password">Current Password</label>
        <br />
        <input type="password" class="form-control current_password" placeholder="Enter your current password"
          name="current_password" id="current_password" required="true" style="font-size: 14px" />
      </div>
      <div style="padding-top: 30px">
        <label style="font-size: 14px; color:black;" for="new_password">New Password</label>
        <br />
        <input type="password" class="form-control new_password" placeholder="Enter your new password"
          name="new_password" id="new_password" required="true" style="font-size: 14px" />
      </div>
      <div style="padding-top: 30px">
        <label style="font-size: 14px; color:black;" for="confirm_password">Confirm Password</label>
        <br />
        <input type="password" class="form-control confirm_password" placeholder="Confirm your new password"
          name="confirm_password" id="confirm_password" required="true" style="font-size: 14px" />
      </div>
      <div style="padding-top: 30px; text-align: left">
        <button type="submit" class="btn btn-primary btn-lg settings-update-btn" id="update-password-btn">
          Update Password
        </button>
      </div>
    </form>
  </div>
</body>

</html>