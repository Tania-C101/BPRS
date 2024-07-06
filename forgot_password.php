<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bprs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to generate a random temporary password
function generateTempPassword($length = 8)
{
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

if (isset($_POST['user_email'])) {
  $email = $_POST['user_email'];

  // Check if the email exists in the database
  $query = "SELECT * FROM reg_users WHERE R_Email='$email'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    // Generate a temporary password
    $tempPassword = generateTempPassword();
    $hashedTempPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

    // Update the database with the hashed temporary password
    $query = "UPDATE reg_users SET Password='$hashedTempPassword' WHERE R_Email='$email'";
    mysqli_query($conn, $query);

    $mail = new PHPMailer();

    try {
      //Server settings
      $mail->isSMTP();
      $mail->Host = "mail.gmail.com"; // Set the SMTP server to send through
      $mail->SMTPAuth = true; // Enables SMTP authentication
      $mail->SMTPSecure = "tls";
      $mail->SMTPDebug = 2; // Enables SMTP debug information (for testing); 1 = Errors and Messages; 2 = Messages only
      $mail->Host = "smtp.gmail.com"; // SMTP server
      $mail->Port = 587; // SMTP port

      $mail->Username = "locksncurls2024@gmail.com"; //Username
      $mail->Password = "locks1234"; //Password

      // Receipient
      $mail->SetFrom('locksncurls2024@gmail.com', 'LocksnCurls');
      $mail->Subject = "Your Temporary Password - LocksnCurls";
      $mail->MsgHTML("Your temporary password is: $tempPassword<br>Please change your password after logging in.");

      $mail->send();
      echo '<script>alert("A temporary password has been sent to your email!"); </script>';
    } catch (Exception $e) {
      echo '<script>alert("Message could not be sent. Mailer Error: {$mail->ErrorInfo}"); </script>';
    }
  } else {
    echo '<script>alert("Email does not exist."); </script>';
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="path/to/your/css">
</head>

<body>
  <center>
    <div style="padding-top: 50px">
      <h2>Forgot Password</h2>
    </div>
    <br>
    <form id="forgot_password_form" method="post" action="forgot_password.php" style=" width: 50%; padding: 50px 50px 45px 50px; border-radius: 20px;
      font-size: 14px; background-color: #f0d4fe;">
      <h3>A temporary password has been sent to your email. Please use it to login to the system. <br>
        Upon login, do not forget to update your password!</h3>
      <div style="padding-top: 20px">
        <label for="user_email">Email</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="email" class="form-control" placeholder="xxxxxxx@xxxxx.com" name="user_email" id="user_email"
          required="true" style="height: 40px; width: 300px" />
      </div>
      <br>
      <br>
      <div style="padding-bottom: 30px">
        <button type="submit" class="btn btn-primary btn-lg" style="width: 200px; height: 50px; background-color: #a600fa; color: white; border-color: #000000; border-radius: 10px;
          border-style: none; font-size: 15px;">
          Retrieve Password
        </button>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button type="submit" class="btn btn-primary btn-lg" style="width: 200px; height: 50px; background-color: #a600fa; color: white; border-color: #000000; border-radius: 10px;
          border-style: none; font-size: 15px;" onclick="window.location.href='user_login.php';">
          Back
        </button>
      </div>
    </form>
  </center>
</body>

</html>