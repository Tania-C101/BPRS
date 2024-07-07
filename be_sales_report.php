<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $from_date = $_POST['from-date'];
  $to_date = $_POST['to-date'];
  $report_type = "Sales";

  $username = $_SESSION['Username'];

  $emp_id_query = $conn->prepare("SELECT Emp_ID FROM employees WHERE Username = ?");
  $emp_id_query->bind_param("s", $username);
  $emp_id_query->execute();
  $emp_id_result = $emp_id_query->get_result();

  if ($emp_id_result->num_rows > 0) {
    $row = $emp_id_result->fetch_assoc();
    $emp_id = $row['Emp_ID'];

    $invoice_query = $conn->prepare("SELECT Invoice_ID FROM invoices 
                          INNER JOIN appointments ON invoices.App_ID = appointments.App_ID 
                          WHERE App_Date BETWEEN ? AND ?");
    $invoice_query->bind_param("ss", $from_date, $to_date);
    $invoice_query->execute();
    $invoice_result = $invoice_query->get_result();

    if ($invoice_result->num_rows > 0) {
      while ($invoice_row = $invoice_result->fetch_assoc()) {
        $invoice_id = $invoice_row['Invoice_ID'];

        $insert_report_query = $conn->prepare("INSERT INTO reports (Report_Type, Gen_Date, Emp_ID, Invoice_ID) 
                                        VALUES (?, CURDATE(), ?, ?)");
        $insert_report_query->bind_param("sis", $report_type, $emp_id, $invoice_id);

        if ($insert_report_query->execute() === TRUE) {
        } else {
          echo "Error: " . $insert_report_query->error;
        }
      }
    } else {
      echo "No invoices found for the selected date range.";
    }
  } else {
    echo "Employee not found.";
  }

  echo "<script>window.location.href='be_sales_report_detail.php?from-date=$from_date&to-date=$to_date';</script>";
  exit;
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
    /*right panel */
    .right-Panel {
      flex-grow: 1;
      padding: 20px;
    }

    /*form container */
    .form-container {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 1000px;
      margin: auto;
      text-align: center;
    }

    /*form header */
    .form-container h2 {
      margin-bottom: 20px;
    }

    /*form groups */
    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }

    /*form labels */
    .form-group label {
      display: block;
      margin-bottom: 5px;
    }

    /*date input fields */
    .form-group input[type="date"] {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    /*submit button */
    .form-group input[type="submit"] {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      background-color: #37005a;
      color: white;
      cursor: pointer;
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
          <a class="nav-item nav-link backend-nav btn dropdown-toggle" href="inquiry_mgt.php" role="button"
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
          <a class="nav-item nav-link backend-nav btn dropdown-toggle" href="" role="button" id="dropdownMenuLink"
            data-bs-toggle="dropdown" aria-expanded="false">
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
      <div class="form-container display-font">
        <br>
        <h2>Sales Report</h2>
        <form id="reportForm" action="" method="POST">
          <div class="form-group">
            <label for="from-date">From date</label>
            <input type="date" id="report2_from_date" name="from-date">
          </div>
          <div class="form-group">
            <label for="to-date">To date</label>
            <input type="date" id="report2_to_date" name="to-date">
          </div>
          <div class="form-group">
            <input type="submit" id="report2_submitButton" value="Submit">
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    document.getElementById('reportForm').addEventListener('submit', function (event) {
      event.preventDefault(); // Prevent form submission

      var fromDate = document.getElementById('report2_from_date').value;
      var toDate = document.getElementById('report2_to_date').value;

      if (!fromDate || !toDate) {
        alert('Please select both dates.');
        return;
      }

      // Debugging: Ensure these values are correct
      console.log('From Date:', fromDate);
      console.log('To Date:', toDate);

      // Submit form to update database
      this.submit();
    });
  </script>
</body>

</html>