<?php

session_start();

// Admin only
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include('database/db.php');


// ==========================
// Check ID
// ==========================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_melodies.php");
    exit();
}

$id = intval($_GET['id']);


// ==========================
// Get Melody Information
// ==========================

$stmt = $conn->prepare("
    SELECT image, file
    FROM melodies
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();


// Melody does not exist

if ($result->num_rows == 0) {

    header("Location: manage_melodies.php?error=not_found");
    exit();

}

$melody = $result->fetch_assoc();

$image = $melody['image'];
$audio = $melody['file'];


// ==========================
// Delete Database Record
// ==========================

$stmt = $conn->prepare("
    DELETE FROM melodies
    WHERE id = ?
");

$stmt->bind_param("i", $id);


if ($stmt->execute()) {


    // ==========================
    // Delete Image
    // ==========================

    if (
        !empty($image) &&
        file_exists("uploads/image/" . $image)
    ) {

        unlink("uploads/image/" . $image);

    }


    // ==========================
    // Delete Audio
    // ==========================

    if (
        !empty($audio) &&
        file_exists("uploads/audio/" . $audio)
    ) {

        unlink("uploads/audio/" . $audio);

    }


    // ==========================
    // Return to Manage Melodies
    // ==========================

    header(
        "Location: manage_melodies.php?success=deleted"
    );

    exit();


} else {

    echo "Error deleting melody: " . $conn->error;

}

?>