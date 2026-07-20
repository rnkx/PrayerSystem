<?php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: user_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Content</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .upload-form {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    .upload-form h2 { text-align:center; color:#2c3e50; margin-bottom:20px; }
    .upload-form label { display:block; margin:15px 0 5px; font-weight:bold; }
    .upload-form input, .upload-form textarea, .upload-form select {
      width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;
    }
    .upload-form button {
      margin-top:20px; padding:12px 20px;
      background:#4a90e2; color:#fff; border:none; border-radius:6px;
      font-weight:bold; cursor:pointer; transition:background 0.3s ease;
    }
    .upload-form button:hover { background:#357ab8; }
  </style>
</head>
<body>
  <?php include('navbar.php'); ?>

  <div class="upload-form">
    <h2>Upload a Prayer</h2>
    <form action="process_upload.php" method="POST" enctype="multipart/form-data">
      <label for="type">Type</label>
      <select name="type" id="type" required>
        <option value="sutra">Sutra (PDF)</option>
        <option value="melody">Melody (Audio)</option>
      </select>

      <label for="title">Title</label>
      <input type="text" name="title" id="title" required>

      <label for="description">Description</label>
      <textarea name="description" id="description" rows="4" required></textarea>

      <label for="image">Image (optional)</label>
      <input type="file" name="image" id="image" accept="image/*">

      <label for="file">File (PDF for Sutra, Audio for Melody)</label>
      <input type="file" name="file" id="file" accept=".pdf,audio/*" required>

      <button type="submit">➕ Upload</button>
    </form>
  </div>

  <?php include('footer.php'); ?>
</body>
</html>
