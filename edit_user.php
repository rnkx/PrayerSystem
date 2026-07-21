<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include('database/db.php');

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$id = (int)$_GET['id'];

// Get user
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Update user
if (isset($_POST['update'])) {

    $username = trim($_POST['username']);

    $profileImage = $user['profile_image'];

    // Upload new profile image
    if (!empty($_FILES['profile_image']['name'])) {

        $allowed = ['jpg','jpeg','png','gif'];

        $extension = strtolower(pathinfo(
            $_FILES['profile_image']['name'],
            PATHINFO_EXTENSION
        ));

        if (in_array($extension, $allowed)) {

            if (!is_dir("uploads/profile")) {
                mkdir("uploads/profile",0777,true);
            }

            $profileImage = time() . "_" . basename($_FILES['profile_image']['name']);

            move_uploaded_file(
                $_FILES['profile_image']['tmp_name'],
                "uploads/profile/" . $profileImage
            );
        }
    }

    $stmt = $conn->prepare("
        UPDATE users
        SET username = ?, profile_image = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ssi",
        $username,
        $profileImage,
        $id
    );

    if ($stmt->execute()) {

        echo "<script>
                alert('User updated successfully.');
                window.location='manage_users.php';
              </script>";

        exit();

    } else {

        echo "<div class='alert alert-danger'>Update failed.</div>";

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f7fb;
}

.edit-card{
    max-width:600px;
    margin:50px auto;
    border:none;
    border-radius:15px;
    box-shadow:0 6px 18px rgba(0,0,0,.1);
}

.profile-image{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #ddd;
}

</style>

</head>

<body>

<?php include('admin_navbar.php'); ?>

<div class="container">

<div class="card edit-card">

<div class="card-header bg-primary text-white">

<h3>Edit User</h3>

</div>

<div class="card-body">

<div class="text-center mb-4">

<?php

$image = "uploads/profile/default.png";

if(
    !empty($user['profile_image']) &&
    file_exists("uploads/profile/".$user['profile_image'])
){
    $image = "uploads/profile/".$user['profile_image'];
}

?>

<img
src="<?php echo $image; ?>"
class="profile-image">

</div>

<form
method="POST"
enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">

Username

</label>

<input
type="text"
name="username"
class="form-control"
value="<?php echo htmlspecialchars($user['username']); ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

Profile Image

</label>

<input
type="file"
name="profile_image"
class="form-control"
accept="image/*">

</div>

<div class="d-flex justify-content-between">

<a
href="manage_users.php"
class="btn btn-secondary">

Back

</a>

<button
type="submit"
name="update"
class="btn btn-primary">

Update User

</button>

</div>

</form>

</div>

</div>

</div>

</body>

</html>