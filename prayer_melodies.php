<?php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: user_login.php");
    exit;
}
include('database/db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Prayer Melodies</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .page-title { text-align:center; margin:30px 0; color:#2c3e50; font-size:28px; }

 .melodies-grid {
 display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
  padding: 40px 20px;
  align-items: stretch;       /* ✅ all cards equal height */
  justify-content: center;    /* ✅ grid centered */
   text-align: center;
    display: flex;
   
      align-items: center;

  
}

.melody {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  justify-content: space-between; /* ✅ keeps audio at bottom */
  height: 100%;                   /* ✅ equal card height */
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.melody img {
  width: 100%;
  height: 220px;        /* ✅ slightly taller for balance */
  object-fit: cover;    /* ✅ crops to fill without distortion */
  border-top-left-radius: 12px;
  border-top-right-radius: 12px;
  border-bottom-left-radius: 12px;
  border-bottom-right-radius: 12px;
  background: #eee;
}

.melody h3 {
  margin: 15px;
  font-size: 22px;
  color: #4a90e2;
  text-align: center;
}

.melody p {
  margin: 0 15px 15px;
  color: #555;
  line-height: 1.6;
  text-align: center;
  flex-grow: 1; /* ✅ fills space evenly */
}

.melody audio {
  margin: 15px auto;
}


    /* Empty state styling */
    .empty-message {
      text-align: center;
      margin: 80px auto;
      max-width: 500px;
      background: #fff;
      border: 2px dashed #4a90e2;
      border-radius: 12px;
      padding: 40px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.05);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .empty-message .icon {
      font-size: 48px;
      margin-bottom: 15px;
    }
    .empty-message h3 {
      font-size: 26px;
      color: #4a90e2;
      margin-bottom: 10px;
    }
    .empty-message p {
      font-size: 16px;
      color: #555;
      margin-bottom: 25px;
    }
    .btn-upload {
      display: inline-block;
      padding: 14px 24px;
      background: #4a90e2;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn-upload:hover {
      background: #357ab8;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>
  <?php include('navbar.php'); ?>

  <div class="main-content">
    <h2 class="page-title">Prayer Melodies</h2>
    <?php
    $melodies = $conn->query("SELECT * FROM melodies");
    if ($melodies->num_rows > 0) {
        echo "<div class='melodies-grid'>";
        while ($row = $melodies->fetch_assoc()) {
            echo "<div class='melody'>
                    <h3>".htmlspecialchars($row['title'])."</h3>";
            if (!empty($row['image']) && file_exists("uploads/image/{$row['image']}")) {
                echo "<img src='uploads/image/{$row['image']}' alt='".htmlspecialchars($row['title'])."'>";
            } else {
                echo "<img src='uploads/image/default.jpg' alt='Default Melody Image'>";
            }
            if (!empty($row['description'])) {
                echo "<p>".htmlspecialchars($row['description'])."</p>";
            }
            echo "<audio controls>
                    <source src='uploads/audio/{$row['file']}' type='audio/mpeg'>
                    Your browser does not support the audio element.
                  </audio>
                  </div>";
        }
        echo "</div>"; // close grid
    } else {
        echo "<div class='empty-message'>
                <div class='icon'>🎵</div>
                <h3>No Melodies Yet</h3>
                <p>Share your first melody with the community.</p>
                <a href='upload.php' class='btn-upload'>➕ Upload a Melody</a>
              </div>";
    }
    ?>
  </div>

  <?php include('footer.php'); ?>
</body>
</html>
