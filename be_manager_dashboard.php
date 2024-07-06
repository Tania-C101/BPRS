<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$month = isset($_POST['month']) ? $_POST['month'] : date('Y-m');

$query = "SELECT
            (SELECT COUNT(*) FROM appointments WHERE MONTH(App_Date) = MONTH('$month-01') AND YEAR(App_Date) = YEAR('$month-01')) AS totalAppointments,
            (SELECT COUNT(*) FROM appointments WHERE App_Status = 'Accepted' AND MONTH(App_Date) = MONTH('$month-01') AND YEAR(App_Date) = YEAR('$month-01')) AS acceptedAppointments,
            (SELECT COUNT(*) FROM appointments WHERE App_Status = 'Rejected' AND MONTH(App_Date) = MONTH('$month-01') AND YEAR(App_Date) = YEAR('$month-01')) AS rejectedAppointments,
            (SELECT COUNT(*) FROM reg_users WHERE MONTH(R_Registered_Date) = MONTH('$month-01') AND YEAR(R_Registered_Date) = YEAR('$month-01')) AS newUsersMonth,
            (SELECT COUNT(*) FROM reg_users) AS totalUsers,
            (SELECT COUNT(*) FROM inquiries WHERE MONTH(Date_Received) = MONTH('$month-01') AND YEAR(Date_Received) = YEAR('$month-01')) AS inquiriesMonth,
            (SELECT COUNT(*) FROM inquiries) AS totalInquiries";

$result = $conn->query($query);

if ($result) {
  $data = $result->fetch_assoc();
} else {
  die("Error: " . $conn->error);
}

$conn->close();

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
  <style>
    /* Styling for the right panel */
    .container {
      margin: 20px 20px 20px 0px;
      display: flex;
      flex-wrap: wrap;
      background-color: white;
    }
  </style>
</head>

<!--Backend Dashboard Main Page-->

<body class="home">
  <div class="profile-bar">
    <div class="sec3">
      <h5 id="back-end-title">
        <center>RESERVATION MANAGEMENT SYSTEM - SALON LOCKS & CURLS</center>
      </h5>
    </div>
    <div class="sec4">
      <a class="nav-item nav-link profile-nav" href="index.php"
        onclick="return confirm('Are you sure you want to logout?');" style="color: white;">LOGOUT</a>
    </div>
  </div>

  <!--Backend Admin Panel Menu Guide-->
  <div class="main-container">
    <div class="left-Panel">
      <nav class="navbar navbar-light justify-content-start vertical-menu">
        <a class="nav-item nav-link backend-nav active" href="be_manager_dashboard.php">Dashboard</a>

        <!--Inquiry Management Tab-->
        <div class="dropdown">

          <!--Main Component Link-->
          <a class="nav-item nav-link backend-nav btn dropdown-toggle" href="be_manager_inquiry_mgt.php" role="button"
            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            Inquiry Management
          </a>

          <!--Sub Components Link-->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li>
              <a class="dropdown-item" href="be_manager_inquiry_mgt.php">Manage Inquiries</a>
            </li>
            <li>
              <a class="dropdown-item" href="be_manager_inquiry_view.php">View Inquiries</a>
            </li>
          </ul>
        </div>
        <br />
        <br />

        <div class="dropdown">

          <!--Main Component Link-->
          <a class="nav-item nav-link backend-nav btn dropdown-toggle" href="be_manager_manage_system_users.php"
            role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            Profile Management
          </a>

          <!--Sub Components Link-->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li>
              <a class="dropdown-item" href="be_manager_manage_system_users.php">Manage System Users</a>
            </li>
            <li>
              <a class="dropdown-item" href="be_manager_manage_employee_profile.php">Manage Employee Profile</a>
            </li>
            <li>
              <a class="dropdown-item" href="be_manager_manage_client_profile.php">Manage Client Profile</a>
            </li>
          </ul>
        </div>
        <div class="dropdown">

          <!--Main Component Link-->
          <a class="nav-item nav-link backend-nav btn dropdown-toggle" href="" role="button" id="dropdownMenuLink"
            data-bs-toggle="dropdown" aria-expanded="false">
            Reports
          </a>

          <!--Sub Components Link-->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li>
              <a class="dropdown-item" href="be_appointment_report.php">Appointment Report</a>
            </li>
            <li>
              <a class="dropdown-item" href="be_sales_report.php">Sales Report</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>

  <!--Right Panel-->
  <div class="right-Panel">
    <center>
      <h1>Dashboard</h1>
    </center>
    <br>
    <div class="form-group">
      <label for="monthSelect">Select Month:</label>
      <input type="month" name="month" id="month" class="form-control" style="width: 300px;"
        onchange="updateDashboard()" />
    </div>
    <div class="container display-panel-inner">
      <div class="box" id="box1">
        <h5>Total Appointments</h5><span class="stat"><?php echo $data['totalAppointments']; ?></span>
      </div>
      <div class="box" id="box2">
        <h5>Accepted Appointments</h5><span class="stat"><?php echo $data['acceptedAppointments']; ?></span>
      </div>
      <div class="box" id="box3">
        <h5>Rejected Appointments</h5><span class="stat"><?php echo $data['rejectedAppointments']; ?></span>
      </div>
      <div class="box" id="box4">
        <h5>Total Registered Users</h5><span class="stat"><?php echo $data['totalUsers']; ?></span>
      </div>
      <div class="box" id="box5">
        <h5>New Registered Users (Month)</h5><span class="stat"><?php echo $data['newUsersMonth']; ?></span>
      </div>
      <div class="box" id="box6">
        <h5>Total Inquiries</h5><span class="stat"><?php echo $data['totalInquiries']; ?></span>
      </div>
      <div class="box" id="box7">
        <h5>Inquiries (Month)</h5><span class="stat"><?php echo $data['inquiriesMonth']; ?></span>
      </div>
    </div>
  </div>
  </div>

  <script>
    function updateDashboard() {
      const month = document.getElementById('month').value;
    }
  </script>
</body>

</html>