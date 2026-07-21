<?php

session_start();

include('database/db.php');


// Only admin allowed

if(!isset($_SESSION['admin_id'])){

    header("Location:admin_login.php");

    exit;

}



// Get statistics

$sutraCount = $conn->query(
"SELECT COUNT(*) AS total FROM sutras"
)->fetch_assoc()['total'];



$melodyCount = $conn->query(
"SELECT COUNT(*) AS total FROM melodies"
)->fetch_assoc()['total'];



$userCount = $conn->query(
"SELECT COUNT(*) AS total FROM users"
)->fetch_assoc()['total'];



?>



<!DOCTYPE html>
<html lang="en">


<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Admin Dashboard | Prayer System</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<link rel="stylesheet" href="css/style.css">


<style>


body{

background:#f4f7fa;

}



.dashboard-title{

text-align:center;

margin:40px 0;

color:#2c3e50;

}



.dashboard-card{

border:none;

border-radius:15px;

box-shadow:0 8px 20px rgba(0,0,0,.1);

transition:.3s;

}



.dashboard-card:hover{

transform:translateY(-5px);

}



.icon{

font-size:40px;

}



.btn-admin{

background:#4a90e2;

color:white;

font-weight:bold;

}



.btn-admin:hover{

background:#357ab8;

color:white;

}


</style>


</head>



<body>


<?php include('admin_navbar.php'); ?>



<div class="container">



<h1 class="dashboard-title">

👑 Admin Dashboard

</h1>



<div class="row g-4">



<!-- Sutras -->


<div class="col-md-3">


<div class="card dashboard-card text-center p-4">


<div class="icon">

📖

</div>


<h3>

<?php echo $sutraCount; ?>

</h3>


<p>

Prayer Sutras

</p>


<a href="manage_sutras.php"
class="btn btn-admin">

Manage Sutras

</a>


</div>


</div>




<!-- Melodies -->


<div class="col-md-3">


<div class="card dashboard-card text-center p-4">


<div class="icon">

🎵

</div>


<h3>

<?php echo $melodyCount; ?>

</h3>


<p>

Prayer Melodies

</p>


<a href="manage_melodies.php"
class="btn btn-admin">

Manage Melodies

</a>


</div>


</div>




<!-- Users -->


<div class="col-md-3">


<div class="card dashboard-card text-center p-4">


<div class="icon">

👥

</div>


<h3>

<?php echo $userCount; ?>

</h3>


<p>

Registered Users

</p>


<a href="manage_users.php"
class="btn btn-admin">

Manage Users

</a>


</div>


</div>




<!-- Upload -->


<div class="col-md-3">


<div class="card dashboard-card text-center p-4">


<div class="icon">

📤

</div>


<h3>

Upload

</h3>


<p>

Add new prayer content

</p>


<a href="admin_upload.php"
class="btn btn-admin">

Upload Content

</a>


</div>


</div>



</div>


</div>



<?php include('footer.php'); ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>


</html>