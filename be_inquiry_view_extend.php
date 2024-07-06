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

<!--Inquiry View Main Page-->

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
        <a class="nav-item nav-link backend-nav
        <a class=" nav-item nav-link backend-nav" href="be_system_invoice.php">Invoice Management</a>

        <!--Inquiry Management Tab-->
        <div class="dropdown">

          <!--Main Component Link-->
          <a class="nav-item nav-link backend-nav btn dropdown-toggle active" href="be_inquiry_mgt.php" role="button"
            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            Inquiry Management
          </a>

          <!--Sub Components Link-->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li>
              <a class="dropdown-item" href="be_inquiry_mgt.php">Manage Inquiries</a>
            </li>
            <li>
              <a class="dropdown-item active" href="be_inquiry_view.php">View Inquiries</a>
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
      <div class="table-responsive bs-example widget-shadow">
        <center>
          <h2>View Inquiry History</h2>
        </center>
        <br>
        <div class="display-panel-inner display-font">
          <br><br>
          <?php
          if (isset($_GET['viewid']) && !empty($_GET['viewid'])) {
            $viewid = $_GET['viewid'];
            $retrieve_sql = "SELECT 
                            COALESCE(reg_users.Reg_UID, unreg_users.Unreg_UID) AS User_ID,
                            COALESCE(reg_users.R_First_Name, unreg_users.U_First_Name) AS First_Name,
                            COALESCE(reg_users.R_Last_Name, unreg_users.U_Last_Name) AS Last_Name,
                            COALESCE(reg_users.R_Email, unreg_users.U_Email) AS Email,
                            COALESCE(reg_users.Phone, unreg_users.U_Phone) AS Phone,
                            inquiries.Inquiry_ID AS Inquiry_ID,
                            inquiries.Message,
                            inquiries.Response,
                            employees.Emp_ID AS Emp_ID
                        FROM 
                            inquiries
                        LEFT JOIN 
                            reg_users ON inquiries.User_ID = reg_users.Reg_UID
                        LEFT JOIN 
                            unreg_users ON inquiries.User_ID = unreg_users.Unreg_UID
                        LEFT JOIN 
                            employees ON inquiries.Emp_ID = employees.Emp_ID
                        WHERE 
                            inquiries.Inquiry_ID = ?";
            $stmt_retrieve = $conn->prepare($retrieve_sql);
            $stmt_retrieve->bind_param("i", $viewid);
            $stmt_retrieve->execute();
            $result_retrieve = $stmt_retrieve->get_result();
            if ($result_retrieve->num_rows > 0) {
              $row = $result_retrieve->fetch_assoc();
              ?>
              <table class="table table-bordered" style="font-size: 14px;">
                <tr>
                  <td style="width:200px;"><label>Inquiry No.</label></td>
                  <td>
                    <div class="inner-row"><?php echo htmlspecialchars($row['Inquiry_ID']); ?></div>
                  </td>
                </tr>
                <tr>
                  <td>Name</td>
                  <td>
                    <div class="inner-row"><?php echo htmlspecialchars($row['First_Name'] . " " . $row['Last_Name']); ?>
                    </div>
                  </td>
                </tr>
                <tr style="height: 20px">
                  <td></td>
                </tr>
                <tr>
                  <td style="width:200px;"><label>Email</label></td>
                  <td>
                    <div class="inner-row"><?php echo htmlspecialchars($row['Email']); ?></div>
                  </td>
                </tr>
                <tr>
                  <td><label>Mobile Number</label></td>
                  <td>
                    <div class="inner-row"><?php echo htmlspecialchars($row['Phone']); ?></div>
                  </td>
                </tr>
                <tr style="height: 20px">
                  <td></td>
                </tr>
                <tr>
                  <td><label>Message</label></td>
                  <td>
                    <div class="inner-row"><?php echo htmlspecialchars($row['Message']); ?></div>
                  </td>
                </tr>
                <tr>
                  <td><label>Response</label></td>
                  <td>
                    <div class="inner-row"><?php echo htmlspecialchars($row['Response']); ?></div>
                  </td>
                </tr>
                <tr>
                  <td><label>Attended by</label></td>
                  <td>
                    <div class="inner-row"><?php echo htmlspecialchars($row['Emp_ID']); ?></div>
                  </td>
                </tr>
                <tr style="height: 20px">
                  <td></td>
                </tr>
                <tr>
                  <td></td>
                  <td>
                    <button type="button" class="btn btn-primary buttonBE"
                      onclick="window.location.href='be_inquiry_view.php';">Back</button>
                  </td>
                </tr>
              </table>
              <?php
            } else {
              echo "<div class='alert alert-warning'>No record found with the provided Inquiry ID.</div>";
            }
            $stmt_retrieve->close();
          } else {
            echo "<div class='alert alert-warning'>Invalid Inquiry ID.</div>";
          }
          $conn->close();
          ?>
        </div>
      </div>
    </div>
  </div>
</body>

</html>