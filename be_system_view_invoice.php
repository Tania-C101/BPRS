<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : 0;

if ($invoice_id > 0) {
  // Query to fetch customer details and invoice date
  $query = "SELECT R.R_First_Name, R.R_Last_Name, R.Phone, R.R_Email, A.App_Date, A.Created_At
              FROM reg_users R
              INNER JOIN appointments A ON R.Reg_UID = A.User_ID
              WHERE A.App_ID = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $invoice_id);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if query was successful
  if ($result->num_rows > 0) {
    // Fetch customer details and invoice date
    $row = $result->fetch_assoc();
    $customer_first_name = $row['R_First_Name'];
    $customer_last_name = $row['R_Last_Name'];
    $customer_phone = $row['Phone'];
    $customer_email = $row['R_Email'];
    $appointment_date = $row['App_Date'];
    $invoice_date = date('Y-m-d', strtotime($row['Created_At']));
  } else {
    die("Error: No records found.");
  }

  // Fetch services related to the specified appointment
  $query_services = "SELECT App_Services FROM appointments WHERE App_ID = ?";
  $stmt_services = $conn->prepare($query_services);
  $stmt_services->bind_param("i", $invoice_id);
  $stmt_services->execute();
  $result_services = $stmt_services->get_result();

  $services = [];
  $service_names = [];
  $total_cost = 0;

  if ($result_services->num_rows > 0) {
    $row_services = $result_services->fetch_assoc();
    $service_names = explode(",", $row_services['App_Services']);

    foreach ($service_names as $service_name) {
      $service_name = trim($service_name);

      // Fetch service details for each service name
      $query_service_details = "SELECT Service_Name, Service_Cost FROM services WHERE Service_Name = ?";
      $stmt_service_details = $conn->prepare($query_service_details);
      $stmt_service_details->bind_param("s", $service_name);
      $stmt_service_details->execute();
      $result_service_details = $stmt_service_details->get_result();

      if ($result_service_details->num_rows > 0) {
        $row_service_details = $result_service_details->fetch_assoc();
        $services[] = [
          'Service_Name' => $row_service_details['Service_Name'],
          'Service_Cost' => $row_service_details['Service_Cost'],
        ];
        $total_cost += $row_service_details['Service_Cost'];
      } else {
        die("Error: Service '$service_name' not found.");
      }
    }

    // Aggregate service names into a single string
    $service_names_str = implode(", ", array_map(function ($service) {
      return $service['Service_Name'];
    }, $services));

    // Insert or update the aggregated service details and total cost into the invoices table
    $insert_invoice_query = "INSERT INTO invoices (Invoice_ID, Service_Name, Total_Price) VALUES (?, ?, ?)
                                 ON DUPLICATE KEY UPDATE Service_Name = VALUES(Service_Name), Total_Price = VALUES(Total_Price)";
    $stmt_insert_invoice = $conn->prepare($insert_invoice_query);
    $stmt_insert_invoice->bind_param("isd", $invoice_id, $service_names_str, $total_cost);
    $stmt_insert_invoice->execute();
  } else {
    die("Error fetching services: No services found for this appointment.");
  }
} else {
  die("Invalid invoice ID.");
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
  <style>
    #customer_details,
    #service_details {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #000000;
    }

    #first-column {
      background-color: #f9f3f3;
      border: 1px solid #000000;
    }

    td:last-child {
      border-right: 1px solid #000000;
    }

    /* Centering horizontally */
    .form-container {
      display: flex;
      justify-content: center;
      align-items: center;
      /* Center vertically */
    }

    /* Adjusting column widths */
    #customer-details table td,
    #service-details table td {
      width: 10%;
    }

    #service-details table td:first-child {
      width: 8%;
    }

    /* Separating columns with lines */
    td:not(:last-child) {
      border: 1px solid #020202;
    }

    /* Style for the image button */
    .image-button {
      background-color: transparent;
      border: none;
      padding: 0;
      cursor: pointer;
    }

    .image-button img {
      width: 50px;
      height: 50px;
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

<!--System Invoice Sub Component-->

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
        <h2>Invoice Details</h2>
      </center>
      <br />
      <div class="form-container display-font">
        <br />
        <div id="print-content">
          <!--Code for the table-->
          <form id="contact-form" action="#" method="post" style="
                  padding: 50px;
                  background-color: #f0d4fe;
                  border-radius: 10px;
                  width: 1100px;
                ">
            <!-- Container for customer details -->
            <div id="div_customer_details" class="table-container">
              <h5 id="invoice_number">
                Invoice Number </h5>
              <h6 style="padding-bottom: 30px">
                <?php echo $invoice_id; ?>
              </h6>
              <table id="customer_details" style="padding: 20px">
                <tr>
                  <th id="first-column" colspan="6">Customer Details</th>
                </tr>
                <tr>
                  <td>Name</td>
                  <td id="system_invoice_name">
                    <?php echo $customer_first_name . ' ' . $customer_last_name; ?>
                  </td>
                  <td>Contact Number</td>
                  <td id="system_invoice_contactNumber">
                    <?php echo $customer_phone; ?>
                  </td>
                  <td>Email</td>
                  <td id="system_invoice_email">
                    <?php echo $customer_email; ?>
                  </td>
                </tr>
                <tr>
                  <td>Appointment Date</td>
                  <td id="system_invoice_registrationDate">
                    <?php echo $appointment_date; ?>
                  </td>
                  <td>Invoice Date</td>
                  <td id="system_invoice_invoiceDate" colspan="3">
                    <?php echo $invoice_date; ?>
                  </td>
                </tr>
              </table>
            </div>
            <br />
            <br />
            <!-- Container for service details -->
            <div id="div_service_details" class="table-container">
              <table id="service_details" style="padding: 20px">
                <tr>
                  <th id="first-column" colspan="6">Service Details</th>
                </tr>
                <tr>
                  <td colspan="1">#</td>
                  <td colspan="3">Services</td>
                  <td colspan="2">Cost</td>
                </tr>
                <?php
                $count = 1;
                $total_cost = 0;
                foreach ($services as $service) {
                  $total_cost += $service['Service_Cost'];
                  echo "<tr>";
                  echo "<td colspan='1'>$count</td>";
                  echo "<td colspan='3'>" . $service['Service_Name'] . "</td>";
                  echo "<td colspan='2'>" . number_format($service['Service_Cost'], 2) . "</td>";
                  echo "</tr>";
                  $count++;
                }
                ?>
                <tr>
                  <td colspan="4" style="font-weight: bold">Grand Total</td>
                  <td id="cost" colspan="2">
                    <?php echo number_format($total_cost, 2); ?>
                  </td>
                </tr>
              </table>
            </div>
            <br />
            <div style="text-align: center; margin-top: 20px">
              <button type="button" class="image-button" onclick="printInvoice()">
                <i class="fa fa-print fa-3x" style="font-size: 26px"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      function printInvoice() {
        window.print();
      }
    </script>
  </div>
  </div>
</body>

</html>