<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//Retrieve Accepted Appointments
$query = "SELECT * FROM appointments WHERE App_Status = 'accepted'";
$result = mysqli_query($conn, $query);

//Fetch Customer Information and Generate Invoice Data
$invoices = array();
while ($row = mysqli_fetch_assoc($result)) {
  // Fetch customer information from reg_users table
  $customer_query = "SELECT R_First_Name, R_Last_Name, Phone FROM reg_users WHERE Reg_UID = '{$row['User_ID']}'";
  $customer_result = mysqli_query($conn, $customer_query);
  $customer = mysqli_fetch_assoc($customer_result);

  // Generate Invoice ID and Number
  $invoice_id = $row['App_ID'];
  $invoice_number = count($invoices) + 1;

  // Insert invoice data into the database
  $insert_invoice_query = "INSERT INTO invoices (Invoice_ID, App_ID) VALUES (?, ?) 
                             ON DUPLICATE KEY UPDATE App_ID = VALUES(App_ID)";
  $stmt_insert_invoice = $conn->prepare($insert_invoice_query);
  $stmt_insert_invoice->bind_param("ii", $invoice_id, $row['App_ID']);
  $stmt_insert_invoice->execute();

  // Invoice Date is the date when the appointment was accepted
  $invoice_date = $row['Created_At'];

  $invoice_data = array(
    'Invoice_ID' => $invoice_id,
    'Invoice_Number' => $invoice_number,
    'Customer_Name' => $customer['R_First_Name'] . ' ' . $customer['R_Last_Name'],
    'Customer_Mobile' => $customer['Phone'],
    'Invoice_Date' => $invoice_date
  );

  $invoices[] = $invoice_data;
}

if (isset($_POST['system_invoiceid'])) {
  $search_invoice_id = $_POST['system_invoiceid'];

  // Filter invoices based on the provided invoice ID
  $filtered_invoices = array_filter($invoices, function ($invoice) use ($search_invoice_id) {
    return $invoice['Invoice_ID'] == $search_invoice_id;
  });
} else {
  $filtered_invoices = $invoices;
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <style>
    .container {
      width: 100%;
    }

    .form-container,
    .table-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .form-container {
      padding: 30px 50px 45px 50px;
      margin-top: 20px;
      border-radius: 20px;
      background-color: #f0d4fe;
    }

    .form-group {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .table-container {
      overflow-x: auto;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table th,
    .table td {
      white-space: nowrap;
      padding: 15px;
      text-align: center;
      border: 1px solid #000000;
    }

    .table th {
      background-color: #f9f3f3;
    }

    .table td:last-child,
    .table th:last-child {
      border-right: 1px solid #000000;
    }

    .view-button {
      background-color: #37005a;
      border-color: #37005a;
    }

    .view-button:hover {
      background-color: #1a001e;
      border-color: #1a001e;
    }
  </style>
</head>

<!--System Invoice Sub Component-->

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
        <a class="nav-item nav-link backend-nav active" href="be_system_invoice.php">Invoice Management</a>

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
        <h2>View Invoices</h2>
      </center>
      <br>
      <div class="form-container display-font">
        <form method="post">
          <table>
            <tr>
              <td style="width: 10%">
                <label id="system_invoiceid_label" for="system_invoiceid">Invoice ID:&nbsp;&nbsp;&nbsp;</label>
              </td>
              <td style="width: 80%">
                <input type="text" class="form-control" placeholder="xxxxxxxx" name="system_invoiceid"
                  id="system_invoiceid" required="true" />
              </td>
              <td style="margin-left: 20px">
                <button id="inquiryView-SearchButton" type="submit" class="btn btn-primary btn-lg buttonBE"
                  style="margin-left: 20px" href="">
                  Search
                </button>
              </td>
            </tr>
          </table>
        </form>
        <br />
        <div class="table-container">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Invoice ID</th>
                <th>Customer Name</th>
                <th>Customer Mobile Number</th>
                <th>Invoice Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($filtered_invoices as $invoice): ?>
                <tr>
                  <td><?= $invoice['Invoice_Number'] ?></td>
                  <td><?= $invoice['Invoice_ID'] ?></td>
                  <td><?= $invoice['Customer_Name'] ?></td>
                  <td><?= $invoice['Customer_Mobile'] ?></td>
                  <td><?= $invoice['Invoice_Date'] ?></td>
                  <td><a href="be_system_view_invoice.php?invoice_id=<?= $invoice['Invoice_ID'] ?>"
                      class="btn btn-primary view-button">View</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>

</html>