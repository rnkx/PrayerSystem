<?php

session_start();

if(!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])){

    header("Location:user_login.php");
    exit();

}


include('database/db.php');


$q = isset($_GET['q']) ? trim($_GET['q']) : "";


$sutraResults=[];
$melodyResults=[];



if($q!=""){


    $keyword="%".$q."%";


    // Search Sutras

    $stmt=$conn->prepare(
        "SELECT *
         FROM sutras
         WHERE title LIKE ?
         OR description LIKE ?"
    );


    $stmt->bind_param(
        "ss",
        $keyword,
        $keyword
    );


    $stmt->execute();


    $sutraResults=$stmt->get_result();



    // Search Melodies

    $stmt=$conn->prepare(
        "SELECT *
         FROM melodies
         WHERE title LIKE ?
         OR description LIKE ?"
    );


    $stmt->bind_param(
        "ss",
        $keyword,
        $keyword
    );


    $stmt->execute();


    $melodyResults=$stmt->get_result();


}



?>


<!DOCTYPE html>
<html lang="en">


<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width,initial-scale=1">


<title>Search Prayer Content</title>



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<link rel="stylesheet" href="css/style.css">



<style>


body{

background:#f4f7fb;

}


.page-header{

background:#4a90e2;

color:white;

padding:35px;

border-radius:15px;

margin:30px 0;

text-align:center;

}


.card{

border:none;

transition:.3s;

}


.card:hover{

transform:translateY(-5px);

box-shadow:0 10px 20px rgba(0,0,0,.15);

}



.card img{

height:220px;

object-fit:cover;

}



audio{

width:100%;

}



</style>


</head>



<body>


<?php include("navbar.php"); ?>



<div class="container">



<div class="page-header">


<h1>
🔍 Search Religious Content
</h1>


<p>
Search prayer sutras and prayer melodies easily.
</p>


</div>




<form method="GET" class="row mb-5">


<div class="col-md-10">


<input

type="text"

name="q"

class="form-control"

placeholder="Search prayer content..."

value="<?php echo htmlspecialchars($q); ?>"

>


</div>



<div class="col-md-2 d-grid">


<button class="btn btn-primary">

Search

</button>


</div>


</form>





<?php if($q!=""){ ?>


<h4 class="mb-4">

Search Results For:
<strong>
<?php echo htmlspecialchars($q); ?>
</strong>

</h4>



<div class="row g-4">



<?php


$count=0;



while($row=$sutraResults->fetch_assoc()){


$count++;


$image="uploads/image/default.jpg";


if(!empty($row['image']) &&
file_exists("uploads/image/".$row['image'])){


$image="uploads/image/".$row['image'];

}


?>


<div class="col-lg-4 col-md-6">


<div class="card shadow h-100">


<img src="<?php echo $image; ?>"
class="card-img-top">



<div class="card-body">


<h4 class="text-primary">

<?php echo htmlspecialchars($row['title']); ?>

</h4>


<p>

<?php echo htmlspecialchars($row['description']); ?>

</p>



<a href="uploads/pdf/<?php echo $row['file']; ?>"
target="_blank"
class="btn btn-primary">

📖 Read Sutra

</a>


</div>


</div>


</div>


<?php } ?>





<?php


while($row=$melodyResults->fetch_assoc()){


$count++;


$image="uploads/image/default.jpg";


if(!empty($row['image']) &&
file_exists("uploads/image/".$row['image'])){


$image="uploads/image/".$row['image'];

}


?>


<div class="col-lg-4 col-md-6">


<div class="card shadow h-100">


<img src="<?php echo $image; ?>"
class="card-img-top">


<div class="card-body">


<h4 class="text-success">

<?php echo htmlspecialchars($row['title']); ?>

</h4>


<p>

<?php echo htmlspecialchars($row['description']); ?>

</p>



<audio controls>

<source src="uploads/audio/<?php echo $row['file']; ?>"
type="audio/mpeg">

</audio>


</div>


</div>


</div>


<?php } ?>



</div>



<?php


if($count==0){


echo "

<div class='alert alert-warning text-center mt-4'>

No prayer content found.

</div>";

}


?>


<?php }else{ ?>


<div class="alert alert-info text-center">

Please enter a keyword to search.

</div>


<?php } ?>



</div>


<br/>
<br/>

<?php include("footer.php"); ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>


</html>