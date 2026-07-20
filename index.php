<?php
include 'database/db.php';
?>

<!DOCTYPE html>
<html>

<head>

<title>Prayer Library</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<nav class="navbar navbar-dark bg-dark">

<div class="container">

<a class="navbar-brand" href="#">
Prayer Library
</a>

<ul class="navbar-nav flex-row">

<li class="nav-item me-3">
<a class="nav-link text-white" href="prayer_sutras.php">
Prayer Sutras
</a>
</li>

<li class="nav-item">
<a class="nav-link text-white" href="prayer_melodies.php">
Prayer Melodies
</a>
</li>

</ul>

</div>

</nav>

<div class="container mt-5">

<div class="row">

<div class="col-md-6">

<div class="card shadow">

<div class="card-body text-center">

<h2>Prayer Sutras</h2>

<p>Read and download Buddhist Sutras.</p>

<a href="prayer_sutras.php" class="btn btn-primary">

Open

</a>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-body text-center">

<h2>Prayer Melodies</h2>

<p>Listen to Buddhist Prayer Music.</p>

<a href="prayer_melodies.php" class="btn btn-success">

Open

</a>

</div>

</div>

</div>

</div>

</div>

</body>

</html>