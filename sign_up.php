<?php

$errors = [];

// Sanitize input function
function sanitize_input($input)
{
  $input = trim($input);
  $input = stripslashes($input);
  $input = htmlspecialchars($input);
  return $input;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize and validate first name
  if (empty($_POST['user_fname'])) {
    $errors['user_fname'] = "First name is required!";
  } else {
    $user_fname = sanitize_input($_POST['user_fname']);
    if (!preg_match("/^[a-zA-Z]+$/", $user_fname)) {
      $errors['user_fname'] = "First name must contain only letters!";
    }
  }

  // Sanitize and validate last name
  if (empty($_POST['user_lname'])) {
    $errors['user_lname'] = "Last name is required!";
  } else {
    $user_lname = sanitize_input($_POST['user_lname']);
    if (!preg_match("/^[a-zA-Z]+$/", $user_lname)) {
      $errors['user_lname'] = "Last name must contain only letters!";
    }
  }

  // Sanitize and validate mobile number
  if (empty($_POST['user_mobile'])) {
    $errors['user_mobile'] = "Mobile number is required!";
  } else {
    $user_mobile = sanitize_input($_POST['user_mobile']);
    if (!preg_match("/^[0-9]{10}$/", $user_mobile)) {
      $errors['user_mobile'] = "Invalid mobile number format!";
    }
  }

  // Sanitize and validate email
  if (empty($_POST['user_email'])) {
    $errors['user_email'] = "Email is required!";
  } else {
    $user_email = sanitize_input($_POST['user_email']);
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
      $errors['user_email'] = "Invalid email format!";
    }
  }

  // Sanitize and validate username
  if (empty($_POST['user_username'])) {
    $errors['user_username'] = "Username is required!";
  } else {
    $user_username = sanitize_input($_POST['user_username']);
    // Additional validation if needed
  }

  // Sanitize and validate passwords
  if (empty($_POST['user_password'])) {
    $errors['user_password'] = "Password is required!";
  } else {
    $user_password = sanitize_input($_POST['user_password']);
    // Additional validation if needed
  }

  if (empty($_POST['user_confirm_password'])) {
    $errors['user_confirm_password'] = "Confirm Password is required!";
  } else {
    $user_confirm_password = sanitize_input($_POST['user_confirm_password']);
    // Additional validation if needed
  }

  // Validate password
  if (empty($_POST['user_password'])) {
    $errors['user_password'] = "Password is required!";
  } else {
    $user_password = $_POST['user_password'];
    if (strlen($user_password) < 6) {
      $errors['user_password'] = "Password must be at least 6 characters long!";
    } elseif (!preg_match("/[a-z]+/", $user_password)) {
      $errors['user_password'] = "Password must contain at least one lowercase letter!";
    } elseif (!preg_match("/[A-Z]+/", $user_password)) {
      $errors['user_password'] = "Password must contain at least one uppercase letter!";
    } elseif (!preg_match("/[0-9]+/", $user_password)) {
      $errors['user_password'] = "Password must contain at least one number!";
    }
  }

  // Confirm password
  if (empty($_POST['user_confirm_password'])) {
    $errors['user_confirm_password'] = "Confirm Password is required!";
  } else {
    $user_confirm_password = $_POST['user_confirm_password'];
    if ($user_password !== $user_confirm_password) {
      $errors['user_confirm_password'] = "Password and Confirm Password do not match!";
    }
  }

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "bprs";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT * FROM reg_users WHERE Username = ? OR R_Email = ? OR Phone = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $user_username, $user_email, $user_mobile);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      if ($row['Username'] == $user_username) {
        $errors['username'] = "Username is already taken!";
      }
      if ($row['R_Email'] == $user_email) {
        $errors['email'] = "Email is already registered!";
      }
    }
  }

  if (empty($errors)) {
    // Hash the password
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO reg_users (R_First_Name, R_Last_Name, R_Email, Phone, Username, Password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $user_fname, $user_lname, $user_email, $user_mobile, $user_username, $hashed_password);

    if ($stmt->execute()) {
      echo "<script>alert('Registration successful. You can now log in!'); window.location.href = 'sign_up.php';</script>";
    } else {
      echo "<script>alert('Error: Registration failed. Please try again later!');</script>";
    }
    $stmt->close();
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Sign Up - Locks&Curls</title>
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
</head>

<!--Sign Up page viewable to Unregistered users-->

<body class="home">

  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link" href="index.php">HOME</a>
    <a class="nav-item nav-link" href="about_us.php">ABOUT</a>
    <a class="nav-item nav-link" href="services.php">SERVICES</a>
    <a class="nav-item nav-link" href="contact.php">CONTACT</a>
    <a class="nav-item nav-link active" href="sign_up.php">SIGN UP</a>
    <a class="nav-item nav-link" href="user_login.php">LOGIN</a>
    <a class="nav-item nav-link" href="admin_login.php">ADMIN</a>
  </nav>

  <!--Up Panel-->
  <div class="container-image">
    <img src="images/contact.png" alt="Contact page backround image" />
    <div class="text" style="color: #ffffff; font-size: 60px">Sign Up</div>
  </div>
  <br>
  <br>

  <!--Down Panel-->
  <section class="w3l-contact-info-main" id="contact">
    <div class="contact-sec">
      <div class="container">
        <div class="row">

          <!--Left Panel-->
          <div class="col-md-6">
            <div class="map-content-9 mt-lg-0 mt-4">
              <div style="padding-top: 30px; margin-right: 50px;">
                <div class="contact-info" style="border-radius: 20px">
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
                    </div>
                  </div>
                </div>
              </div>
              <div class="side-image">
                <center>
                  <img src="images/sign-up-pic.png" alt="Sign-up page picture" />
                </center>
              </div>
            </div>
          </div>

          <!--Right Panel-->
          <div class="col-md-6">
            <div class="map-content-9 mt-lg-0 mt-4">
              <br />
              <form method="post" class="form-style" style="margin-top: 7px;">
                <div style="padding-top: 10px">
                  <h4>Create User Profile</h4>
                </div>
                <div style="padding-top: 30px">
                  <label for="user_fname">First Name</label>
                  <input type="text" class="form-control" placeholder="xxxxxx" name="user_fname" id="user_fname"
                    style="height: 50px; font-size: 14px;"
                    value="<?php echo isset($_POST['user_fname']) ? $_POST['user_fname'] : ''; ?>" required>
                  <?php if (!empty($errors['user_fname'])): ?>
                    <div class="error error-display"><?php echo $errors['user_fname']; ?></div>
                  <?php endif; ?>
                </div>
                <div style="padding-top: 30px">
                  <label for="user_lname">Last Name</label>
                  <input type="text" class="form-control" placeholder="xxxxxx" name="user_lname" id="user_lname"
                    style="height: 50px; font-size: 14px;"
                    value="<?php echo isset($_POST['user_lname']) ? $_POST['user_lname'] : ''; ?>" required>
                  <?php if (!empty($errors['user_lname'])): ?>
                    <div class="error error-display"><?php echo $errors['user_lname']; ?></div>
                  <?php endif; ?>
                </div>
                <div style="padding-top: 30px">
                  <label for="user_mobile">Mobile Number</label>
                  <input type="text" class="form-control" name="user_mobile" id="user_mobile" pattern="[0-9]+"
                    maxlength="10" placeholder="0712345678" style="height: 50px"
                    value="<?php echo isset($_POST['user_mobile']) ? $_POST['user_mobile'] : ''; ?>" required>
                  <?php if (!empty($errors['user_mobile'])): ?>
                    <div class="error error-display"><?php echo $errors['user_mobile']; ?></div>
                  <?php endif; ?>
                </div>
                <div style="padding-top: 30px">
                  <label for="user_email">E-mail</label>
                  <input type="email" class="form-control" placeholder="xxxxxx@gmail.com" name="user_email"
                    id="user_email" style="height: 50px; font-size: 14px;"
                    value="<?php echo isset($_POST['user_email']) ? $_POST['user_email'] : ''; ?>" required>
                  <?php if (!empty($errors['user_email'])): ?>
                    <div class="error error-display"><?php echo $errors['user_email']; ?></div>
                  <?php endif; ?>
                </div>
                <div style="padding-top: 30px">
                  <label for="user_username">Username</label>
                  <input type="text" class="form-control" placeholder="xxxxxx" name="user_username" id="user_username"
                    style="height: 50px; font-size: 14px;"
                    value="<?php echo isset($_POST['user_username']) ? $_POST['user_username'] : ''; ?>" required>
                  <?php if (!empty($errors['user_username'])): ?>
                    <div class="error error-display"><?php echo $errors['user_username']; ?></div>
                  <?php endif; ?>
                </div>
                <div style="padding-top: 30px">
                  <label label for="user_password">Password</label>
                  <input type="password" class="form-control new_password" placeholder="Password" name="user_password"
                    id="user_password" style="height: 50px; font-size: 14px;" required>
                  <?php if (!empty($errors['user_password'])): ?>
                    <div class="error error-display"><?php echo $errors['user_password']; ?></div>
                  <?php endif; ?>
                </div>
                <div style="padding-top: 30px">
                  <label for="user_confirm_password">Confirm Password</label>
                  <input type="password" class="form-control confirm_password" placeholder="Confirm your password"
                    name="user_confirm_password" id="user_confirm_password" style="height: 50px; font-size: 14px;"
                    required>
                  <?php if (!empty($errors['user_confirm_password'])): ?>
                    <div class="error error-display"><?php echo $errors['user_confirm_password']; ?></div>
                  <?php endif; ?>
                </div>
                <br />
                <br>
                <div style="padding-bottom:30px">
                  <button type="submit" class="btn btn-primary btn-lg front-button" id="create_Account_Button">
                    Create Account
                  </button>
                </div>
              </form>
            </div>
            <br>
            <br>
            <br>
          </div>
        </div>
      </div>
    </div>
  </section>

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