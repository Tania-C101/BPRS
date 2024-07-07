<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testers";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Retrieve the from-date and to-date from the URL parameters
$fromDate = $_GET['from-date'];
$toDate = $_GET['to-date'];

// Query to fetch appointments within the selected time frame
$query = "SELECT App_ID, App_Date FROM appointments WHERE App_Date BETWEEN '$fromDate' AND '$toDate' AND App_Status = 'accepted'";
$result = mysqli_query($conn, $query);

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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <style>
    /* Styling for the right panel */
    .right-Panel {
      flex-grow: 1;
      padding: 20px;
    }

    .table-container {
      margin-top: 20px;
    }

    .appointments-table {
      width: 100%;
      border-collapse: collapse;
    }

    .appointments-table th,
    .appointments-table td {
      border: 1px solid #000;
      padding: 8px;
      text-align: left;
    }

    .appointments-table th {
      background-color: #f2f2f2;
    }

    .appointments-table .total-row td {
      font-weight: bold;
    }

    .container-right-panel {
      max-width: 900px;
      margin: 0 auto;
      background-color: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .report-title {
      text-align: center;
      margin-bottom: 20px;
    }

    .image-button {
      background-color: transparent;
      border: none;
      padding: 0;
      cursor: pointer;
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

<!--Reports Main Page-->

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
        <a class="nav-item nav-link backend-nav" href="be_manager_dashboard.php">Dashboard</a>

        <!-- Inquiry Management Tab -->
        <div class="dropdown">

          <!-- Main Component Link -->
          <a class="nav-item nav-link backend-nav btn dropdown-toggle" href="be_manager_inquiry_mgt.php" role="button"
            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            Inquiry Management
          </a>

          <!-- Sub Components Link -->
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

          <!-- Main Component Link -->
          <a class="nav-item nav-link backend-nav btn dropdown-toggle" href="be_manager_manage_system_users.php"
            role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            Profile Management
          </a>

          <!-- Sub Components Link -->
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
          <a class="nav-item nav-link backend-nav btn dropdown-toggle active" href="" role="button"
            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            Reports
          </a>

          <!--Sub Components Link-->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li>
              <a class="dropdown-item active" href="be_appointment_report.php">Appointment Report</a>
            </li>
            <li>
              <a class="dropdown-item" href="be_sales_report.php">Sales Report</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>

    <!-- Right Panel -->
    <div class="right-Panel">
      <div id="print-content">
        <div class="report-title">
          <br>
          <br>
          <h2>Appointments Report</h2>
        </div>
        <br>
        <br>
        <div class="container-right-panel display-font">
          <div class="report-subtitle">
            <h4>
              Appointments Report from <span id="appointments_from_date"><?php echo $fromDate; ?></span> to <span
                id="appointments_to_date"><?php echo $toDate; ?></span>
            </h4>
          </div>
          <div class="table-container">
            <table class="appointments-table" id="appointments-report-table">
              <tr>
                <th>Appointment Number</th>
                <th>Month/Year</th>
                <th>Appointment</th>
              </tr>
              <?php
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>" . $row['App_ID'] . "</td>
                          <td>" . date('m/Y', strtotime($row['App_Date'])) . "</td>
                          <td>" . $row['App_Date'] . "</td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='3'>No appointments found for the selected date range.</td></tr>";
              }
              ?>
              <tr class="total-row">
                <td colspan="2" id="appointments_total">Total</td>
                <td><?php echo $result->num_rows; ?></td>
              </tr>
            </table>
          </div>
          <div style="text-align: center; margin-top: 20px;">
            <button type="button" class="image-button" onclick="printInvoice()">
              <i class="fa fa-print fa-3x" style="font-size: 26px; border: none;"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function printInvoice() {
      window.print();
    }
  </script>
</body>

</html>