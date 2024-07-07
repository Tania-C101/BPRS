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

// Check if user is logged in
if (!isset($_SESSION['Username'])) {
  echo "User is not logged in!";
  exit;
}

$employee_username = $_SESSION['Username'];

// Retrieve employee ID based on the username
$emp_result = mysqli_query($conn, "SELECT Emp_ID FROM employees WHERE Username = '$employee_username'");
if (!$emp_result || mysqli_num_rows($emp_result) == 0) {
  echo "Employee not found!";
  exit;
}

$emp_row = mysqli_fetch_array($emp_result);
$emp_id = $emp_row['Emp_ID'];

$error_message = '';

if (isset($_POST['appointmentBESubmit'])) {
  if (isset($_GET['viewid'])) {
    $viewid = $_GET['viewid'];
    $status = $_POST['appointmentStatus'];

    if ($status == "Pending") {
      $error_message = "Please assign a valid status!";
    } else {
      $update_sql = mysqli_query($conn, "UPDATE appointments SET App_Status = '$status', Emp_ID = '$emp_id' WHERE App_ID='$viewid'");
      if ($update_sql) {
        echo '<script>alert("Appointment Status updated!");</script>';
      } else {
        echo '<script>alert("Failed to update the status. Please try again.");</script>';
      }
    }
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
  <link
    href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
    rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</head>

<!--Appointment View Page-->

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
          <a class="nav-item nav-link backend-nav btn dropdown-toggle active" href="" role="button"
            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            Appointment Management
          </a>

          <!--Sub Components Link-->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li>
              <a class="dropdown-item  active" href="be_appointment.php">Manage Appointments</a>
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

    <!--Right Panel-->
    <div class="right-Panel">
      <div class="table-responsive bs-example widget-shadow">
        <br>
        <center>
          <h2>View Appointments</h2>
        </center>
        <br>
        <div class="display-panel-inner display-font">
          <br>
          <br>
          <?php

          if (isset($_GET['viewid'])) {
            $viewid = $_GET['viewid'];
            $retrieve_sql = mysqli_query($conn, "SELECT reg_users.R_First_Name, reg_users.R_Last_Name, reg_users.R_Email, reg_users.Phone, appointments.App_Date, appointments.App_Time, appointments.App_Services, appointments.App_Status, appointments.App_ID FROM appointments JOIN reg_users ON reg_users.Reg_UID = appointments.User_ID WHERE appointments.App_ID = '$viewid'");
            $row = mysqli_fetch_array($retrieve_sql);
          } else {
            // Handle the case where viewid is not set
            echo "View ID not set.";
          }
          ?>
          <table class="table table-bordered">
            <tr>
              <td><label>Appointment Number</label></td>
              <td>
                <div class="inner-row"><?php echo $row['App_ID']; ?></div>
              </td>
              <td><label>Appointment Date</label></td>
              <td>
                <div class="inner-row"><?php echo $row['App_Date']; ?></div>
              </td>
            </tr>
            <tr style="height: 20px">
              <td></td>
            </tr>
            <tr>
              <td><label>Client Name</label></td>
              <td>
                <div class="inner-row">
                  <?php echo $row['R_First_Name'] . " " . $row['R_Last_Name']; ?>
                </div>
              <td><label>Appointment Time</label></td>
              <td>
                <div class="inner-row"><?php echo $row['App_Time']; ?></div>
              </td>
              </td>
            </tr>
            <tr style="height: 20px">
              <td></td>
            </tr>
            <tr>
              <td><label>Mobile Number</label></td>
              <td>
                <div class="inner-row"><?php echo $row['Phone']; ?></div>
              </td>
              <td><label>Email</label></td>
              <td>
                <div class="inner-row"><?php echo $row['R_Email']; ?></div>
              </td>
            </tr>
            <tr style="height: 20px">
              <td></td>
            </tr>
            <tr>
              <td><label>Requested Services</label></td>
              <td>
                <div class="inner-row" style="width:300px; height: 150px">
                  <?php echo $row['App_Services']; ?>
                </div>
              </td>
            </tr>
            <tr style="height: 20px">
              <td></td>
            </tr>
            <tr>
              <td>Appointment Status</td>
              <td>
                <?php
                if ($row['App_Status'] == "Pending") {
                  echo "Status pending";
                } elseif ($row['App_Status'] == "Accepted") {
                  echo "Appointment Accepted";
                } elseif ($row['App_Status'] == "Rejected") {
                  echo "Appointment Rejected";
                }
                ?>
              </td>
            </tr>
          </table>
          <table class="table table-bordered">
            <?php if ($row['App_Status'] == "Pending") { ?>
              <form name="submit" method="post" enctype="multipart/form-data">
                <tr>
                  <td style="width: 200px">Assign Status</td>
                  <td style="width: 322px">
                    <select name="appointmentStatus" class="form-control wd-400" required="true">
                      <option value="Pending">--SELECT--</option>
                      <option value="Accepted">Accept</option>
                      <option value="Rejected">Reject</option>
                    </select>
                    <?php if (!empty($error_message)) { ?>
                      <div class="text-danger"><?php echo $error_message; ?></div>
                    <?php } ?>
                  </td>
                  <td></td>
                  <td></td>
                </tr>
                <tr style="height: 50px">
                  <td></td>
                </tr>
                <tr align="center">
                  <td style="width: 200px"></td>
                  <td>
                    <button type="submit" name="appointmentBESubmit" class="btn btn-primary buttonBE">Submit</button>
                    <a href="be_appointment.php" style="text-decoration:none; color:#ffffff">
                      <button type="button" class="btn btn-primary buttonBE">Back</button>
                    </a>
                  </td>
                  <td>
                  <td>
                  </td>
                  <td></td>
                </tr>
              </form>
            <?php } ?>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>

</html>