<?php
session_start();

// Only allow admins
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Prayer System</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f7fa;
      margin: 0;
      padding: 0;
    }
    .dashboard-title {
      text-align: center;
      margin: 30px 0;
      color: #2c3e50;
      font-size: 28px;
    }
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      max-width: 1000px;
      margin: 0 auto 40px;
      padding: 0 20px;
    }
    .card {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      text-align: center;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .card h3 {
      margin-top: 0;
      color: #4a90e2;
      font-size: 22px;
    }
    .card p {
      color: #555;
      margin: 15px 0;
    }
    .card a {
      display: inline-block;
      margin-top: 10px;
      padding: 10px 16px;
      background: #4a90e2;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      transition: background 0.3s ease;
    }
    .card a:hover {
      background: #357ab8;
    }
  </style>
</head>
<body>
  <?php include('admin_navbar.php'); ?>

  <h2 class="dashboard-title">Admin Dashboard</h2>
  <div class="dashboard-grid">
    <div class="card">
      <h3>Manage Sutras</h3>
      <p>Add, edit, or delete prayer sutras.</p>
      <a href="manage_sutras.php">Go to Sutras</a>
    </div>
    <div class="card">
      <h3>Manage Melodies</h3>
      <p>Upload and organize prayer melodies.</p>
      <a href="manage_melodies.php">Go to Melodies</a>
    </div>
    <div class="card">
      <h3>User Accounts</h3>
      <p>View and manage registered users.</p>
      <a href="manage_users.php">Go to Users</a>
    </div>
    <div class="card">
      <h3>Uploads</h3>
      <p>Review and approve uploaded content.</p>
      <a href="upload.php">Go to Uploads</a>
    </div>
  </div>

  <?php include('admin_footer.php'); ?>
</body>
</html>
