<?php

session_start();
if (!isset($_SESSION['Reg_UID'])) {
  header('Location: user_login.php');
  exit;
}

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

  if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
  }

  $stmt->bind_param("i", $invoice_id);
  $stmt->execute();
  $result = $stmt->get_result();


  if ($result->num_rows > 0) {
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

  if (!$stmt_services) {
    die("Error preparing statement: " . $conn->error);
  }

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

      $query_service_details = "SELECT Service_Name, Service_Cost, Service_ID FROM services WHERE Service_Name = ?";
      $stmt_service_details = $conn->prepare($query_service_details);

      if (!$stmt_service_details) {
        die("Error preparing statement: " . $conn->error);
      }

      $stmt_service_details->bind_param("s", $service_name);
      $stmt_service_details->execute();
      $result_service_details = $stmt_service_details->get_result();

      if ($result_service_details->num_rows > 0) {
        $row_service_details = $result_service_details->fetch_assoc();
        $services[] = [
          'Service_Name' => $row_service_details['Service_Name'],
          'Service_Cost' => $row_service_details['Service_Cost'],
          'Service_ID' => $row_service_details['Service_ID']
        ];
        $total_cost += $row_service_details['Service_Cost'];
      } else {
        die("Error: Service '$service_name' not found.");
      }
    }

    $service_names_str = implode(", ", array_map(function ($service) {
      return $service['Service_Name'];
    }, $services));

    $insert_invoice_query = "INSERT INTO invoices (Invoice_ID, Service_Name, Total_Price) VALUES (?, ?, ?)
                                 ON DUPLICATE KEY UPDATE Service_Name = VALUES(Service_Name), Total_Price = VALUES(Total_Price)";
    $stmt_insert_invoice = $conn->prepare($insert_invoice_query);

    if (!$stmt_insert_invoice) {
      die("Error preparing statement: " . $conn->error);
    }

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
  <title>Login - Locks&Curls</title>
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
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

    .btn-secondary {
      background-color: #4a007a;
      border-color: #4a007a;
    }

    .btn-secondary:hover {
      background-color: #4a007a;
      border-color: #4a007a;
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

    @media print {
      .print-hidden {
        display: none;
      }
    }
  </style>
</head>

<!--View Invoice for Registered Users-->

<body class="home">
  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link" href="regIndex.php">HOME</a>
    <a class="nav-item nav-link" href="regAbout_Us.php">ABOUT</a>
    <a class="nav-item nav-link" href="regServices.php">SERVICES</a>
    <a class="nav-item nav-link" href="regContact.php">CONTACT</a>
    <a class="nav-item nav-link" href="appointment.php">APPOINTMENT</a>
    <a class="nav-item nav-link" href="booking_history.php">BOOKING HISTORY</a>
    <a class="nav-item nav-link active" href="invoice_history.php">INVOICE HISTORY</a>
    <div class="dropdown">
      <a class="nav-item nav-link dropdown-toggle" data-bs-toggle="dropdown">PROFILE SETTINGS</a>
      <ul class="dropdown-menu">
        <li class="dropdown-tab">
          <a class="dropdown-item dropdown-link" href="edit_profile.php">Edit Profile</a>
        </li>
        <li class="dropdown-tab">
          <a class="dropdown-item dropdown-link" href="change_password.php" id="change-password-link">Change
            Password</a>
        </li>
      </ul>
    </div>
    <a class="nav-item nav-link profile-nav" href="index.php"
      onclick="return confirm('Are you sure you want to logout?');" style="color: white;">LOGOUT</a>
  </nav>

  <!--Up Panel-->
  <div class="container-image" style="text-align: center">
    <img src="images/invoice_history.png" style="width: 100%" alt="Invoice history background image" />
    <div class="text" style="color: #ffffff; font-size: 60px">
      Invoice History
    </div>
  </div>

  <!--Down Panel-->
  <div class="container mt-5">
    <h4 style="padding-bottom: 20px; text-align: center; color: black">
      Invoice History
    </h4>
    <div class="form-container">
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
                <td id="system_invoice_appointmentDate">
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

          <!-- Container for service details -->
          <div id="div_service_details" class="table-container">
            <table id="service_details" style="padding: 20px">
              <tr>
                <th id="first-column" colspan="6">Service Details</th>
              </tr>
              <tr>
                <td>#</td>
                <td>Services</td>
                <td>Cost</td>
              </tr>
              <?php
              $counter = 1; // Initialize a counter
              foreach ($services as $service): ?>
                <tr>
                  <td><?php echo $counter; ?></td> <!-- Display the counter -->
                  <td><?php echo $service['Service_Name']; ?></td>
                  <td><?php echo $service['Service_Cost']; ?></td>
                </tr>
                <?php
                $counter++; // Increment the counter for the next service
              endforeach; ?>
              <tr>
                <td colspan="2"><b>Total Cost</b></td>
                <td><?php echo number_format($total_cost, 2); ?></td>
              </tr>
            </table>
          </div>
          <br />

          <!-- Print and Go Back buttons -->
          <center>
            <button type="button" class="image-button" onclick="window.print()">
              <i class="fa fa-print fa-3x" style="font-size: 26px"></i>
            </button>
            <br>
            <br>
            <div class="print-hidden">
              <a href="javascript:history.back()" class="btn btn-secondary">Go Back</a>
            </div>
          </center>
        </form>
      </div>
    </div>
  </div>
  </div>
  <br>
  <footer>
    <div class="footerContainer">
      <div class="sec1">
        <p style="font-size: 24px">Contact Us</p>
        <p class="bi bi-geo-alt-fill ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No. 05, Park Road, Colombo</p>
        <p class="bi bi-envelope-fill">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;locksncurls2024@gmail.com</p>
        <p class="bi bi-telephone-fill">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0112 878 774</p>
      </div>
      <div class="sec2">
        <p style="font-size: 24px">Useful Links</p>
        <a class="footerLink bi bi-link-45deg" href="index.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</a><br />
        <a class="footerLink bi bi-link-45deg" href="about_us.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;About</a><br />
        <a class="footerLink bi bi-link-45deg" href="services.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Services</a><br />
        <a class="footerLink bi bi-link-45deg" href="contact.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contact</a><br />
      </div>
      <div class="sec3">
        <p style="font-size: 24px">About Us</p>
        <p style="line-height: 30px;">
          Locks & Curls Beauty Parlor offers expert hair, skin, and nail
          services in a relaxing environment. Our skilled team uses premium,
          eco-friendly products to ensure you look and feel your best. Visit
          us for personalized care and a rejuvenating experience.
        </p>
      </div>
    </div>
  </footer>
</body>

</html>