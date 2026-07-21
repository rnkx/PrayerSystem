<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: user_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Prayer Sutras and Prayer Melodic Management System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <style>

        body{
            background:#f4f7fb;
        }

        .hero{
            background:linear-gradient(135deg,#4a90e2,#6bb6ff);
            color:white;
            padding:70px 20px;
            border-radius:15px;
            text-align:center;
            margin-top:30px;
        }

        .hero h1{
            font-weight:bold;
        }

        .feature-card{
            transition:.3s;
            border:none;
            border-radius:15px;
        }

        .feature-card:hover{
            transform:translateY(-8px);
            box-shadow:0 10px 20px rgba(0,0,0,.15);
        }

        .feature-icon{
            font-size:60px;
        }

        footer{
            margin-top:60px;
        }

    </style>

</head>

<body>

<?php include("navbar.php"); ?>

<div class="container">

    <!-- Hero Section -->
    <div class="hero">

        <h1>Prayer Sutras and Prayer Melodic Management System</h1>

        <p class="lead mt-3">
            Read prayer sutras, listen to prayer melodies and search
            religious content anytime and anywhere.
        </p>

        <a href="search.php" class="btn btn-warning btn-lg mt-3">
            🔍 Search Prayer Content
        </a>

    </div>

    <!-- Features -->
    <div class="row mt-5 g-4">

        <div class="col-md-4">

            <div class="card feature-card h-100 text-center shadow">

                <div class="card-body">

                    <div class="feature-icon">
                        📖
                    </div>

                    <h4 class="mt-3">
                        Prayer Sutras
                    </h4>

                    <p>
                        Browse and read a collection of prayer sutras
                        for spiritual learning and daily practice.
                    </p>

                    <a href="prayer_sutras.php"
                       class="btn btn-primary">
                        View Sutras
                    </a>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card feature-card h-100 text-center shadow">

                <div class="card-body">

                    <div class="feature-icon">
                        🎵
                    </div>

                    <h4 class="mt-3">
                        Prayer Melodies
                    </h4>

                    <p>
                        Listen to peaceful prayer melodies that
                        promote meditation and reflection.
                    </p>

                    <a href="prayer_melodies.php"
                       class="btn btn-success">
                        Listen Now
                    </a>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card feature-card h-100 text-center shadow">

                <div class="card-body">

                    <div class="feature-icon">
                        ⬆️
                    </div>

                    <h4 class="mt-3">
                        Upload Content
                    </h4>

                    <p>
                        Upload new prayer sutras and prayer melodies
                        for other users to access.
                    </p>

                    <a href="upload.php"
                       class="btn btn-warning">
                        Upload
                    </a>

                </div>

            </div>

        </div>

    </div>

    <!-- About -->
    <div class="card mt-5 shadow border-0">

        <div class="card-body">

            <h3>
                About the System
            </h3>

            <p>
                This Prayer Sutras and Prayer Melodic Management System
                is a responsive web application developed using
                HTML, CSS, JavaScript, Bootstrap, PHP and MySQL.
                The system enables users to read prayer sutras,
                listen to prayer melodies, upload religious content,
                and search for information efficiently through an
                intuitive user interface.
            </p>

        </div>

    </div>

</div>
<br/>
<br/>
<?php include("footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

document.addEventListener("DOMContentLoaded",function(){

    console.log("Prayer System Loaded Successfully");

});

</script>

</body>
</html>