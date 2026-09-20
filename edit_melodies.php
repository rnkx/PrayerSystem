```php
<?php

session_start();

// ==========================
// Admin Only
// ==========================

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include('database/db.php');


// ==========================
// Check Melody ID
// ==========================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_melodies.php");
    exit();
}

$id = intval($_GET['id']);


// ==========================
// Get Melody
// ==========================

$stmt = $conn->prepare("
    SELECT *
    FROM melodies
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Melody not found.";
    exit();
}

$melody = $result->fetch_assoc();


// ==========================
// Error Variable
// ==========================

$error = "";


// ==========================
// Update Melody
// ==========================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    $oldImage = $melody['image'];
    $oldFile = $melody['file'];

    $newImage = $oldImage;
    $newFile = $oldFile;


    // ==========================
    // Validate Title
    // ==========================

    if ($title === "") {

        $error = "Melody title is required.";

    }


    // ==========================
    // Upload New Image
    // ==========================

    if (
        $error === "" &&
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === 0
    ) {

        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];

        $imageExt = strtolower(
            pathinfo($imageName, PATHINFO_EXTENSION)
        );

        $allowedImage = [
            "jpg",
            "jpeg",
            "png",
            "gif",
            "webp"
        ];

        if (!in_array($imageExt, $allowedImage)) {

            $error = "Invalid image format. Please upload JPG, JPEG, PNG, GIF or WEBP.";

        } else {

            $newImage = time() . "_" . uniqid() . "." . $imageExt;

            $imagePath = "uploads/image/" . $newImage;

            if (move_uploaded_file($imageTmp, $imagePath)) {

                // Delete old image
                if (
                    !empty($oldImage) &&
                    $oldImage !== "default.jpg" &&
                    file_exists("uploads/image/" . $oldImage)
                ) {

                    unlink("uploads/image/" . $oldImage);

                }

            } else {

                $error = "Failed to upload the new image.";

                $newImage = $oldImage;

            }
        }
    }


    // ==========================
    // Upload New Audio
    // ==========================

    if (
        $error === "" &&
        isset($_FILES['audio']) &&
        $_FILES['audio']['error'] === 0
    ) {

        $audioName = $_FILES['audio']['name'];
        $audioTmp = $_FILES['audio']['tmp_name'];

        $audioExt = strtolower(
            pathinfo($audioName, PATHINFO_EXTENSION)
        );

        $allowedAudio = [
            "mp3",
            "wav",
            "ogg",
            "m4a"
        ];

        if (!in_array($audioExt, $allowedAudio)) {

            $error = "Invalid audio format. Please upload MP3, WAV, OGG or M4A.";

        } else {

            $newFile = time() . "_" . uniqid() . "." . $audioExt;

            $audioPath = "uploads/audio/" . $newFile;

            if (move_uploaded_file($audioTmp, $audioPath)) {

                // Delete old audio
                if (
                    !empty($oldFile) &&
                    file_exists("uploads/audio/" . $oldFile)
                ) {

                    unlink("uploads/audio/" . $oldFile);

                }

            } else {

                $error = "Failed to upload the new audio.";

                $newFile = $oldFile;

            }
        }
    }


    // ==========================
    // Update Database
    // ==========================

    if ($error === "") {

        $stmt = $conn->prepare("
            UPDATE melodies
            SET
                title = ?,
                description = ?,
                image = ?,
                file = ?
            WHERE id = ?
        ");

        if (!$stmt) {

            $error = "Database error: " . $conn->error;

        } else {

            $stmt->bind_param(
                "ssssi",
                $title,
                $description,
                $newImage,
                $newFile,
                $id
            );

            if ($stmt->execute()) {

                header(
                    "Location: manage_melodies.php?success=updated"
                );

                exit();

            } else {

                $error = "Failed to update melody: " . $stmt->error;

            }
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Edit Melody | Prayer System</title>


<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<style>

body {
    background: #f4f7fb;
}

.page-title {
    margin: 35px 0 25px;
    text-align: center;
    color: #2c3e50;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,.08);
}

.current-image {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #ddd;
}

audio {
    width: 100%;
    max-width: 500px;
}

.form-label {
    color: #2c3e50;
}

</style>

</head>


<body class="d-flex flex-column min-vh-100">


<?php include('admin_navbar.php'); ?>


<div class="container flex-grow-1">


<h2 class="page-title">

🎶 Edit Prayer Melody

</h2>


<div class="card mb-5">

<div class="card-body p-4">


<!-- ==========================
     Error Message
========================== -->

<?php if ($error !== ""): ?>

<div class="alert alert-danger">

<strong>Error:</strong>

<?php echo htmlspecialchars($error); ?>

</div>

<?php endif; ?>


<form
method="POST"
enctype="multipart/form-data">


<!-- ==========================
     Melody Title
========================== -->

<div class="mb-3">

<label class="form-label">

<strong>Melody Title</strong>

</label>


<input
type="text"
name="title"
class="form-control"
value="<?php echo htmlspecialchars($melody['title']); ?>"
placeholder="Enter melody title"
required>

</div>


<!-- ==========================
     Description
========================== -->

<div class="mb-3">

<label class="form-label">

<strong>Description</strong>

</label>


<textarea
name="description"
class="form-control"
rows="5"
placeholder="Enter melody description"><?php

echo htmlspecialchars($melody['description']);

?></textarea>

</div>


<!-- ==========================
     Current Image
========================== -->

<div class="mb-3">

<label class="form-label">

<strong>Current Image</strong>

</label>

<br>


<?php

$currentImage = "uploads/image/default.jpg";

if (
    !empty($melody['image']) &&
    file_exists("uploads/image/" . $melody['image'])
) {

    $currentImage =
        "uploads/image/" . $melody['image'];

}

?>


<img
src="<?php echo htmlspecialchars($currentImage); ?>"
class="current-image"
alt="Current Melody Image">


</div>


<!-- ==========================
     Replace Image
========================== -->

<div class="mb-3">

<label class="form-label">

<strong>Replace Image</strong>

</label>


<input
type="file"
name="image"
class="form-control"
accept=".jpg,.jpeg,.png,.gif,.webp">


<div class="form-text">

Leave this empty if you do not want to change the image.

</div>

</div>


<!-- ==========================
     Current Audio
========================== -->

<div class="mb-3">

<label class="form-label">

<strong>Current Audio</strong>

</label>

<br>


<?php if (
    !empty($melody['file']) &&
    file_exists("uploads/audio/" . $melody['file'])
): ?>


<audio controls>

<source
src="uploads/audio/<?php echo htmlspecialchars($melody['file']); ?>">

Your browser does not support the audio element.

</audio>


<?php else: ?>


<div class="alert alert-secondary">

No audio file found.

</div>


<?php endif; ?>


</div>


<!-- ==========================
     Replace Audio
========================== -->

<div class="mb-4">

<label class="form-label">

<strong>Replace Audio</strong>

</label>


<input
type="file"
name="audio"
class="form-control"
accept=".mp3,.wav,.ogg,.m4a">


<div class="form-text">

Leave this empty if you do not want to change the audio.

</div>

</div>


<!-- ==========================
     Buttons
========================== -->

<div class="d-flex gap-2">


<button
type="submit"
class="btn btn-primary">

💾 Save Changes

</button>


<a
href="manage_melodies.php"
class="btn btn-secondary">

↩ Cancel

</a>


</div>


</form>


</div>

</div>


</div>


<?php include('footer.php'); ?>


<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>

