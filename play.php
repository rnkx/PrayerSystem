<?php include('navbar.php'); ?>
<?php
$file = $_GET['file'] ?? '';
if ($file) {
    echo "<h2>Now Playing</h2>
          <audio controls autoplay><source src='uploads/audio/$file' type='audio/mpeg'></audio>";
} else {
    echo "No file selected.";
}
?>
<?php include('footer.php'); ?>
