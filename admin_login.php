<?php
session_start();
include('database/db.php');
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hash);

    if ($stmt->fetch() && password_verify($password, $hash)) {
        $_SESSION['admin_id'] = $id;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid admin credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | Prayer System</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .admin-login-container {
      max-width: 500px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .admin-login-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #4a90e2;
    }
    .admin-login-container .error {
      background: #ffe0e0;
      color: #a00;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 6px;
      text-align: center;
    }
    .admin-login-container input {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .admin-login-container button {
      width: 100%;
      padding: 12px;
      background: #4a90e2;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 1em;
      cursor: pointer;
      transition: background 0.3s;
    }
    .admin-login-container button:hover {
      background: #357ab8;
    }
  </style>
</head>
<body>
  

  <div class="admin-login-container">
    <h2>Admin Login</h2>
    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Admin Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <p style="text-align:center;">
  <a href="forgot_password.php">Forgot Password?</a>
</p>

  </div>


</body>
</html>
