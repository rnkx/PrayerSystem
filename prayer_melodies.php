<?php
session_start();

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: user_login.php");
    exit();
}

include('database/db.php');

$keyword = "";

// Search functionality
if (isset($_GET['search'])) {

    $keyword = trim($_GET['search']);

    $stmt = $conn->prepare("
        SELECT *
        FROM melodies
        WHERE title LIKE CONCAT('%', ?, '%')
        OR description LIKE CONCAT('%', ?, '%')
        ORDER BY title ASC
    ");

    $stmt->bind_param("ss", $keyword, $keyword);

    $stmt->execute();

    $melodies = $stmt->get_result();

} else {

    $melodies = $conn->query("
        SELECT *
        FROM melodies
        ORDER BY title ASC
    ");

}

?>


<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Prayer Melodies</title>


<!-- Bootstrap CSS -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
rel="stylesheet">


<link rel="stylesheet" href="css/style.css">


<style>

body{
    background:#f4f7fb;
}


/* Header */

.page-header{

    background:linear-gradient(135deg,#4a90e2,#6bb6ff);

    color:white;

    padding:40px;

    margin:30px 0;

    border-radius:15px;

    text-align:center;

}


/* Melody Card */

.melody-card{

    border:none;

    border-radius:15px;

    overflow:hidden;

    transition:.3s;

}


.melody-card:hover{

    transform:translateY(-8px);

    box-shadow:0 10px 20px rgba(0,0,0,.15);

}



.melody-card img{

    width:100%;

    height:230px;

    object-fit:cover;

}



audio{

    width:100%;

}



/* Empty Message */

.empty-state{

    background:white;

    padding:50px;

    border-radius:15px;

    text-align:center;

    box-shadow:0 5px 15px rgba(0,0,0,.1);

}


</style>


</head>


<body>


<?php include("navbar.php"); ?>


<div class="container">


<!-- Page Header -->

<div class="page-header">


<h1>
🎵 Prayer Melodies
</h1>


<p class="lead">

Listen to peaceful prayer melodies for meditation and reflection.

</p>


</div>



<!-- Search -->

<form method="GET" class="row mb-5">


<div class="col-md-10 mb-2">


<input

type="text"

name="search"

class="form-control"

placeholder="Search prayer melodies..."

value="<?php echo htmlspecialchars($keyword); ?>"

>


</div>



<div class="col-md-2 d-grid">


<button class="btn btn-primary">

🔍 Search

</button>


</div>


</form>




<div class="row g-4">


<?php


if($melodies->num_rows > 0){


while($row = $melodies->fetch_assoc()){



$image = "uploads/image/default.jpg";


if(!empty($row['image']) && 
file_exists("uploads/image/".$row['image'])){


    $image = "uploads/image/".$row['image'];


}



$audio = "";

if(!empty($row['file'])){


    $audio = "uploads/audio/".$row['file'];


}


?>



<div class="col-lg-4 col-md-6">


<div class="card melody-card shadow h-100">



<img

src="<?php echo $image; ?>"

alt="<?php echo htmlspecialchars($row['title']); ?>"

class="card-img-top"



>



<div class="card-body d-flex flex-column">



<h4 class="text-primary text-center">


<?php echo htmlspecialchars($row['title']); ?>


</h4>




<p class="text-center">


<?php echo htmlspecialchars($row['description']); ?>


</p>




<div class="mt-auto">


<audio controls>


<source 

src="<?php echo $audio; ?>"

type="audio/mpeg">


Your browser does not support audio playback.


</audio>



</div>


</div>



</div>


</div>



<?php


}


}else{


?>


<div class="col-12">


<div class="empty-state">


<h2>
🎵 No Prayer Melodies Found
</h2>


<p class="text-muted">

No prayer melodies are currently available.

</p>



<a href="upload.php" class="btn btn-success">

➕ Upload Melody

</a>


</div>


</div>



<?php

}

?>


</div>


</div>


<br/>
<br/>

<?php include("footer.php"); ?>



<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



<script>


// Audio interaction

document.querySelectorAll("audio").forEach(audio=>{


audio.addEventListener("play",function(){


console.log("Prayer melody started playing");


});


});


</script>



</body>

</html>