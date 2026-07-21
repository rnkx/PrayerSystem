<?php

session_start();


// Admin only

if(!isset($_SESSION['admin_id'])){

    header("Location: admin_login.php");
    exit();

}


include('database/db.php');



// ==========================
// Search Sutras
// ==========================

$keyword = isset($_GET['search']) 
? trim($_GET['search']) 
: "";



if($keyword!=""){


    $search="%".$keyword."%";


    $stmt=$conn->prepare(

        "SELECT *
         FROM sutras
         WHERE title LIKE ?
         OR description LIKE ?
         ORDER BY id DESC"

    );


    if(!$stmt){

        die("SQL Error: ".$conn->error);

    }



    $stmt->bind_param(
        "ss",
        $search,
        $search
    );


    $stmt->execute();


    $sutras=$stmt->get_result();



}
else{


    $sutras=$conn->query(

        "SELECT *
         FROM sutras
         ORDER BY id DESC"

    );


}



// Total sutras

$totalSutras=$conn->query(

    "SELECT COUNT(*) AS total FROM sutras"

)->fetch_assoc()['total'];



?>



<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Manage Sutras | Prayer System</title>



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<style>


body{

background:#f4f7fb;

}



.page-title{

margin:35px 0 20px;

text-align:center;

color:#2c3e50;

}



.card{

border:none;

border-radius:12px;

box-shadow:0 5px 15px rgba(0,0,0,.08);

margin-bottom:25px;

}



.search-box{

background:white;

padding:20px;

max-width:700px;

margin:auto;

border-radius:12px;

box-shadow:0 5px 15px rgba(0,0,0,.08);

}



.table img{


width:70px;

height:70px;

object-fit:cover;

border-radius:10px;


}



.table td{

vertical-align:middle;

}

.table td, .table th {
  text-align: center;
}


.description{

max-width:300px;

}



</style>


</head>



<body class="d-flex flex-column min-vh-100">



<?php include('admin_navbar.php'); ?>




<div class="container flex-grow-1">





<h2 class="page-title">

📖 Manage Prayer Sutras

</h2>





<!-- Total Sutras -->


<div class="card">


<div class="card-body text-center">


<h4>Total Uploaded Sutras</h4>


<h2 class="text-primary">

<?php echo $totalSutras; ?>

</h2>


</div>


</div>







<!-- Search -->

<div class="search-box">


<form method="GET"
class="row justify-content-center g-3">


<div class="col-md-6">


<input

type="text"

name="search"

class="form-control"

placeholder="Search sutra title or description..."

value="<?php echo htmlspecialchars($keyword); ?>"

>


</div>




<div class="col-auto">


<button class="btn btn-primary">

🔍 Search

</button>


</div>




<div class="col-auto">


<a href="manage_sutras.php"
class="btn btn-secondary">

Reset

</a>


</div>



</form>


</div>





<br>





<!-- Sutra Table -->


<div class="card">


<div class="card-body">


<div class="table-responsive">



<table class="table table-hover align-middle">



<thead class="table-dark text-center">


<tr>

<th>ID</th>

<th>Image</th>

<th>Title</th>

<th>Description</th>

<th>PDF</th>

<th width="180">Actions</th>


</tr>


</thead>




<tbody>



<?php if($sutras && $sutras->num_rows>0): ?>


<?php while($row=$sutras->fetch_assoc()): ?>


<?php


$image="uploads/image/default.jpg";


if(!empty($row['image']) &&
file_exists("uploads/image/".$row['image'])){


$image="uploads/image/".$row['image'];

}


?>



<tr>



<td class="text-center">

<?php echo $row['id']; ?>

</td>





<td class="text-center">


<img src="<?php echo $image; ?>">


</td>





<td>


<strong>

<?php echo htmlspecialchars($row['title']); ?>

</strong>


</td>





<td class="description">


<?php echo htmlspecialchars($row['description']); ?>


</td>






<td class="text-center">


<a href="uploads/pdf/<?php echo $row['file']; ?>"
target="_blank"
class="btn btn-success btn-sm">


📖 View PDF


</a>


</td>






<td class="text-center">


<a href="edit_sutra.php?id=<?php echo $row['id']; ?>"
class="btn btn-warning btn-sm">


✏ Edit


</a>





<a href="delete_sutra.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this sutra?');">


🗑 Delete


</a>


</td>



</tr>



<?php endwhile; ?>


<?php else: ?>


<tr>


<td colspan="6"
class="text-center text-muted">


No sutras found.


</td>


</tr>


<?php endif; ?>



</tbody>


</table>



</div>


</div>


</div>





</div>




<?php include('footer.php'); ?>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>


</html>