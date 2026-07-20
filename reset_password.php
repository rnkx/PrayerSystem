<?php
include('database/db.php');
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $token = $_POST['token'];
    $newpass = $_POST['new_password'];
    $confirmpass = $_POST['confirm_password'];

    // Check if passwords match
    if ($newpass !== $confirmpass) {
        $error = "New password and confirmation do not match.";
    } else {
        // Validate password strength
        $pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
        if (!preg_match($pattern, $newpass)) {
            $error = "New password must be at least 8 characters long, include 1 uppercase letter, 1 number, and 1 symbol.";
        } else {
         $sql = "SELECT email FROM reset_tokens WHERE token=? AND username=? AND expires > NOW()";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("ss", $token, $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $row = $result->fetch_assoc()) {
                $hashed = password_hash($newpass, PASSWORD_DEFAULT);

                // Update password in both tables
                $conn->query("UPDATE users SET password='$hashed' WHERE username='$username'");
                $conn->query("UPDATE admins SET password='$hashed' WHERE username='$username'");

                $success = "Your new password has been saved. You can now log in.";
            } else {
                $error = "Invalid or expired token for this username.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password | Prayer System</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include('navbar.php'); ?>
  <div class="reset-container">
    <br/>
    <h2>Set a New Password</h2>
    <?php if ($error): ?><div class="error" style="text-align:center;"><?php echo $error; ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success" style="text-align:center;"><?php echo $success; ?></div><?php endif; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Enter your username" required>
      <input type="text" name="token" placeholder="Enter token from Gmail" required>
      <input type="password" name="new_password" placeholder="Enter new password" required>
      <input type="password" name="confirm_password" placeholder="Confirm new password" required>
      <button type="submit">Save New Password</button>
    </form>
  </div>
  
  <?php include('footer.php'); ?>
</body>
</html>
