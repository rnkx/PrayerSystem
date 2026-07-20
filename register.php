<?php
include('database/db.php');
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Password validation: 1 uppercase, 1 number, 1 symbol, min 8 chars
    $pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";

    if (!preg_match($pattern, $password)) {
        $error = "Password must be at least 8 characters long, include 1 uppercase letter, 1 number, and 1 symbol.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed);

        if ($stmt->execute()) {
            header("Location: user_login.php");
            exit;
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Registration | Prayer System</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .register-container {
      max-width: 500px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .register-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #4a90e2;
    }
    .register-container .error {
      background: #ffe0e0;
      color: #a00;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 6px;
      text-align: center;
    }
    .register-container input {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .register-container button {
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
    .register-container button:hover {
      background: #357ab8;
    }
  </style>
</head>
<body>


  <div class="register-container">
    <h2>User Registration</h2>
    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Register</button>
    </form>
  </div>

  
</body>
</html>
