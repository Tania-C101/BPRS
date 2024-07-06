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

<!--Inquiry Management Main Page-->

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
        <a class="nav-item nav-link backend-nav" href="be_manager_dashboard.php">Dashboard</a>

        <!--Inquiry Management Tab-->
        <div class="dropdown">

          <!--Main Component Link-->
          <a class="nav-item nav-link backend-nav btn dropdown-toggle active" href="be_manager_inquiry_mgt.php"
            role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            Inquiry Management
          </a>

          <!--Sub Components Link-->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li>
              <a class="dropdown-item active" href="be_manager_inquiry_mgt.php">Manage Inquiries</a>
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
    <br>
    <center>
      <h2>Manage Inquiries</h2>
    </center>
    <br />
    <div class="table-responsive bs-example widget-shadow display-panel-inner display-font">
      <br />
      <table class="inquiryTable">
        <thead>
          <tr>
            <th class="inquiryTableRowHead tableOutline" style="width: 10px;">
              Inquiry #
            </th>
            <th class="inquiryTableRowHead tableOutline" style="width: 10px;">
              User ID
            </th>
            <th class="inquiryTableRowHead tableOutline" style="width: 200px;">
              Name
            </th>
            <th class="inquiryTableRowHead tableOutline" style="width: 100px;">
              Email
            </th>
            <th class="inquiryTableRowHead tableOutline" style="width: 50px;">
              Mobile No.
            </th>
            <th class="inquiryTableRowHead tableOutline" style="width: 50px">
              Response Status
            </th>
            <th class="inquiryTableRowHead tableOutline" style="width: 90px;">
              Action
            </th>
          </tr>
        </thead>
        <tbody class="borderBE">
          <?php
          $retrieve_sql = mysqli_query($conn, "SELECT 
                COALESCE(reg_users.Reg_UID, unreg_users.Unreg_UID) AS User_ID,
                COALESCE(reg_users.R_First_Name, unreg_users.U_First_Name) AS First_Name,
                COALESCE(reg_users.R_Last_Name, unreg_users.U_Last_Name) AS Last_Name,
                COALESCE(reg_users.R_Email, unreg_users.U_Email) AS Email,
                COALESCE(reg_users.Phone, unreg_users.U_Phone) AS Phone,
                inquiries.Inquiry_ID AS Inquiry_ID,
                inquiries.User_ID AS User_ID,
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
                 WHERE inquiries.Response IS NULL");

          $count = 1;

          while ($row = mysqli_fetch_array($retrieve_sql)) {
            ?>
            <tr>
              <td class="inquiryTableRow"><?php echo $row['Inquiry_ID']; ?></td>
              <td class="inquiryTableRow"><?php echo $row['User_ID']; ?></td>
              <td class="inquiryTableRow">
                <?php echo $row['First_Name'] . " "; ?>   <?php echo $row['Last_Name']; ?>
              </td>
              <td class="inquiryTableRow"><?php echo $row['Email']; ?></td>
              <td class="inquiryTableRow"><?php echo $row['Phone']; ?></td>
              <?php if ($row['Response'] == NULL) { ?>
                <td class="font-w600 inquiryTableRow"><?php echo "Pending"; ?></td>
              <?php } else { ?>
                <td class="font-w600 inquiryTableRow"><?php echo $row['Response']; ?></td><?php } ?>

              <!--View button column-->
              <td class="inquiryTableRow">
                <a id="view_inquiry_Button" href="be_manager_inquiry_reply.php?viewid=<?php echo $row['Inquiry_ID']; ?>"
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
</body>

</html>