<?php
session_start();

// Redirect to login if session variable is not set
if (!isset($_SESSION['Reg_UID'])) {
  header("Location: user_login.php");
  exit;
}

$Reg_UID = $_SESSION['Reg_UID'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$errors = []; // Array to store validation errors

try {
  // Connect to database
  $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Fetch user data
  $query = $db->prepare("SELECT R_First_Name, R_Last_Name, R_Email, Phone FROM reg_users WHERE Reg_UID = :Reg_UID");
  $query->bindParam(':Reg_UID', $Reg_UID, PDO::PARAM_INT);
  $query->execute();
  $user = $query->fetch(PDO::FETCH_ASSOC);

  // Prepend '0' to mobile number if not already present
  if (!empty($user['Phone']) && strlen($user['Phone']) === 9) {
    $user['Phone'] = '0' . $user['Phone'];
  }

  // Handle form submission
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input values
    $first_name = $_POST['user_fname'];
    $last_name = $_POST['user_lname'];
    $mobile = $_POST['user_mobile'];
    $email = $_POST['user_email'];

    // Validate first name
    if (!preg_match("/^[a-zA-Z]+$/", $first_name)) {
      $errors[] = "First name must contain only letters A-Z or a-z!";
    }

    // Validate last name
    if (!preg_match("/^[a-zA-Z]+$/", $last_name)) {
      $errors[] = "Last name must contain only letters A-Z or a-z!";
    }

    // Validate phone number
    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
      $errors[] = "Phone number must be 10 digits!";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Invalid email format!";
    }

    // Check for existing mobile number
    $mobile_check_query = $db->prepare("SELECT COUNT(*) FROM reg_users WHERE Phone = :mobile AND Reg_UID != :Reg_UID");
    $mobile_check_query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $mobile_check_query->bindParam(':Reg_UID', $Reg_UID, PDO::PARAM_INT);
    $mobile_check_query->execute();
    $mobile_exists = $mobile_check_query->fetchColumn();

    if ($mobile_exists) {
      $errors[] = "This mobile number is already registered!";
    }

    // Check for existing email
    $email_check_query = $db->prepare("SELECT COUNT(*) FROM reg_users WHERE R_Email = :email AND Reg_UID != :Reg_UID");
    $email_check_query->bindParam(':email', $email, PDO::PARAM_STR);
    $email_check_query->bindParam(':Reg_UID', $Reg_UID, PDO::PARAM_INT);
    $email_check_query->execute();
    $email_exists = $email_check_query->fetchColumn();

    if ($email_exists) {
      $errors[] = "This email is already registered!";
    }

    // Check if any changes were made
    if (empty($errors)) {
      if (
        $first_name === $user['R_First_Name'] &&
        $last_name === $user['R_Last_Name'] &&
        $mobile === $user['Phone'] &&
        $email === $user['R_Email']
      ) {
        echo "<script>alert('No changes were made to your profile.');</script>";
      } else {
        // Update the user data
        $update_query = $db->prepare("UPDATE reg_users SET R_First_Name = :first_name, R_Last_Name = :last_name, Phone = :mobile, R_Email = :email WHERE Reg_UID = :Reg_UID");
        $update_query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $update_query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $update_query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $update_query->bindParam(':email', $email, PDO::PARAM_STR);
        $update_query->bindParam(':Reg_UID', $Reg_UID, PDO::PARAM_INT);

        if ($update_query->execute()) {
          // Update user data after successful update
          $user['R_First_Name'] = $first_name;
          $user['R_Last_Name'] = $last_name;
          $user['Phone'] = $mobile;
          $user['R_Email'] = $email;

          echo "<script>alert('Profile successfully updated!');
                document.getElementById('user_fname').value = '$first_name';
                document.getElementById('user_lname').value = '$last_name';
                document.getElementById('user_mobile').value = '$mobile';
                document.getElementById('user_email').value = '$email';
                </script>";
        } else {
          echo "<script>alert('Failed to update profile. Please try again!');</script>";
        }
      }
    } else {
      // Output validation errors
      foreach ($errors as $error) {
        echo "<script>alert('$error');</script>";
      }
    }
  }
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Edit Profile - Locks&Curls</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
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
    crossorigin="anonymous"></script>
