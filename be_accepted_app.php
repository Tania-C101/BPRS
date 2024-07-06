<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$userDetails = [];
$stmt_app = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $search_app_id = $_POST['search_app_id'];

  $sql = "SELECT reg_users.Reg_UID, reg_users.R_First_Name, reg_users.R_Last_Name, reg_users.Phone, appointments.App_ID, appointments.App_Date,
                            appointments.App_Time, appointments.App_Services, appointments.App_Status, appointments.Emp_ID, employees.Emp_ID
                      FROM
                      appointments
                      LEFT JOIN 
                      reg_users ON appointments.User_ID = reg_users.Reg_UID
                      LEFT JOIN 
                      employees ON appointments.Emp_ID = employees.Emp_ID   
                      WHERE appointments.App_ID = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $search_app_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $userDetails = $result->fetch_assoc();
  } else {
    echo "<script>alert('No record found with the provided Appointment ID');</script>";
  }
  $stmt->close();
} else {

  $sql_app = "SELECT reg_users.Reg_UID, reg_users.R_First_Name, reg_users.R_Last_Name, reg_users.Phone, appointments.App_ID, appointments.App_Date, appointments.App_Time, appointments.App_Services, appointments.App_Status, appointments.Emp_ID, employees.Emp_ID FROM
              appointments
              LEFT JOIN 
              reg_users ON appointments.User_ID = reg_users.Reg_UID
              LEFT JOIN 
              employees ON appointments.Emp_ID = employees.Emp_ID   
              WHERE appointments.App_Status = 'Accepted'";
  $stmt_app = $conn->query($sql_app);
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
</head>

<!--Accepted Appointments Sub Page-->

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
              <a class="dropdown-item" href="be_appointment.php">Manage Appointments</a>
            </li>
            <li>
              <a class="dropdown-item active" href="be_accepted_app.php">Accepted Appointments</a>
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

    <!--Right panel-->
    <div class="right-Panel">
      <br>
      <center>
        <h2>Accepted Appointments</h2>
      </center>
      <br>
      <div class="form-container inquiryView">
        <form method="post" action="">
          <br>
          <br>
          <div class="form-group">
            <table>
              <tr>
                <td style="width:150px"><label>Appointment ID:</label></td>
                <td style="width:230px"><input type="text" class="form-control" placeholder="xxxxxxxx"
                    name="search_app_id" id="search_app_id" required="true" style="height: 40px; width: 200px;" />
                </td>
                <td> <button type="submit" class="btn btn-primary btn-lg buttonBE">
                    Search
                  </button></td>
              </tr>
            </table>
          </div>
        </form>
        <div class="table-container">
          <table class="table table-bordered inquiryTable">
            <thead>
              <tr>
                <th class="text-center">App #</th>
                <th class="text-center">Name</th>
                <th class="text-center">Mobile</th>
                <th class="text-center">App. Date</th>
                <th class="text-center">App. Time</th>
                <th class="text-center">Services</th>
                <th class="text-center">Status</th>
                <th class="text-center">Emp ID</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($stmt_app) {
                while ($row = $stmt_app->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td class='text-center'>" . htmlspecialchars($row['App_ID']) . "</td>";
                  echo "<td class='text-center'>" . htmlspecialchars($row['R_First_Name'] . " " . $row['R_Last_Name']) . "</td>";
                  echo "<td class='text-center'>" . htmlspecialchars($row['Phone']) . "</td>";
                  echo "<td class='text-center'>" . htmlspecialchars($row['App_Date']) . "</td>";
                  echo "<td class='text-center'>" . htmlspecialchars($row['App_Time']) . "</td>";
                  echo "<td class='text-center'>" . htmlspecialchars($row['App_Services']) . "</td>";
                  echo "<td class='text-center'>" . htmlspecialchars($row['App_Status']) . "</td>";
                  echo "<td class='text-center'>" . htmlspecialchars($row['Emp_ID']) . "</td>";
                  echo "</tr>";
                }
              } elseif (!empty($userDetails)) {
                // Display user details if searched
                echo "<tr>";
                echo "<td class='text-center'>" . htmlspecialchars($userDetails['App_ID']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($userDetails['R_First_Name'] . " " . $userDetails['R_Last_Name']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($userDetails['Phone']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($userDetails['App_Date']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($userDetails['App_Time']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($userDetails['App_Services']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($userDetails['App_Status']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($userDetails['Emp_ID']) . "</td>";
                echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>

</html>