<?php

session_start();

include('database/db.php');


// Check login

if(!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])){

    header("Location:user_login.php");
    exit();

}



// Get current user ID

$id = isset($_SESSION['user_id']) 
      ? $_SESSION['user_id'] 
      : $_SESSION['admin_id'];




// Get user data

$stmt = $conn->prepare(
    "SELECT id, username, profile_image 
     FROM users 
     WHERE id=?"
);


if(!$stmt){

    die("Database Error: ".$conn->error);

}


$stmt->bind_param("i",$id);

$stmt->execute();


$result = $stmt->get_result();


$user = $result->fetch_assoc();



if(!$user){

    die("User not found");

}



// Default image

$profileImage = "default.jpg";


if(!empty($user['profile_image']) &&
   file_exists("uploads/profile/".$user['profile_image'])){


    $profileImage = $user['profile_image'];

}




// Update Profile

if(isset($_POST['update'])){


    $username = trim($_POST['username']);



    if(empty($username)){

        $error = "Username cannot be empty";

    }

    else{


        // Keep current image

        $newImage = $profileImage;



        // Upload new image

        if(isset($_FILES['profile_image']) &&
           $_FILES['profile_image']['error'] == 0){



            $allowed = [
                "jpg",
                "jpeg",
                "png"
            ];



            $extension = strtolower(
                pathinfo(
                    $_FILES['profile_image']['name'],
                    PATHINFO_EXTENSION
                )
            );



            if(in_array($extension,$allowed)){



                $newImage =
                time()."_".
                basename($_FILES['profile_image']['name']);



                move_uploaded_file(
                    $_FILES['profile_image']['tmp_name'],
                    "uploads/profile/".$newImage
                );



            }
            else{

                $error = "Only JPG, JPEG and PNG images are allowed";

            }

        }





        if(!isset($error)){



            $update = $conn->prepare(
                "UPDATE users
                 SET username=?, profile_image=?
                 WHERE id=?"
            );



            $update->bind_param(
                "ssi",
                $username,
                $newImage,
                $id
            );



            $update->execute();



            header("Location:index.php");

            exit();

        }


    }

}



?>



<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Profile</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


</head>



<body class="bg-light">


<?php include('navbar.php'); ?>



<div class="container mt-5">


<div class="row justify-content-center">


<div class="col-md-6">



<div class="card shadow p-4">



<h2 class="text-center mb-4">

✏ Edit Profile

</h2>



<?php if(isset($error)){ ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>





<form method="POST"
enctype="multipart/form-data">





<div class="text-center mb-3">


<img src="uploads/profile/<?php echo $profileImage; ?>"
width="140"
height="140"
class="rounded-circle"
style="object-fit:cover;">


</div>





<label class="form-label">

Username

</label>


<input 
type="text"
name="username"
class="form-control mb-3"
value="<?php echo htmlspecialchars($user['username']); ?>"
required>





<label class="form-label">

Change Profile Image

</label>


<input 
type="file"
name="profile_image"
class="form-control mb-4"
accept="image/png,image/jpeg">





<button 
type="submit"
name="update"
class="btn btn-primary w-100">

💾 Update Profile

</button>




</form>




</div>


</div>


</div>


</div>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>