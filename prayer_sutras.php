<?php
session_start();

if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: user_login.php");
    exit();
}

include('database/db.php');

// Search
$keyword = "";

if (isset($_GET['search'])) {
    $keyword = trim($_GET['search']);

    $stmt = $conn->prepare("
        SELECT *
        FROM sutras
        WHERE title LIKE CONCAT('%', ?, '%')
        OR description LIKE CONCAT('%', ?, '%')
        ORDER BY title ASC
    ");

    $stmt->bind_param("ss", $keyword, $keyword);
    $stmt->execute();
    $sutras = $stmt->get_result();

} else {

    $sutras = $conn->query("
        SELECT *
        FROM sutras
        ORDER BY title ASC
    ");

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Prayer Sutras</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">

<style>

body{
    background:#f4f6f9;
}

.page-header{
    background:#4a90e2;
    color:white;
    padding:35px;
    border-radius:12px;
    text-align:center;
    margin:30px 0;
}

.card{
    transition:.3s;
    border:none;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 10px 20px rgba(0,0,0,.15);
}

.card img{
    height:250px;
    object-fit:cover;
}

.btn-read{
    width:100%;
}

.empty-state{
    text-align:center;
    background:white;
    padding:60px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}

</style>

</head>

<body>

<?php include("navbar.php"); ?>

<div class="container">

    <div class="page-header">

        <h1>📖 Prayer Sutras</h1>

        <p class="lead">
            Browse, search and read prayer sutras for spiritual learning and reflection.
        </p>

    </div>

    <form method="GET" class="row mb-4">

        <div class="col-md-10 mb-2">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search prayer sutras..."
                value="<?php echo htmlspecialchars($keyword); ?>">

        </div>

        <div class="col-md-2 d-grid">

            <button class="btn btn-primary">

                Search

            </button>

        </div>

    </form>

    <div class="row">

<?php

if($sutras->num_rows > 0){

while($row = $sutras->fetch_assoc()){

$image = "uploads/image/default.jpg";

if(!empty($row['image']) && file_exists("uploads/image/".$row['image'])){

    $image = "uploads/image/".$row['image'];

}

?>

<div class="col-lg-4 col-md-6 mb-4">

<div class="card shadow h-100">

<img
src="<?php echo $image; ?>"
class="card-img-top"
alt="<?php echo htmlspecialchars($row['title']); ?>">

<div class="card-body d-flex flex-column">

<h4 class="card-title text-primary">

<?php echo htmlspecialchars($row['title']); ?>

</h4>

<p class="card-text">

<?php echo htmlspecialchars($row['description']); ?>

</p>

<div class="mt-auto">

<a
href="uploads/pdf/<?php echo urlencode($row['file']); ?>"
target="_blank"
class="btn btn-primary btn-read">

📖 Read Prayer Sutra

</a>

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

<h2>📖 No Prayer Sutras Found</h2>

<p class="text-muted">

No prayer sutras are currently available.

</p>

<a href="upload.php" class="btn btn-success">

Upload a Prayer Sutra

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

const searchInput = document.querySelector("input[name='search']");

searchInput.addEventListener("focus",function(){

    this.style.boxShadow="0 0 8px rgba(74,144,226,.4)";

});

searchInput.addEventListener("blur",function(){

    this.style.boxShadow="";

});

</script>

</body>
</html>