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
$result_all_users = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $reg_user_id = $_POST['system_reg_id'];

  $sql_user_details = "SELECT Reg_UID, R_First_Name, R_Last_Name, Phone, R_Email, Username, Password FROM reg_users WHERE Reg_UID = ?";
  $stmt = $conn->prepare($sql_user_details);
  $stmt->bind_param("i", $reg_user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $userDetails = $result->fetch_assoc();
  } else {
    echo "<script>alert('No user found with the provided User ID'); window.location.href='be_manager_manage_client_profile.php';</script>";
  }
  $stmt->close();
} else {

  $sql_all_users = "SELECT Reg_UID, R_First_Name, R_Last_Name, Phone, R_Email, Username, Password FROM reg_users";
  $result_all_users = $conn->query($sql_all_users);
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
  </style>
</head>

<!--Manage Client Profile Sub Component Page-->

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
          <a class="nav-item nav-link backend-nav btn dropdown-toggle active" href="be_manager_manage_system_users.php"
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
              <a class="dropdown-item active" href="be_manager_manage_client_profile.php">Manage Client Profile</a>
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
      <h2>Manage Client Profile</h2>
    </center>
    <br>
    <div class="form-container display-font">
      <form method="post" action="">
        <div class="form-group">
          <label id="system_reg_userid_label" for="system_reg_id" style="width: 16%">Registered User ID:</label>
          <input type="text" class="form-control" placeholder="xxxxxxxx" name="system_reg_id" id="system_reg_id"
            required="true" style="height: 50px; width: 64%; flex-grow: 1" />
          <button type="submit" class="btn btn-primary btn-lg buttonBE">
            Search
          </button>
        </div>
      </form>
      <br />
      <div class="table-container">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center">Registered User ID</th>
              <th class="text-center">First Name</th>
              <th class="text-center">Last Name</th>
              <th class="text-center">Mobile Number</th>
              <th class="text-center">E-mail</th>
              <th class="text-center">Username</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result_all_users) {
              while ($row = $result_all_users->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='text-center'>" . htmlspecialchars($row['Reg_UID']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($row['R_First_Name']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($row['R_Last_Name']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($row['Phone']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($row['R_Email']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($row['Username']) . "</td>";
                echo "</tr>";
              }
            } elseif (!empty($userDetails)) {
              // Display user details if searched
              echo "<tr>";
              echo "<td class='text-center'>" . htmlspecialchars($userDetails['Reg_UID']) . "</td>";
              echo "<td class='text-center'>" . htmlspecialchars($userDetails['R_First_Name']) . "</td>";
              echo "<td class='text-center'>" . htmlspecialchars($userDetails['R_Last_Name']) . "</td>";
              echo "<td class='text-center'>" . htmlspecialchars($userDetails['Phone']) . "</td>";
              echo "<td class='text-center'>" . htmlspecialchars($userDetails['R_Email']) . "</td>";
              echo "<td class='text-center'>" . htmlspecialchars($userDetails['Username']) . "</td>";
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