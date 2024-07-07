<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
  $title = $_POST['title'];
  $page_desc = $_POST['page_desc'];

  $title = $conn->real_escape_string($title);
  $page_desc = $conn->real_escape_string($page_desc);

  // Insert data into the content table
  $sql = "INSERT INTO content (Page_Title, Page_Desc) VALUES ('$title', '$page_desc')";

  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Page updated successfully!')</script>";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
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
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
    rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</head>

<!--Content Management Main Page-->

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
          <a class="dropdown-items dropdown-link settings-dropdown-items" href="be_employee_change_password.php"
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
          <a class="nav-item nav-link backend-nav btn dropdown-toggle active" href="be_manage_services.php"
            role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
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
              <a class="dropdown-item active" href="be_content_mgt.php">Manage Page Content</a>
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

    <!--Right Panel-->
    <div class="right-Panel">
      <br>
      <center>
        <h2>Page Management</h2>
      </center>
      <br>
      <div class="display-panel-inner">
        <form method="post" class="display-panel-inner display-font" onsubmit="return validateForm()">
          <label>Page Title</label>
          <input type="text" class="form-control" name="title" id="title" required>
          <div id="titleError" class="error-display"></div>
          <br>
          <br>
          <label>Page Description</label>
          <textarea name="page_desc" id="page_desc" rows="5" class="form-control"></textarea>
          <div id="pageDescError" class="error-display"></div>
          <br>
          <br>
          <button type="submit" name="submit" class="btn btn-primary buttonBE">Update</button>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <button type="button" class="btn btn-primary buttonBE"
            onclick="window.location.href='be_dashboard.php';">Back</button>
        </form>

        <script>
          function validateForm() {
            // Clear previous error messages
            document.getElementById('titleError').innerHTML = '';
            document.getElementById('pageDescError').innerHTML = '';

            // Get form values
            var title = document.getElementById('title').value.trim();
            var pageDesc = document.getElementById('page_desc').value.trim();

            var isValid = true;

            // Validate title
            if (title === '') {
              document.getElementById('titleError').innerHTML = 'This field is required.';
              isValid = false;
            }

            // Validate page description
            if (pageDesc === '') {
              document.getElementById('pageDescError').innerHTML = 'This field is required.';
              isValid = false;
            }

            return isValid;
          }
        </script>
</body>

</html>