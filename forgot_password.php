<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include('database/db.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Generate secure token
    $token = bin2hex(random_bytes(16));
  $now = new DateTime("now", new DateTimeZone("Asia/Kuala_Lumpur")); // match your server timezone
$expires = clone $now;
$expires->modify("+5 minutes");

$created_at = $now->format("Y-m-d H:i:s");
$expires_at = $expires->format("Y-m-d H:i:s");

// Insert both explicitly
$stmt = $conn->prepare("INSERT INTO reset_tokens (username, email, token, expires, created_at) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $email, $token, $expires_at, $created_at);
$stmt->execute();

    // Build reset link
    $resetLink = "http://localhost/PrayerSystem/reset_password.php?token=$token&username=$username";

    // Send email via Gmail SMTP
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rngkxxx@gmail.com';   // Gmail address
        $mail->Password = 'fmai lvlq ksrx aqxl';       // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('rngkxxx@gmail.com', 'Prayer System');
        $mail->addAddress($email);

        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "Hello $username,\n\nWe received a request to reset your password.\n\nUse this token: $token\n\nOr click the link below:\n$resetLink\n\nThis link will expire in 5 minutes.";

        $mail->send();
        $message = "A reset link and token have been sent to your Gmail.";
    } catch (Exception $e) {
        $message = "Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password | Prayer System</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include('navbar.php'); ?>
  <div class="reset-container">
    <br/>
    <h2>Forgot Password</h2>
    <form method="POST">
      <input type="text" name="username" placeholder="Enter your username" required>
      <input type="email" name="email" placeholder="Enter your Gmail address" required>
      <button type="submit">Send Reset Link</button>
    </form>
    <?php if ($message): ?>
      <div class="message" style="text-align:center;"><?php echo $message; ?></div>
    <?php endif; ?>
  </div>
  <?php include('footer.php'); ?>
</body>
</html>