</head>

<body class="home">

  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link" href="regIndex.php">HOME</a>
    <a class="nav-item nav-link" href="regAbout_Us.php">ABOUT</a>
    <a class="nav-item nav-link" href="regServices.php">SERVICES</a>
    <a class="nav-item nav-link" href="regContact.php">CONTACT</a>
    <a class="nav-item nav-link" href="appointment.php">APPOINTMENT</a>
    <a class="nav-item nav-link" href="booking_history.php">BOOKING HISTORY</a>
    <a class="nav-item nav-link" href="invoice_history.php">INVOICE HISTORY</a>
    <div class="dropdown">
      <a class="nav-item nav-link dropdown-toggle active" data-bs-toggle="dropdown">PROFILE SETTINGS</a>
      <ul class="dropdown-menu">
        <li class="dropdown-tab">
          <a class="dropdown-item dropdown-link active" href="edit_profile.php">Edit Profile</a>
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
    <img src="images/edit_profile.png" style="width: 100%" alt="Edit profile page background image" />
    <div class="text" style="color: #ffffff; font-size: 60px">User Profile</div>
  </div>

  <!--Down Panel-->
  <section class="w3l-contact-info-main" id="contact">
    <div class="contact-sec">
      <div class="container">
        <div class="row">

          <!--Left Panel-->
          <div class="col-md-6">
            <div class="map-content-9 mt-lg-0 mt-4">
              <div style="padding-top: 30px; margin-right: 50px">
                <div class="contact-info" style="border-radius: 20px">
                  <br>
                  <br>
                  <div class="contact-item">
                    <div class="icon">
                      <i class="bi bi-telephone-fill custom-icon" style="padding-left: 30px"></i>
                    </div>
                    <div>
                      <h6 class="contact-section-label">Call Us</h6>
                      <p class="contact-detail-label">
                        <a href="tel:0112 878 774" style="color: black; text-decoration: none">0112 878 774</a>
                      </p>
                    </div>
                  </div>
                  <div class="contact-item">
                    <div class="icon">
                      <i class="bi bi-envelope-fill custom-icon" style="padding-left: 30px"></i>
                    </div>
                    <div>
                      <h6 class="contact-section-label">Email Us</h6>
                      <p class="contact-detail-label">
                        <a href="mailto:locksncurls2024@gmail.com"
                          style="color: black; text-decoration: none">locksncurls2024@gmail.com</a>
                      </p>
                    </div>
                  </div>
                  <div class="contact-item">
                    <div class="icon">
                      <i class="bi bi-geo-alt-fill custom-icon" style="padding-left: 30px"></i>
                    </div>
                    <div>
                      <h6 class="contact-section-label">Address</h6>
                      <p class="contact-detail-label">
                        No. 05, Park Road, Colombo
                      </p>
                    </div>
                  </div>
                  <div class="contact-item">
                    <div class="icon">
                      <i class="bi bi-clock-fill custom-icon" style="padding-left: 30px"></i>
                    </div>
                    <div>
                      <h6 class="contact-section-label">Time</h6>
                      <p class="contact-detail-label">
                        9:00 AM - 5:00 PM
                      </p>
                      <br>
                      <br>
                    </div>
                  </div>
                </div>
              </div>
              <br>
            </div>
          </div>

          <!--Right Panel-->
          <div class="col-md-6">
            <div class="map-content-9 mt-lg-0 mt-4">
              <br />
              <form method="post" class="form-style" style="margin-top:10px" id="profile_update_form">
                <?php if (!empty($user)): ?>

                  <div>
                    <label for="user_fname">First Name</label>
                    <input type="text" class="form-control" placeholder="xxxxxx" name="user_fname" id="user_fname"
                      value="<?php echo htmlspecialchars($user['R_First_Name']); ?>" required="true"
                      style="font-size: 14px" />
                    <?php if (!empty($errors) && in_array("First name must contain only letters A-Z or a-z!", $errors)): ?>
                      <div class="error-message">First name must contain only letters A-Z or a-z!</div>
                    <?php endif; ?>
                  </div>

                  <div style="padding-top: 30px">
                    <label for="user_lname">Last Name</label>
                    <input type="text" class="form-control" placeholder="xxxxxx" name="user_lname" id="user_lname"
                      value="<?php echo htmlspecialchars($user['R_Last_Name']); ?>" required="true"
                      style="font-size: 14px" />
                    <?php if (!empty($errors) && in_array("Last name must contain only letters A-Z or a-z!", $errors)): ?>
                      <div class="error-message">Last name must contain only letters A-Z or a-z!</div>
                    <?php endif; ?>
                  </div>

                  <div style="padding-top: 30px">
                    <label for="user_mobile">Mobile Number</label>
                    <input type="text" class="form-control" required="" name="user_mobile" id="user_mobile"
                      pattern="[0-9]+" maxlength="10" placeholder="0712345678"
                      value="<?php echo htmlspecialchars($user['Phone']); ?>" style="font-size: 14px" />
                    <?php if (!empty($errors) && in_array("Phone number must be 10 digits!", $errors)): ?>
                      <div class="error-message">Phone number must be 10 digits!</div>
                    <?php endif; ?>
                  </div>

                  <div style="padding-top: 30px">
                    <label for="user_email">E-mail</label>
                    <input type="email" class="form-control" placeholder="xxxxxx@gmail.com" required="" name="user_email"
                      id="user_email" value="<?php echo htmlspecialchars($user['R_Email']); ?>" style="font-size: 14px" />
                    <?php if (!empty($errors) && in_array("Invalid email format!", $errors)): ?>
                      <div class="error-message">Invalid email format!</div>
                    <?php endif; ?>
                  </div>

                  <br />
                  <div>
                    <button type="submit" class="btn btn-primary btn-lg front-button" id="update_Profile_Button">
                      Update Profile
                    </button>
                  </div>
                <?php endif; ?>
              </form>
            </div>
            <br>
          </div>
        </div>
      </div>
    </div>
  </section>
  <br />
  <br />

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
        <a class="footerLink bi bi-link-45deg" href="regIndex.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home</a><br />
        <a class="footerLink bi bi-link-45deg" href="regAbout_us.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;About</a><br />
        <a class="footerLink bi bi-link-45deg" href="regServices.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Services</a><br />
        <a class="footerLink bi bi-link-45deg" href="regContact.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contact</a><br />
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

  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
      document.getElementById('update_Profile_Button').addEventListener('click', (event) => {
        checkValidation(event);
      });
    });

    function checkValidation() {
      let user_fname = document.getElementById("user_fname").value;
      let user_lname = document.getElementById("user_lname").value;
      let user_mobile = document.getElementById("user_mobile").value;
      let user_email = document.getElementById("user_email").value;

      let nameRegex = /^[A-Za-z]+$/;
      let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      let phoneRegex = /^\d{10}$/;

      if (!user_fname || !user_lname || !user_mobile || !user_email) {
        alert("User data will be updated!");
      } else if (!nameRegex.test(user_fname)) {
        alert("First name must contain only letters A-Z or a-z!");
      } else if (!nameRegex.test(user_lname)) {
        alert("Last name must contain only letters A-Z or a-z!");
      } else if (!phoneRegex.test(user_mobile)) {
        alert("Phone number must be 10 digits!");
      } else if (!emailRegex.test(user_email)) {
        alert("Invalid email format!");
      } else {
        alert("Profile successfully updated!");
      }
    }
  </script>
</body>

</html>