<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: user_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Prayer System</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .welcome-container {
      max-width: 800px;
      margin: 40px auto;
      background: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
    }
    .welcome-container h1 {
      color: #4a90e2;
      margin-bottom: 20px;
    }
    .welcome-container p {
      font-size: 1.2em;
      margin-bottom: 30px;
    }
    .welcome-container a {
      display: inline-block;
      margin: 0 10px;
      padding: 12px 20px;
      background: #ffd700;
      color: #333;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      transition: background 0.3s;
    }
    .welcome-container a:hover {
      background: #ffcc00;
    }
  </style>
</head>
<body>
  <?php include('navbar.php'); ?>

  <main>
    <div class="welcome-container">
      <h1>Welcome to the Prayer System</h1>
      <p>Explore sutras and melodies for peace and reflection.</p>
      <a href="prayer_sutras.php">View Sutras</a>
      <a href="prayer_melodies.php">Listen to Melodies</a>
      <a href="upload.php">Upload Content</a>
    </div>
  </main>

  <?php include('footer.php'); ?>
</body>
</html>
