<?php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: user_login.php");
    exit;
}

include('database/db.php');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Results | Prayer System</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .page-title { text-align:center; margin:30px 0; color:#2c3e50; font-size:28px; }
    .results-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:20px; max-width:1000px; margin:0 auto 40px; padding:0 20px; }
    .card { background:#fff; padding:25px; border-radius:12px; box-shadow:0 6px 15px rgba(0,0,0,0.1); transition:transform 0.2s ease, box-shadow 0.2s ease; text-align:center; }
    .card:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,0.15); }
    .card h3 { margin-top:0; color:#4a90e2; font-size:22px; }
    .card p { color:#555; line-height:1.6; margin:15px 0; }
    .card img { max-width:100%; height:auto; border-radius:8px; margin-bottom:15px; }
    .card a, .card audio { margin-top:10px; display:block; }
    .card a { padding:10px 16px; background:#4a90e2; color:#fff; text-decoration:none; border-radius:6px; transition:background 0.3s ease; }
    .card a:hover { background:#357ab8; }
    audio { width:100%; }
  </style>
</head>
<body>
  <?php include('navbar.php'); ?>

  <h2 class="page-title">Search Results for "<?php echo htmlspecialchars($q); ?>"</h2>
  <div class="results-grid">
    <?php
    if ($q !== '') {
        // Search sutras
        $stmt = $conn->prepare("SELECT * FROM sutras WHERE title LIKE ? OR description LIKE ?");
        $like = "%$q%";
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        $sutras = $stmt->get_result();

        while ($row = $sutras->fetch_assoc()) {
            echo "<div class='card'>
                    <h3>{$row['title']}</h3>";
            echo !empty($row['image']) ? "<img src='uploads/images/{$row['image']}' alt='{$row['title']}'>" : "<img src='uploads/images/default.jpg' alt='Default Sutra Image'>";
            echo "<p>{$row['description']}</p>
                  <a href='uploads/pdf/{$row['file']}' target='_blank'>Read Sutra</a>
                  </div>";
        }

        // Search melodies
        $stmt = $conn->prepare("SELECT * FROM melodies WHERE title LIKE ? OR description LIKE ?");
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        $melodies = $stmt->get_result();

        while ($row = $melodies->fetch_assoc()) {
            echo "<div class='card'>
                    <h3>{$row['title']}</h3>";
            echo !empty($row['image']) ? "<img src='uploads/images/{$row['image']}' alt='{$row['title']}'>" : "<img src='uploads/images/default.jpg' alt='Default Melody Image'>";
            if (!empty($row['description'])) {
                echo "<p>{$row['description']}</p>";
            }
            echo "<audio controls>
                    <source src='uploads/audio/{$row['file']}' type='audio/mpeg'>
                    Your browser does not support the audio element.
                  </audio>
                  </div>";
        }
    } else {
        echo "<p style='text-align:center;'>Please enter a search term.</p>";
    }
    ?>
  </div>

  <?php include('footer.php'); ?>
</body>
</html>
