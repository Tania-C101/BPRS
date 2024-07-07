<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to handle file upload
function uploadFile($file)
{
  $targetDir = "uploads/"; //Sets the target directory
  $targetFile = $targetDir . basename($file["name"]); //Extarcts the file's base name and
  //concats the target directory path with the base name creating the full path of the image
  $uploadOk = 1; //Initializing the Upload Status
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); //Extracts the file extension from the target file path

  // Check if file is actually uploaded
  if ($file["error"] != UPLOAD_ERR_OK) {//If value of UPLOAD_ERR_OK is not 0 -> Unsuccessful upload
    echo "File upload error.";
    $uploadOk = 0;
  } else {
    // Check if image file is a valid image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
      echo "File is not an image.";
      $uploadOk = 0;
    }

    // Allow only certain file formats
    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowedFormats)) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }
  }

  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    return null;
  } else {
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
      return $targetFile;
    } else {
      echo "Sorry, there was an error uploading your file.";
      return null;
    }
  }
}
// Handle image upload
if (isset($_FILES["image"]) && $_FILES["image"]["error"] != UPLOAD_ERR_NO_FILE) {
  $file_path = uploadFile($_FILES["image"]);
  if ($file_path === null) {
    $errors['image_error'] = "There was an error uploading the image.";
  }
} else {
  $file_path = null;
}

$service = [
  'Service_ID' => '',
  'Service_Name' => '',
  'Service_Cost' => '',
  'Service_Category' => '',
  'Emp_ID' => '',
  'Image_ID' => '',
  'File_Name' => '',
  'File' => ''
];

//$action = $_POST['action'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
  $action = $_POST['action'];
  $service_id = isset($_POST['service_id']) ? $_POST['service_id'] : '';

  if ($action === 'search' && !empty($service_id)) {
    $sql = "SELECT s.Service_ID, s.Service_Name, s.Service_Cost, s.Service_Category, s.Emp_ID, s.Image_ID, i.File_Name, i.File
              FROM services s
              LEFT JOIN s_images i ON s.Image_ID = i.Image_ID
              WHERE s.Service_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $service = $result->fetch_assoc();
    }
    $stmt->close();

  }
}

// Add Service

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
  $action = $_POST['action'];

  // Assign form values to variables
  $service_id = isset($_POST['service_id']) ? $_POST['service_id'] : '';
  $service_name = isset($_POST['service_name']) ? $_POST['service_name'] : null;
  $service_cost = isset($_POST['service_cost']) ? $_POST['service_cost'] : null;
  $service_category = isset($_POST['service_category']) ? $_POST['service_category'] : null;
  $emp_id = isset($_POST['emp_id']) ? $_POST['emp_id'] : null;

  if ($action == 'add') {
    // Handle image upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] != UPLOAD_ERR_NO_FILE) {
      // Insert image into database table
      if ($file_path) {
        $file_name = basename($_FILES["image"]["name"]);
        $insert_image_sql = "INSERT INTO s_images (File_Name, File) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_image_sql);
        $stmt->bind_param("ss", $file_name, $file_path);
        $stmt->execute();
        $image_id = $stmt->insert_id;
        $stmt->close();
      } else {
        $image_id = null;
      }

      // Insert service information into services table
      $insert_service_sql = "INSERT INTO services (Service_ID, Service_Name, Service_Cost, Emp_ID, Service_Category, Image_ID) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($insert_service_sql);
      $stmt->bind_param("ssiisi", $service_id, $service_name, $service_cost, $emp_id, $service_category, $image_id);

      if ($stmt->execute()) {
        echo "<script>alert('Service added successfully!');</script>";
      } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
      }
      $stmt->close();
    }
  }
}

