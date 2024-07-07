<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
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

<!--Appointment Main Page-->

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
      <br>
      <center>
        <h2>Manage Appointments</h2>
      </center>
      <br />
      <div class="table-responsive bs-example widget-shadow display-panel-inner display-font">
        <br>
        <table class="inquiryTable">
          <thead>
            <tr>
              <th class="inquiryTableRowHead tableOutline" style="width: 10px;">
                App #</th>
              <th class="inquiryTableRowHead tableOutline" style="width: 200px">
                Name</th>
              <th class="inquiryTableRowHead tableOutline" style="width: 70px">
                Mobile
              </th>
              <th class="inquiryTableRowHead tableOutline" style="width: 90px">
                App. Date
              </th>
              <th class="inquiryTableRowHead tableOutline" style="width: 90px">
                App. Time</th>
              <th class="inquiryTableRowHead tableOutline" style="width: 150px">Services
                Requested</th>
              <th class="inquiryTableRowHead tableOutline" style="width: 90px">Status
              </th>
              <th class="inquiryTableRowHead tableOutline" style="width: 90px">Action
              </th>
            </tr>
          </thead>
          <tbody class="borderBE">
            <?php
            $retrieve_sql = mysqli_query($conn, "SELECT reg_users.R_First_Name, reg_users.R_Last_Name, reg_users.Phone, appointments.App_ID, appointments.App_Date, appointments.App_Time, appointments.App_Services, appointments.App_Status FROM appointments JOIN reg_users ON reg_users.Reg_UID = appointments.User_ID WHERE appointments.App_Status='Pending'");
            $count = 1;

            while ($row = mysqli_fetch_array($retrieve_sql)) {
              ?>
              <tr>
                <td class="inquiryTableRow"><?php echo $row['App_ID']; ?></td>
                <td class="inquiryTableRow">
                  <?php echo $row['R_First_Name'] . " "; ?>   <?php echo $row['R_Last_Name']; ?>
                </td>
                <td class="inquiryTableRow"><?php echo $row['Phone']; ?></td>
                <td class="inquiryTableRow"><?php echo $row['App_Date']; ?></td>
                <td class="inquiryTableRow"><?php echo $row['App_Time']; ?></td>
                <td class="inquiryTableRow"><?php echo $row['App_Services']; ?></td>

                <!--Appointment status column-->
                <?php if ($row['App_Status'] == "") { ?>
                  <td class="font-w600 inquiryTableRow"><?php echo "Pending"; ?></td>
                <?php } else { ?>
                  <td class="font-w600 inquiryTableRow"><?php echo $row['App_Status']; ?></td><?php } ?>

                <!--Action column-->
                <td class="inquiryTableRow">
                  <a id="view_Button" href="be_appointment_view_extend.php?viewid=<?php echo $row['App_ID']; ?>"
                    class="btn btn-primary buttonBE">View</a>
                </td>
              </tr>
              <?php
              $count = $count + 1;
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>

</html>