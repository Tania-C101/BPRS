<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$employee = [
  'Emp_ID' => '',
  'Full_Name' => '',
  'Contact_No' => '',
  'Email' => '',
  'Username' => '',
  'Employee_Category' => ''
];

$action = $_POST['action'] ?? '';

$disableAdd = false; // Flag to disable Add button

if ($action == 'search') {
  $emp_id = $_POST['emp_id'];
  $sql = "SELECT * FROM employees WHERE Emp_ID = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $emp_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $employee = $result->fetch_assoc() ?: $employee;

  if ($result->num_rows == 0) {
    echo "<script>alert('User not found for the entered ID!');</script>";
  }

  // Format mobile number with leading '0', only if not empty
  if (!empty($employee['Contact_No'])) {
    $employee['Contact_No'] = sprintf('%010d', $employee['Contact_No']);
  }

  // Set flag to disable Add button
  $disableAdd = true;

} elseif ($action == 'add') {
  $full_name = $_POST['system_fname'];
  $email = $_POST['system_email'];
  $mobile = $_POST['system_mobile'];
  $username = $_POST['system_username'];
  $password = password_hash($_POST['system_password'], PASSWORD_BCRYPT);
  $employee_category = $_POST['employee_category'];

  // Check if username already exists
  $sql_check_username = "SELECT Emp_ID FROM employees WHERE Username = ?";
  $stmt_check_username = $conn->prepare($sql_check_username);
  $stmt_check_username->bind_param("s", $username);
  $stmt_check_username->execute();
  $stmt_check_username->store_result();

  // Check if email already exists
  $sql_check_email = "SELECT Emp_ID FROM employees WHERE Email = ?";
  $stmt_check_email = $conn->prepare($sql_check_email);
  $stmt_check_email->bind_param("s", $email);
  $stmt_check_email->execute();
  $stmt_check_email->store_result();

  // Check if mobile number already exists
  $sql_check_mobile = "SELECT Emp_ID FROM employees WHERE Contact_No = ?";
  $stmt_check_mobile = $conn->prepare($sql_check_mobile);
  $stmt_check_mobile->bind_param("s", $mobile);
  $stmt_check_mobile->execute();
  $stmt_check_mobile->store_result();

  // Validate if Emp_ID is not manually entered
  if (!empty($_POST['emp_id'])) {
    echo "<script>alert('System automatically generates the Employee ID. Please do not enter it manually.');</script>";
    $disableAdd = true; // Disable further actions
  } elseif ($stmt_check_username->num_rows > 0) {
    echo "<script>alert('Username already exists!');</script>";
    // Preserve entered values
    $employee['Full_Name'] = $full_name;
    $employee['Contact_No'] = $mobile;
    $employee['Email'] = $email;
    $employee['Username'] = $username;
    $employee['Employee_Category'] = $employee_category;
  } elseif ($stmt_check_email->num_rows > 0) {
    echo "<script>alert('Email already exists!');</script>";
    // Preserve entered values
    $employee['Full_Name'] = $full_name;
    $employee['Contact_No'] = $mobile;
    $employee['Email'] = $email;
    $employee['Username'] = $username;
    $employee['Employee_Category'] = $employee_category;
  } elseif ($stmt_check_mobile->num_rows > 0) {
    echo "<script>alert('Mobile number already exists!');</script>";
    // Preserve entered values
    $employee['Full_Name'] = $full_name;
    $employee['Contact_No'] = $mobile;
    $employee['Email'] = $email;
    $employee['Username'] = $username;
    $employee['Employee_Category'] = $employee_category;
  } else {
    // Proceed with insertion
    $sql = "INSERT INTO employees (Full_Name, Email, Contact_No, Username, Password, Employee_Category)
                    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $full_name, $email, $mobile, $username, $password, $employee_category);

    if ($stmt->execute()) {
      echo "<script>alert('System user added successfully!');</script>";
      // Clear fields after successful addition
      $employee = [
        'Emp_ID' => '',
        'Full_Name' => '',
        'Contact_No' => '',
        'Email' => '',
        'Username' => '',
        'Employee_Category' => ''
      ];
    } else {
      echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
  }
} elseif ($action == 'update') {
  $emp_id = $_POST['emp_id'];
  $full_name = $_POST['system_fname'];
  $mobile = $_POST['system_mobile'];
  $email = $_POST['system_email'];
  $username = $_POST['system_username'];
  $employee_category = $_POST['employee_category'];

  // Validate if Emp_ID is not changed
  $new_emp_id = $_POST['new_emp_id']; // Assuming you add a hidden field for this

  if ($emp_id != $new_emp_id) {
    echo "<script>alert('You cannot change the Employee ID.');</script>";
    $disableAdd = true; // Disable further actions
  } else {
    // Retrieve current data from the database
    $sql = "SELECT * FROM employees WHERE Emp_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_employee = $result->fetch_assoc();

    // Check if username already exists for another employee
    $sql_check_username = "SELECT Emp_ID FROM employees WHERE Username = ? AND Emp_ID != ?";
    $stmt_check_username = $conn->prepare($sql_check_username);
    $stmt_check_username->bind_param("si", $username, $emp_id);
    $stmt_check_username->execute();
    $stmt_check_username->store_result();

    // Check if email already exists for another employee
    $sql_check_email = "SELECT Emp_ID FROM employees WHERE Email = ? AND Emp_ID != ?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bind_param("si", $email, $emp_id);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();

    // Check if mobile number already exists for another employee
    $sql_check_mobile = "SELECT Emp_ID FROM employees WHERE Contact_No = ? AND Emp_ID != ?";
    $stmt_check_mobile = $conn->prepare($sql_check_mobile);
    $stmt_check_mobile->bind_param("si", $mobile, $emp_id);
    $stmt_check_mobile->execute();
    $stmt_check_mobile->store_result();

    if ($stmt_check_username->num_rows > 0) {
      echo "<script>alert('Username already exists!');</script>";
    } elseif ($stmt_check_email->num_rows > 0) {
      echo "<script>alert('Email already exists!');</script>";
    } elseif ($stmt_check_mobile->num_rows > 0) {
      echo "<script>alert('Mobile number already exists!');</script>";
    } else {
      // Check if any data has changed
      if (
        $full_name == $current_employee['Full_Name'] &&
        $mobile == $current_employee['Contact_No'] &&
        $email == $current_employee['Email'] &&
        $username == $current_employee['Username'] &&
        $employee_category == $current_employee['Employee_Category']
      ) {
        echo "<script>alert('No changes were made to your profile since no data have been updated');</script>";
      } else {
        $sql = "UPDATE employees SET Full_Name = ?, Email = ?, Contact_No = ?, Username = ?, Employee_Category = ? WHERE Emp_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissi", $full_name, $email, $mobile, $username, $employee_category, $emp_id);

        if ($stmt->execute()) {
          echo "<script>alert('System user updated successfully!');</script>";
        } else {
          echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
      }
    }
  }

} elseif ($action == 'delete') {
  $emp_id = $_POST['emp_id'];
  $sql = "DELETE FROM employees WHERE Emp_ID = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $emp_id);

  if ($stmt->execute()) {
    echo "<script>alert('System user deleted successfully!');</script>";
  } else {
    echo "<script>alert('Error: " . $stmt->error . "');</script>";
  }
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
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900&display=swap"
    rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</head>

<!--Manage System User Profile Sub Component Page-->

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
              <a class="dropdown-item active" href="be_manager_manage_system_users.php">Manage System Users</a>
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
      <h2>Manage System Users</h2>
    </center>
    <br>
    <form method="post" class="form-style display-font" id="user-form">
      <!-- Hidden input for action -->
      <input type="hidden" name="action" id="action" value="" />

      <!-- Hidden input for Emp_ID -->
      <input type="hidden" name="emp_id" id="emp_id" value="<?php echo htmlspecialchars($employee['Emp_ID']); ?>" />

      <!-- Hidden input for new_emp_id (for update action to check Emp_ID change) -->
      <input type="hidden" name="new_emp_id" id="new_emp_id"
        value="<?php echo htmlspecialchars($employee['Emp_ID']); ?>" />

      <div style="padding-top: 10px">
        <label id="system_reg_userid_label" for="emp_id" style="display: block;">Employee ID:</label>
        <br />
        <div style="display: flex; align-items: center">
          <input type="text" class="form-control" placeholder="xxxxxxxx" name="emp_id" id="emp_id"
            value="<?php echo htmlspecialchars($employee['Emp_ID']); ?>"
            style="flex-grow: 1; width: 70%; margin-right: 10px; font-size: 14px;" />
          <button type="button" class="btn btn-primary btn-lg" onclick="submitForm('search')"
            style="background-color: #37005a; color: white; border-color: #000000; border-radius: 10px; border-style: none; font-size: 14px; width: 20%;">Search</button>
        </div>
      </div>
      <div style="padding-top: 30px">
        <label for="employee_category">Employee Category:</label>
        <br />
        <select style="font-size: 14px;" class="form-control" name="employee_category" id="employee_category" required>
          <option value="Administrator" <?php if ($employee['Employee_Category'] == 'Administrator')
            echo 'selected'; ?>>
            Administrator</option>
          <option value="Manager" <?php if ($employee['Employee_Category'] == 'Manager')
            echo 'selected'; ?>>Manager
          </option>
        </select>
      </div>
      <div style="padding-top: 30px">
        <label for="system_fname">Full Name:</label>
        <br />
        <input type="text" class="form-control" name="system_fname" id="system_fname"
          value="<?php echo htmlspecialchars($employee['Full_Name']); ?>" required="true" style="font-size: 14px;" />
      </div>
      <div style="padding-top: 30px">
        <label for="system_mobile">Mobile Number:</label>
        <br />
        <input type="text" class="form-control" required="" name="system_mobile" id="system_mobile"
          value="<?php echo !empty($employee['Contact_No']) ? htmlspecialchars($employee['Contact_No']) : ''; ?>"
          pattern="[0-9]+" maxlength="10" style="font-size: 14px;" />
      </div>
      <div style="padding-top: 30px">
        <label for="system_email">E-mail:</label>
        <br />
        <input type="email" class="form-control" required="" name="system_email" id="system_email"
          value="<?php echo htmlspecialchars($employee['Email']); ?>" style="font-size: 14px;" />
      </div>
      <div style="padding-top: 30px">
        <label for="system_username">Username:</label>
        <br />
        <input type="text" class="form-control" name="system_username" id="system_username"
          value="<?php echo htmlspecialchars($employee['Username']); ?>" required="true" style="font-size: 14px;" />
      </div>
      <div style="padding-top: 30px">
        <label for="system_password">Password:</label>
        <br />
        <input type="password" class="form-control" name="system_password" id="system_password" <?php if ($disableAdd)
          echo 'disabled'; ?> style="font-size: 14px;" />
      </div>
      <div style="padding-top: 30px">
        <label for="confirm_password">Confirm Password:</label>
        <br />
        <input type="password" class="form-control" name="confirm_password" id="confirm_password" <?php if ($disableAdd)
          echo 'disabled'; ?> style="font-size: 14px;" />
      </div>
      <br />
      <br />
      <div style="display: flex; justify-content: space-between; padding: 10px;">
        <button type="button" class="btn btn-primary btn-lg buttonBE" onclick="submitForm('add')" style="width: 200px"
          <?php if ($disableAdd)
            echo 'disabled'; ?>>Add System User</button>
        <button type="button" class="btn btn-primary btn-lg buttonBE" onclick="submitForm('update')"
          style="width: 200px" <?php if (!$disableAdd)
            echo 'disabled'; ?>>Update System User</button>
        <button type="button" class="btn btn-primary btn-lg buttonBE" onclick="submitForm('delete')"
          style="width: 200px" <?php if (!$disableAdd)
            echo 'disabled'; ?>>Delete System User</button>
        <a href="be_manager_manage_system_users.php" class="btn btn-secondary btn-lg"
          style="background-color: #37005a; color: white; border-color: #000000; border-radius: 10px; border-style: none; width:200px; font-size: 14px">Back</a>
      </div>
    </form>

  </div>

  <script>
    function submitForm(action) {
      document.getElementById('action').value = action;

      if (action === 'add') {
        var fname = document.getElementById('system_fname').value;
        var username = document.getElementById('system_username').value;
        var email = document.getElementById('system_email').value;
        var mobile = document.getElementById('system_mobile').value;
        var password = document.getElementById('system_password').value;
        var confirmPassword = document.getElementById('confirm_password').value;

        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var mobilePattern = /^\d{10}$/;
        var passwordPattern = /^(?=.*[A-Z])(?=.*\d).{8,}$/;

        const hasNumber = /\d/.test(fname);

        if (hasNumber) {
          alert("Full Name field should not contain numbers.");
          return false;
        }

        if (!fname || !mobile || !email || !username || !password || !confirmPassword) {
          alert("One or more fields are empty!");
          return;
        }

        if (!emailPattern.test(email)) {
          alert("Invalid email format!");
          return;
        }

        if (!mobilePattern.test(mobile)) {
          alert("Mobile number must be exactly 10 digits!");
          return;
        }

        if (!passwordPattern.test(password)) {
          alert("Password must be at least 8 characters long, with at least one uppercase letter and one number!");
          return;
        }

        if (password !== confirmPassword) {
          alert("Password and Confirm Password do not match!");
          return;
        }
      }

      if (action === 'update') {
        var fname = document.getElementById('system_fname').value;
        var username = document.getElementById('system_username').value;
        var email = document.getElementById('system_email').value;
        var mobile = document.getElementById('system_mobile').value;

        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var mobilePattern = /^\d{10}$/;

        const hasNumber = /\d/.test(fname);

        if (hasNumber) {
          alert("Full Name field should not contain numbers.");
          return false;
        }

        if (!fname || !mobile || !email || !username) {
          alert("One or more fields are empty!");
          return;
        }

        if (!emailPattern.test(email)) {
          alert("Invalid email format!");
          return;
        }

        if (!mobilePattern.test(mobile)) {
          alert("Mobile number must be exactly 10 digits!");
          return;
        }

      }

      if (action === 'delete') {
        var confirmDelete = confirm("Are you sure you want to delete this system user?");
        if (!confirmDelete) {
          return; // If user cancels, do nothing
        }
      }
      document.getElementById('user-form').submit();
    }
  </script>
</body>

</html>