// Update Service

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
  $action = $_POST['action'];
  $service_id = isset($_POST['service_id']) ? $_POST['service_id'] : '';

  // Assign form values to variables
  $service_id = isset($_POST['service_id']) ? $_POST['service_id'] : '';
  $service_name = isset($_POST['service_name']) ? $_POST['service_name'] : null;
  $service_cost = isset($_POST['service_cost']) ? $_POST['service_cost'] : null;
  $service_category = isset($_POST['service_category']) ? $_POST['service_category'] : null;
  $emp_id = isset($_POST['emp_id']) ? $_POST['emp_id'] : null;

  // Check if an image file is uploaded
  if ($action == 'update') {
    // Handle image upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] != UPLOAD_ERR_NO_FILE) {
      // Insert image into database table
      if ($file_path) {
        $file_name = basename($_FILES["image"]["name"]);
        $insert_image_sql = "INSERT INTO s_images (File_Name, File) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_image_sql);
        $stmt->bind_param("ss", $file_name, $file_path);
        $stmt->execute();
        $image_id = $stmt->insert_id;
        $stmt->close();
      } else {
        $image_id = null;
      }
    }
    // Update service information in Services table
    $update_service_sql = "UPDATE services SET Service_Name = ?, Service_Cost = ?, Emp_ID = ?, Service_Category = ?, Image_ID = ? WHERE Service_ID = ?";
    $stmt = $conn->prepare($update_service_sql);
    $stmt->bind_param("ssissi", $service_name, $service_cost, $emp_id, $service_category, $image_id, $service_id);

    if ($stmt->execute()) {
      echo "<script>alert('Service updated successfully!');</script>";
    } else {
      echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
  $action = $_POST['action'];
  $service_id = isset($_POST['service_id']) ? $_POST['service_id'] : '';
  if ($action == 'delete') {
    // Delete image from database table 
    $delete_image_sql = "DELETE FROM s_images WHERE Image_ID = ?";
    $stmt = $conn->prepare($delete_image_sql);
    $stmt->bind_param("i", $image_id);  // Assuming $image_id is the ID of the image to delete
    $stmt->execute();
    $stmt->close();

    // Delete service from Services table
    $delete_service_sql = "DELETE FROM services WHERE Service_ID = ?";
    $stmt = $conn->prepare($delete_service_sql);
    $stmt->bind_param("i", $service_id);

    if ($stmt->execute()) {
      echo "<script>alert('Service deleted successfully!');</script>";
      // Clear fields after successful deletion
      $service_id = '';
    } else {
      echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
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
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
    rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</head>

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
        <a class="nav-item nav-link backend-nav" href="be_system_invoice.php">Invoice Management</a>

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
          <a class="nav-item nav-link backend-nav btn dropdown-toggle active" href="be_manage_services.php"
            role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            Content Management
          </a>

          <!--Sub Components Link-->
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li>
              <a class="dropdown-item active" href="be_manage_services.php">Manage Services</a>
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
      <center>
        <h2>Manage Services</h2>
      </center>
      <br>
      <form method="post" class="form-style display-font" id="user-form" enctype="multipart/form-data">
        <input type="hidden" name="action" id="action" value="" />
        <div style="padding-top: 10px">
          <label for="service_id" style="display: block;">Service ID:</label>
          <br />
          <div style="display: flex; align-items: center">
            <input type="text" class="form-control" placeholder="xxxxxxxx" name="service_id" id="service_id"
              value="<?php echo htmlspecialchars($service['Service_ID']); ?>"
              style="flex-grow: 1; width: 70%; margin-right: 10px; font-size: 14px;" />
            <button type="button" class="btn btn-primary btn-lg buttonBE" onclick="submitForm('search')">Search</button>
          </div>
          <span class="text-danger" id="service_id_error"></span>
        </div>
        <div style="padding-top: 30px">
          <label for="service_category">Service Category:</label>
          <br />
          <select style="font-size: 14px;" class="form-control" name="service_category" id="service_category" required>
            <option value="------------" <?php if ($service['Service_Category'] == '------------')
              echo 'selected'; ?>>
              ------------</option>
            <option value="Hair Services" <?php if ($service['Service_Category'] == 'Hair Services')
              echo 'selected'; ?>>
              Hair Services</option>
            <option value="Waxing Services" <?php if ($service['Service_Category'] == 'Waxing Services')
              echo 'selected'; ?>>
              Waxing Services</option>
            <option value="Facial Services" <?php if ($service['Service_Category'] == 'Facial Services')
              echo 'selected'; ?>>
              Facial Services</option>
            <option value="Nail Services" <?php if ($service['Service_Category'] == 'Nail Services')
              echo 'selected'; ?>>
              Nail Services</option>
          </select>
          <span class="text-danger" id="service_category_error"></span>
        </div>
        <div style="padding-top: 30px">
          <label for="service_name">Service Name:</label>
          <br />
          <input type="text" class="form-control" name="service_name" id="service_name"
            value="<?php echo htmlspecialchars($service['Service_Name']); ?>" required="true"
            style="font-size: 14px;" />
          <span class="text-danger" id="service_name_error"></span>
        </div>
        <div style="padding-top: 30px">
          <label for="service_cost">Service Cost:</label>
          <br />
          <input type="text" class="form-control" name="service_cost" id="service_cost"
            value="<?php echo htmlspecialchars($service['Service_Cost']); ?>" required="true"
            style="font-size: 14px;" />
          <span class="text-danger" id="service_cost_error"></span>
        </div>
        <div style="padding-top: 30px">
          <label for="image">Service Image</label>
          <br>
          <input type="file" class="form-control" name="image" id="image" accept="image/*" style="font-size: 14px;"
            value="<?php echo htmlspecialchars($service['Image_ID']); ?>">
          <span class="text-danger" id="image_error"></span>
        </div>
        <div style="padding-top: 30px">
          <label for="emp_id">Emp ID:</label>
          <br />
          <input type="text" class="form-control" name="emp_id" id="emp_id"
            value="<?php echo htmlspecialchars($service['Emp_ID']); ?>" required="true" style="font-size: 14px;" />
          <span class="text-danger" id="emp_id_error"></span>
          <br />
          <br />
          <div style="display: flex; justify-content: space-between; padding: 10px;">
            <button type="button" class="btn btn-primary btn-lg buttonBE" onclick="submitForm('add')"
              style="width: 200px">Add Service</button>
            <button type="button" class="btn btn-primary btn-lg buttonBE" onclick="submitForm('update')"
              style="width: 200px">Update Service</button>
            <button type="button" class="btn btn-primary btn-lg buttonBE" onclick="submitForm('delete')"
              style="width: 200px">Delete Service</button>
            <button type="button" class="btn btn-primary btn-lg buttonBE"
              onclick="window.location.href='be_dashboard.php';" style="width: 200px">Back</button>
          </div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelector('form').addEventListener('submit', function (event) {
        event.preventDefault();
        const action = document.getElementById('action').value;
        if (action === 'search') {
          const empId = document.getElementById('service_id').value;
          fetch('', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
              action: 'search',
              service_id: empId
            })
          })
            .then(response => response.json())
            .then(data => {
              if (data && data.Service_ID) {
                document.getElementById('service_name').value = data.Service_Name;
                document.getElementById('service_cost').value = data.Service_Cost;
                document.getElementById('emp_id').value = data.Emp_ID;

                const serviceCategorySelect = document.getElementById('service_category');
                for (let option of serviceCategorySelect.options) {
                  if (option.value === data.Service_Category) {
                    option.selected = true;
                  }
                }
              } else {
                alert('Service not found');
              }
            })
            .catch(error => console.error('Error:', error));
        }
      });
    });

    function showError(element, message) {
      document.getElementById(element + '_error').innerText = message;
    }

    function clearErrors() {
      const errorElements = document.querySelectorAll('.text-danger');
      errorElements.forEach(element => element.innerText = '');
    }

    function validateForm(action) {
      clearErrors();
      let isValid = true;

      const service_id = document.getElementById('service_id').value.trim();
      const service_category = document.getElementById('service_category').value;
      const service_name = document.getElementById('service_name').value.trim();
      const service_cost = document.getElementById('service_cost').value.trim();
      const image = document.getElementById('image').value.trim();
      const emp_id = document.getElementById('emp_id').value.trim();

      if (action === 'search') {
        if (!service_id) {
          showError('service_id', 'Service ID is required for search.');
          isValid = false;
        } else if (isNaN(service_id)) {
          showError('service_id', 'Service ID must be a number.');
          isValid = false;
        }
      }

      if (action === 'add' || action === 'update') {
        if (!service_name) {
          showError('service_name', 'Service name is required.');
          isValid = false;
        }
        if (service_category === '------------') {
          showError('service_category', 'Please select a service category.');
          isValid = false;
        }
        if (!service_cost) {
          showError('service_cost', 'Service cost is required.');
          isValid = false;
        } else if (isNaN(service_cost)) {
          showError('service_cost', 'Service cost must be a number.');
          isValid = false;
        }
        if (!image) {
          showError('image', 'Service image is required.');
          isValid = false;
        }
        if (!emp_id) {
          showError('emp_id', 'Employee ID is required.');
          isValid = false;
        }
      } else if (action === 'delete') {
        if (!service_name) {
          showError('service_name', 'Service name is required.');
          isValid = false;
        }
        if (service_category === '------------') {
          showError('service_category', 'Please select a service category.');
          isValid = false;
        }
        if (!service_cost) {
          showError('service_cost', 'Service cost is required.');
          isValid = false;
        } else if (isNaN(service_cost)) {
          showError('service_cost', 'Service cost must be a number.');
          isValid = false;
        }
        if (!emp_id) {
          showError('emp_id', 'Employee ID is required.');
          isValid = false;
        }
      }
      return isValid;
    }

    function submitForm(action) {
      if (validateForm(action)) {
        document.getElementById('action').value = action;
        document.getElementById('user-form').submit();
      }
    }
  </script>
</body>

</html>