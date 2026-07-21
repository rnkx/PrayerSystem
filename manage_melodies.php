<?php
session_start();

// Admin only
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include('database/db.php');

// ==========================
// Search Melodies
// ==========================
$keyword = isset($_GET['search']) ? trim($_GET['search']) : "";

if ($keyword !== "") {
    $search = "%" . $keyword . "%";
    $stmt = $conn->prepare("
        SELECT *
        FROM melodies
        WHERE title LIKE ?
        OR description LIKE ?
        ORDER BY id DESC
    ");
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $melodies = $stmt->get_result();
} else {
    $melodies = $conn->query("SELECT * FROM melodies ORDER BY id DESC");
}

// Total melodies
$totalMelodies = $conn->query("SELECT COUNT(*) AS total FROM melodies")
                      ->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Melodies | Prayer System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #f4f7fb;
}
.page-title {
    margin: 35px 0 20px;
    text-align: center;
    color: #2c3e50;
}
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,.08);
    margin-bottom: 25px;
}
.search-box {
    background: #fff;
    padding: 20px;
    max-width: 700px;
    margin: auto;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,.08);
}
.table td {
    vertical-align: middle;
    text-align: center;
}
.table img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 10px;
}
.description {
    max-width: 300px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.table-hover tbody tr:hover {
    background-color: #f0f4f8;
}
audio {
    width: 200px;
}
footer {
    margin-top: auto;
    background: #2c3e50;
    color: #fff;
    padding: 15px;
    text-align: center;
}
</style>
</head>
<body class="d-flex flex-column min-vh-100">

<?php include('admin_navbar.php'); ?>

<div class="container flex-grow-1">

    <h2 class="page-title">🎶 Manage Melodies</h2>

    <!-- Total Melodies -->
    <div class="card">
        <div class="card-body text-center">
            <h4>Total Uploaded Melodies</h4>
            <h2 class="text-primary"><?php echo $totalMelodies; ?></h2>
        </div>
    </div>

    <!-- Search -->
    <div class="search-box">
        <form method="GET" class="row justify-content-center g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control"
                       placeholder="Search melody title or description..."
                       value="<?php echo htmlspecialchars($keyword); ?>">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">🔍 Search</button>
            </div>
            <div class="col-auto">
                <a href="manage_melodies.php" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
<br/>
    <!-- Melodies Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Audio</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($melodies && $melodies->num_rows > 0): ?>
                            <?php while ($row = $melodies->fetch_assoc()): 
                                $image = "uploads/image/default.jpg";
                                if (!empty($row['image']) && file_exists("uploads/image/".$row['image'])) {
                                    $image = "uploads/image/".$row['image'];
                                }
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><img src="<?php echo $image; ?>" alt="Melody Cover"></td>
                                <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                <td class="description" title="<?php echo htmlspecialchars($row['description']); ?>">
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </td>
                                <td>
                                    <audio controls>
                                        <source src="uploads/audio/<?php echo $row['file']; ?>" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </td>
                               <td class="text-center">


<a href="edit_melodies.php?id=<?php echo $row['id']; ?>"
class="btn btn-warning btn-sm">


✏ Edit


</a>





<a href="delete_melodies.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this melody?');">


🗑 Delete


</a>


</td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No melodies found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<br/>
<?php include('footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
