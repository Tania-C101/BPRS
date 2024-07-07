<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  function sanitize_input($data)
  {
    return htmlspecialchars(stripslashes(trim($data)));
  }

  $fname = sanitize_input($_POST['fname']);
  $lname = sanitize_input($_POST['lname']);
  $email = sanitize_input($_POST['email']);
  $phone = sanitize_input($_POST['phone']);
  $message = sanitize_input($_POST['message']);

  $errors = [];

  // Validate first name
  if (empty($fname) || !preg_match("/^[a-zA-Z]*$/", $fname)) {
    $errors['fname'] = "Please enter a valid first name.";
  }

  // Validate last name
  if (empty($lname) || !preg_match("/^[a-zA-Z]*$/", $lname)) {
    $errors['lname'] = "Please enter a valid last name.";
  }

  // Validate email
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Please enter a valid email address.";
  }

  // Validate phone number
  if (empty($phone) || !preg_match("/^\d{10}$/", $phone)) {
    $errors['phone'] = "Please enter a valid 10-digit phone number.";
  }

  // Validate message
  if (empty($message)) {
    $errors['message'] = "Please enter a message.";
  }

  if (empty($errors)) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bprs";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Concurrently insert data into unreg_users table and inquiries table extracting required values
    $sql1 = "INSERT INTO unreg_users (U_First_Name, U_Last_Name, U_Email, U_Phone) VALUES (?, ?, ?, ?)";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("ssss", $fname, $lname, $email, $phone);
    $stmt1->execute();

    $unreg_uid = $stmt1->insert_id;
    $unreg_uid_formatted = 'UR' . ($unreg_uid + 1);

    $sql2 = "INSERT INTO inquiries (I_First_Name, I_Last_Name, I_Email, I_Mobile, Message, User_ID, User_Type) VALUES (?, ?, ?, ?, ?, ?, 'unregistered')";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("sssiss", $fname, $lname, $email, $phone, $message, $unreg_uid_formatted);

    if ($stmt2->execute()) {
      echo "<script>alert('Message sent successfully!'); window.location.href = 'contact.php'</script>";
    } else {
      echo "<script>alert('Failed. Please try again later!');</script>";
    }

    $stmt1->close();
    $stmt2->close();
    $conn->close();
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Contact Us- Locks&Curls</title>
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</head>

<!--Contact page viewable for Unregistered users-->

<body class="home">

  <nav class="navbar navbar-expand-lg navbar-light bg-custom justify-content-end front-nav">
    <a class="nav-item nav-link" href="index.php">HOME</a>
    <a class="nav-item nav-link" href="about_us.php">ABOUT</a>
    <a class="nav-item nav-link" href="services.php">SERVICES</a>
    <a class="nav-item nav-link active" href="contact.php">CONTACT</a>
    <a class="nav-item nav-link" href="sign_up.php">SIGN UP</a>
    <a class="nav-item nav-link" href="user_login.php">LOGIN</a>
    <a class="nav-item nav-link" href="admin_login.php">ADMIN</a>
  </nav>

  <!--Up Panel-->
  <div class="container-image" style="text-align: center; width: 100%">
    <img src="images/contact.png" style="width: 100%" alt="Contact page backround image" />
    <div class="text" style="color: #ffffff; font-size: 60px">Contact Us</div>
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
                      <br>
                      <br>
                      <br>
                      <br>
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
              <label style="margin: 20px; line-height: 30px; text-align: center">
                Please fill out the below inquiry form to know more about our
                services or to provide any feedback. Our staff will attend to
                your inquiry at the earliest.</label>
              <br />
              <form method="post" class="form-style">
                <div>
                  <div class="form-group">
                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" required=""
                      style="font-size: 14px"
                      value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : ''; ?>" /> // Preseerves previously entered values
                    <?php if (!empty($errors['fname'])): ?>
                      <div class="error" style="color: red;"><?php echo $errors['fname']; ?></div>
                    <?php endif; ?><br>
                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" required=""
                      style="font-size: 14px"
                      value="<?php echo isset($_POST['lname']) ? htmlspecialchars($_POST['lname']) : ''; ?>" />
                    <?php if (!empty($errors['lname'])): ?>
                      <div class="error" style="color: red;"><?php echo $errors['lname']; ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required=""
                      style="font-size: 14px"
                      value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
                    <?php if (!empty($errors['email'])): ?>
                      <div class="error" style="color: red;"><?php echo $errors['email']; ?></div>
                    <?php endif; ?><br>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" required=""
                      maxlength="10" style="font-size: 14px"
                      value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" />
                    <?php if (!empty($errors['phone'])): ?>
                      <div class="error" style="color: red;"><?php echo $errors['phone']; ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="form-group">
                    <textarea class="form-control" id="message" name="message" placeholder="Message" required=""
                      style="font-size: 14px"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    <?php if (!empty($errors['message'])): ?>
                      <div class="error" style="color: red;"><?php echo $errors['message']; ?></div>
                    <?php endif; ?><br>
                  </div>
                </div>
                <div>
                  <button type="submit" class="btn btn-primary btn-lg front-button" id="send_Msg_Button">
                    Send Message
                  </button>
                </div>
              </form>
            </div>
            <br />
            <br />
          </div>
        </div>
      </div>
    </div>
  </section>
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