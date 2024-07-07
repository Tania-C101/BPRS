<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch the from-date and to-date parameters from the URL
$from_date = $_GET['from-date'];
$to_date = $_GET['to-date'];

// Query to fetch sales data within the specified date range
$sql = "SELECT MONTHNAME(App_Date) AS Month, YEAR(App_Date) AS Year, SUM(Total_Price) AS TotalSales 
        FROM invoices 
        INNER JOIN appointments ON invoices.App_ID = appointments.App_ID 
        WHERE App_Date BETWEEN '$from_date' AND '$to_date' 
        GROUP BY YEAR(App_Date), MONTH(App_Date)";

$result = $conn->query($sql);

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

    .sales-table {
      width: 100%;
      border-collapse: collapse;
    }

    .sales-table th,
    .sales-table td {
      border: 1px solid #000;
      padding: 8px;
      text-align: left;
    }

    .sales-table th {
      background-color: #f2f2f2;
    }

    .sales-table .total-row td {
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

<!--Sales Report Sub Component-->

<body class="home">
  <div class="profile-bar">
    <div class="sec3">
      <h5 id="back-end-title">
        <center>RESERVATION MANAGEMENT SYSTEM - SALON LOCKS & CURLS</center>
      </h5>
    </div>
    <div class="sec4">
      <a class="nav-item nav-link dropdown-toggle" data-bs-toggle="dropdown">Manager</a>
      <ul class="dropdown-menu settings-dropdown-menu">
        <li class="dropdown-tab">
          <a class="dropdown-items dropdown-link settings-dropdown-items" href="index.php">Logout</a>
        </li>
        <br>
        <li class="dropdown-tab">
          <a class="dropdown-items dropdown-link settings-dropdown-items" href="be_manager_change_password.php"
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
              <a class="dropdown-item" href="be_manage_employee_profile.php">Manage Employee Profile</a>
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
              <a class="dropdown-item " href="be_appointment_report.php">Appointment Report</a>
            </li>
            <li>
              <a class="dropdown-item active" href="be_sales_report.php">Sales Report</a>
            </li>
          </ul>
        </div>
      </nav>
    </div>

    <!--Right Panel-->
    <div class="right-Panel">
      <br>
      <br>
      <div id="print-content">
        <div class="report-title">
          <h2>Sales Report</h2>
        </div>
        <br>
        <div class="container-right-panel display-font">
          <br>
          <br>
          <div class="report-subtitle">
            <h4>Sales Report from <?php echo $from_date; ?> to <?php echo $to_date; ?></h4>
          </div>
          <div class="table-container">
            <table class="sales-table" id="sales-report-table">
              <tr>
                <th>Sale Number</th>
                <th>Month/Year</th>
                <th>Sales</th>
              </tr>
              <?php
              $sale_number = 1;
              $total_sales = 0;

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $month_year = $row['Month'] . ' ' . $row['Year'];
                  $sales = $row['TotalSales'];
                  $total_sales += $sales;
                  ?>
                  <tr>
                    <td><?php echo $sale_number++; ?></td>
                    <td><?php echo $month_year; ?></td>
                    <td><?php echo $sales; ?></td>
                  </tr>
                  <?php
                }
              } else {
                echo "<tr><td colspan='3'>No sales found</td></tr>";
              }
              ?>
              <tr class="total-row">
                <td colspan="2">Total</td>
                <td><?php echo number_format($total_sales, 2); ?></td>
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
<?php
$conn->close();
?>