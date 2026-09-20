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
    header("Location: manage_sutras.php");
    exit();
}

$id = intval($_GET['id']);


// ==========================
// Get Sutra Information
// ==========================

$stmt = $conn->prepare(
    "SELECT image, file
     FROM sutras
     WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();


// Sutra does not exist
if ($result->num_rows == 0) {

    header("Location: manage_sutras.php?error=not_found");
    exit();

}

$sutra = $result->fetch_assoc();

$image = $sutra['image'];
$pdf = $sutra['file'];


// ==========================
// Delete Database Record
// ==========================

$stmt = $conn->prepare(
    "DELETE FROM sutras
     WHERE id = ?"
);

$stmt->bind_param("i", $id);

if ($stmt->execute()) {


    // ==========================
    // Delete Image File
    // ==========================

    if (
        !empty($image) &&
        file_exists("uploads/image/" . $image)
    ) {

        unlink("uploads/image/" . $image);

    }


    // ==========================
    // Delete PDF File
    // ==========================

    if (
        !empty($pdf) &&
        file_exists("uploads/pdf/" . $pdf)
    ) {

        unlink("uploads/pdf/" . $pdf);

    }


    // Return to manage page

    header("Location: manage_sutras.php?success=deleted");
    exit();


} else {

    echo "Error deleting sutra: " . $conn->error;

}

?>