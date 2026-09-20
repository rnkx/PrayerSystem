<?php

session_start();

// Admin only
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include('database/db.php');

// Check ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_sutras.php");
    exit();
}

$id = intval($_GET['id']);

// Get existing sutra
$stmt = $conn->prepare("SELECT * FROM sutras WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Sutra not found.";
    exit();
}

$sutra = $result->fetch_assoc();


// ==========================
// Update Sutra
// ==========================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    $oldImage = $sutra['image'];
    $oldFile = $sutra['file'];

    $newImage = $oldImage;
    $newFile = $oldFile;


    // ==========================
    // Upload New Image
    // ==========================

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];

        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        $allowedImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($imageExt, $allowedImage)) {

            $error = "Invalid image format.";

        } else {

            $newImage = time() . "_" . uniqid() . "." . $imageExt;

            $imagePath = "uploads/image/" . $newImage;

            if (move_uploaded_file($imageTmp, $imagePath)) {

                // Delete old image
                if (
                    !empty($oldImage) &&
                    file_exists("uploads/image/" . $oldImage)
                ) {
                    unlink("uploads/image/" . $oldImage);
                }

            } else {

                $error = "Failed to upload image.";

            }
        }
    }


    // ==========================
    // Upload New PDF
    // ==========================

    if (
        !isset($error) &&
        isset($_FILES['pdf']) &&
        $_FILES['pdf']['error'] === 0
    ) {

        $pdfName = $_FILES['pdf']['name'];
        $pdfTmp = $_FILES['pdf']['tmp_name'];

        $pdfExt = strtolower(pathinfo($pdfName, PATHINFO_EXTENSION));

        if ($pdfExt !== 'pdf') {

            $error = "Only PDF files are allowed.";

        } else {

            $newFile = time() . "_" . uniqid() . ".pdf";

            $pdfPath = "uploads/pdf/" . $newFile;

            if (move_uploaded_file($pdfTmp, $pdfPath)) {

                // Delete old PDF
                if (
                    !empty($oldFile) &&
                    file_exists("uploads/pdf/" . $oldFile)
                ) {
                    unlink("uploads/pdf/" . $oldFile);
                }

            } else {

                $error = "Failed to upload PDF.";

            }
        }
    }


    // ==========================
    // Update Database
    // ==========================

    if (!isset($error)) {

        if ($title === "") {

            $error = "Sutra title is required.";

        } else {

            $stmt = $conn->prepare(
                "UPDATE sutras
                 SET title = ?, description = ?, image = ?, file = ?
                 WHERE id = ?"
            );

            $stmt->bind_param(
                "ssssi",
                $title,
                $description,
                $newImage,
                $newFile,
                $id
            );

            if ($stmt->execute()) {

                header("Location: manage_sutras.php?success=updated");
                exit();

            } else {

                $error = "Failed to update sutra.";
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

<title>Edit Sutra | Prayer System</title>

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

</style>

</head>

<body class="d-flex flex-column min-vh-100">

<?php include('admin_navbar.php'); ?>

<div class="container flex-grow-1">

<h2 class="page-title">
    ✏️ Edit Prayer Sutra
</h2>

<div class="card mb-5">

<div class="card-body p-4">

<?php if (isset($error)): ?>

<div class="alert alert-danger">
    <?php echo htmlspecialchars($error); ?>
</div>

<?php endif; ?>


<form method="POST"
      enctype="multipart/form-data">


<!-- Title -->

<div class="mb-3">

<label class="form-label">
    <strong>Sutra Title</strong>
</label>

<input
type="text"
name="title"
class="form-control"
value="<?php echo htmlspecialchars($sutra['title']); ?>"
required>

</div>


<!-- Description -->

<div class="mb-3">

<label class="form-label">
    <strong>Description</strong>
</label>

<textarea
name="description"
class="form-control"
rows="5"
><?php echo htmlspecialchars($sutra['description']); ?></textarea>

</div>


<!-- Current Image -->

<div class="mb-3">

<label class="form-label">
    <strong>Current Image</strong>
</label>

<br>

<?php

$currentImage = "uploads/image/default.jpg";

if (
    !empty($sutra['image']) &&
    file_exists("uploads/image/" . $sutra['image'])
) {
    $currentImage = "uploads/image/" . $sutra['image'];
}

?>

<img
src="<?php echo htmlspecialchars($currentImage); ?>"
class="current-image">

</div>


<!-- New Image -->

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
    Leave empty if you do not want to change the image.
</div>

</div>


<!-- Current PDF -->

<div class="mb-3">

<label class="form-label">
    <strong>Current PDF</strong>
</label>

<br>

<?php if (!empty($sutra['file'])): ?>

<a
href="uploads/pdf/<?php echo htmlspecialchars($sutra['file']); ?>"
target="_blank"
class="btn btn-success btn-sm">

📖 View Current PDF

</a>

<?php else: ?>

<span class="text-muted">
    No PDF uploaded.
</span>

<?php endif; ?>

</div>


<!-- New PDF -->

<div class="mb-4">

<label class="form-label">
    <strong>Replace PDF</strong>
</label>

<input
type="file"
name="pdf"
class="form-control"
accept=".pdf">

<div class="form-text">
    Leave empty if you do not want to change the PDF.
</div>

</div>


<!-- Buttons -->

<div class="d-flex gap-2">

<button
type="submit"
class="btn btn-primary">

💾 Save Changes

</button>

<a
href="manage_sutras.php"
class="btn btn-secondary">

Cancel

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