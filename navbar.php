<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('database/db.php');


$user = null;


// ===============================
// Get Current User
// ===============================

if (isset($_SESSION['user_id'])) {

    $id = $_SESSION['user_id'];

    $stmt = $conn->prepare(
        "SELECT id, username, profile_image 
         FROM users 
         WHERE id=?"
    );

    if($stmt){

        $stmt->bind_param("i",$id);
        $stmt->execute();

        $user = $stmt->get_result()->fetch_assoc();

    }

}


// ===============================
// Get Admin
// ===============================

elseif(isset($_SESSION['admin_id'])){


    $id = $_SESSION['admin_id'];


    $stmt = $conn->prepare(
        "SELECT id, username, profile_image 
         FROM users 
         WHERE id=?"
    );


    if($stmt){

        $stmt->bind_param("i",$id);
        $stmt->execute();

        $user = $stmt->get_result()->fetch_assoc();

    }

}



// ===============================
// Profile Image
// ===============================

$profileImage = "default.jpg";


if($user && !empty($user['profile_image'])){


    if(file_exists("uploads/profile/".$user['profile_image'])){

        $profileImage = $user['profile_image'];

    }

}

?>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">

<div class="container-fluid">


<a class="navbar-brand text-warning fw-bold"
href="index.php">

🙏 Prayer System

</a>



<button class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarMenu"
aria-controls="navbarMenu"
aria-expanded="false"
aria-label="Toggle navigation">


<span class="navbar-toggler-icon"></span>


</button>





<div class="collapse navbar-collapse"
id="navbarMenu">



<ul class="navbar-nav me-auto">


<li class="nav-item">
<a class="nav-link" href="index.php">
🏠 Home
</a>
</li>


<li class="nav-item">
<a class="nav-link" href="prayer_sutras.php">
📖 Sutras
</a>
</li>


<li class="nav-item">
<a class="nav-link" href="prayer_melodies.php">
🎵 Melodies
</a>
</li>


<li class="nav-item">
<a class="nav-link" href="upload.php">
📤 Upload
</a>
</li>

</ul>





<!-- PROFILE -->

<?php if($user){ ?>


<div class="dropdown">


<button class="btn btn-outline-light dropdown-toggle d-flex align-items-center"
type="button"
id="profileDropdown"
data-bs-toggle="dropdown"
aria-expanded="false">


<img src="uploads/profile/<?php echo $profileImage; ?>"
width="40"
height="40"
class="rounded-circle me-2"
style="object-fit:cover;">


<?php echo htmlspecialchars($user['username']); ?>


</button>





<ul class="dropdown-menu dropdown-menu-end"
aria-labelledby="profileDropdown">


<li>

<a class="dropdown-item"
href="edit_profile.php">

✏ Edit Profile

</a>

</li>


<li>
<hr class="dropdown-divider">
</li>


<li>

<a class="dropdown-item text-danger"
href="logout.php">

🚪 Logout

</a>

</li>


</ul>


</div>




<?php } else { ?>


<a href="user_login.php"
class="btn btn-warning">

Login

</a>


<?php } ?>



</div>

</div>

</nav>