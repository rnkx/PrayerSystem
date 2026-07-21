<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include('database/db.php');

// Check user ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = (int)$_GET['id'];

// Prevent admin from deleting their own account
if ($user_id == $_SESSION['admin_id']) {
    echo "<script>
            alert('You cannot delete your own administrator account.');
            window.location='manage_users.php';
          </script>";
    exit();
}

// Get user information
$stmt = $conn->prepare("
    SELECT profile_image
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {

    echo "<script>
            alert('User not found.');
            window.location='manage_users.php';
          </script>";
    exit();

}

$user = $result->fetch_assoc();

// Delete profile image if it exists
if (!empty($user['profile_image']) &&
    $user['profile_image'] != "default.jpg") {

    $imagePath = "uploads/profile/" . $user['profile_image'];

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

// Delete user
$stmt = $conn->prepare("
    DELETE FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {

    echo "<script>
            alert('User deleted successfully.');
            window.location='manage_users.php';
          </script>";

} else {

    echo "<script>
            alert('Failed to delete user.');
            window.location='manage_users.php';
          </script>";

}
?>