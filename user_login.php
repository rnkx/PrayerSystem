<?php
session_start();
include('database/db.php');
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hash);

    if ($stmt->fetch() && password_verify($password, $hash)) {
        $_SESSION['user_id'] = $id; 
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"> 
  <title>User Login | Prayer System</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Extra styling for login page */
    .login-container {
      max-width: 500px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #4a90e2;
    }
    .login-container .error {
      background: #ffe0e0;
      color: #a00;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 6px;
      text-align: center;
    }
    .login-container input {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .login-container button {
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
    .login-container button:hover {
      background: #357ab8;
    }
  </style>
</head>
<body>


  <div class="login-container">
    <h2>User Login</h2>
    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
<p style="text-align:center;">
  <a href="forgot_password.php">Forgot Password?</a>
</p>
<br/>
<p style="text-align:center;">
  Don't have an account? <a href="register.php">Register here</a>
</p>

  </div>


</body>
</html>
