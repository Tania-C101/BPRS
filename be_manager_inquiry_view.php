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
$stmt_inq = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $search_inq_id = $_POST['search_inq_id'];

  $sql = "SELECT 
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
      WHERE Inquiry_ID = ?
    ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $search_inq_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $userDetails = $result->fetch_assoc();
  } else {
    echo "<script>alert('No record found with the provided Inquiry ID');</script>";
  }
  $stmt->close();
} else {
  $sql_all = "SELECT 
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
    ";
  $stmt_inq = $conn->query($sql_all);
}

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
    $view_inquiry_details = true;
  } else {
    echo "<script>alert('No record found with the provided Inquiry ID');</script>";
    $view_inquiry_details = false;
  }
  $stmt_retrieve->close();
} else {
  $view_inquiry_details = false;
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
              <a class="dropdown-item" href="be_manager_inquiry_mgt.php">Manage Inquiries</a>
            </li>
            <li>
              <a class="dropdown-item active" href="be_manager_inquiry_view.php">View Inquiries</a>
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

  <!--Right panel-->
  <div class="right-Panel">
    <br>
    <center>
      <h2>Search Inquiries</h2>
    </center>
    <br>
    <div class="form-container inquiryView display-font">
      <form method="post" action="">
        <br>
        <br>
        <div class="form-group">
          <table>
            <tr>
              <td style="width:150px"><label>Inquiry ID:</label></td>
              <td style="width:230px"><input type="text" class="form-control" placeholder="xxxxxxxx"
                  name="search_inq_id" id="search_inq_id" required="true" style="height: 40px; width: 200px;" />
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
              <th class="text-center">Inquiry #</th>
              <th class="text-center">Name</th>
              <th class="text-center">Email</th>
              <th class="text-center">Phone</th>
              <th class="text-center">View</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($stmt_inq) {
              while ($row = $stmt_inq->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='text-center'>" . htmlspecialchars($row['Inquiry_ID']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($row['First_Name'] . " " . $row['Last_Name']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($row['Email']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($row['Phone']) . "</td>";
                echo "<td class='text-center'><a href='be_manager_inquiry_view_extend.php?viewid=" . htmlspecialchars($row['Inquiry_ID']) . "' style='text-decoration: none; color: #ffffff'><button class='btn btn-primary btn-lg buttonBE'>View</button></a></td>";
                echo "</tr>";
              }
            } elseif (!empty($userDetails)) {
              // Display user details if searched
              echo "<tr>";
              echo "<td class='text-center'>" . htmlspecialchars($userDetails['Inquiry_ID']) . "</td>";
              echo "<td class='text-center'>" . htmlspecialchars($userDetails['First_Name'] . " " . $userDetails['Last_Name']) . "</td>";
              echo "<td class='text-center'>" . htmlspecialchars($userDetails['Email']) . "</td>";
              echo "<td class='text-center'>" . htmlspecialchars($userDetails['Phone']) . "</td>";
              echo "<td class='text-center'><a href='be_manager_inquiry_view_extend.php?viewid=" . htmlspecialchars($userDetails['Inquiry_ID']) . "' style='text-decoration: none; color: #ffffff'><button class='btn btn-primary btn-lg buttonBE'>View</button></a></td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>

</html>