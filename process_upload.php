<?php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: user_login.php");
    exit;
}

include('database/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type']; // "sutra" or "melody"
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    // --- IMAGE UPLOAD (optional) ---
    $imageName = "";
    if (!empty($_FILES['image']['name'])) {
        $allowedImageTypes = ['jpg','jpeg','png','gif'];
        $imageExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($imageExt, $allowedImageTypes)) {
            $imageName = time() . "_" . uniqid() . "." . $imageExt;
            $targetImage = __DIR__ . "/uploads/image/" . $imageName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetImage)) {
                $imageName = ""; // fallback
            }
        } else {
            echo "<script>alert('Invalid image type. Only JPG, PNG, GIF allowed.'); window.location='upload.php';</script>";
            exit;
        }
    }

    // --- FILE UPLOAD (required) ---
    $fileName = "";
   if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $fileExt = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

    if ($type === "sutra" && $fileExt !== "pdf") {
        echo "<script>alert('Invalid file type. Sutras must be PDF.'); window.location='upload.php';</script>";
        exit;
    }
    if ($type === "melody" && !in_array($fileExt, ['mp3','wav','ogg'])) {
        echo "<script>alert('Invalid file type. Melodies must be audio (MP3/WAV/OGG).'); window.location='upload.php';</script>";
        exit;
    }

    $fileName = time() . "_" . uniqid() . "." . $fileExt;
    $targetFile = ($type === "sutra") ? __DIR__ . "/uploads/pdf/" . $fileName : __DIR__ . "/uploads/audio/" . $fileName;

    if (!move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
        echo "<script>alert('Error saving file.'); window.location='upload.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Please upload a file.'); window.location='upload.php';</script>";
    exit;
}


    // --- DATABASE INSERT ---
    if ($type === "sutra") {
        $stmt = $conn->prepare("INSERT INTO sutras (title, description, file, image, created_at) VALUES (?, ?, ?, ?, NOW())");
    } else {
        $stmt = $conn->prepare("INSERT INTO melodies (title, description, file, image, created_at) VALUES (?, ?, ?, ?, NOW())");
    }

    $stmt->bind_param("ssss", $title, $description, $fileName, $imageName);

    if ($stmt->execute()) {
        echo "<script>alert('Upload successful!'); window.location='upload.php';</script>";
    } else {
        echo "<script>alert('Error uploading content.'); window.location='upload.php';</script>";
    }
}
?>
