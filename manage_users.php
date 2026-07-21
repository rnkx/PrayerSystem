<?php
session_start();

// Restrict access to admins only
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include('database/db.php');

// Handle search keyword
$keyword = isset($_GET['search']) ? trim($_GET['search']) : "";

// Fetch users
if ($keyword !== "") {
    $search = "%" . $keyword . "%";
    $stmt = $conn->prepare("
        SELECT *
        FROM users
        WHERE username LIKE ?
        ORDER BY username ASC
    ");
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $users = $stmt->get_result();
} else {
    $users = $conn->query("
        SELECT *
        FROM users
        ORDER BY username ASC
    ");
}

// Total users
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")
                   ->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users | Prayer System</title>
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
.summary-card, .search-box {
    border: none;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,.08);
    margin-bottom: 25px;
}
.table img {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    object-fit: cover;
}
.table td {
    vertical-align: middle;
}
.table td, .table th {
  text-align: center;
}

.search-box {
    background: #fff;
    padding: 20px;
    max-width: 700px;
    margin: auto;
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

    <h2 class="page-title">👥 Manage Users</h2>

    <!-- Summary -->
    <div class="card summary-card">
        <div class="card-body text-center">
            <h4>Total Registered Users</h4>
            <h2 class="text-primary"><?php echo $totalUsers; ?></h2>
        </div>
    </div>

    <!-- Search -->
    <div class="search-box">
        <form method="GET" class="row justify-content-center g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control"
                       placeholder="Search username..."
                       value="<?php echo htmlspecialchars($keyword); ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <div class="col-auto">
                <a href="manage_users.php" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
<br/>
    <!-- Users Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID</th>
                            <th>Profile</th>
                            <th>Username</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users && $users->num_rows > 0): ?>
                            <?php while ($row = $users->fetch_assoc()): 
                                $image = "uploads/profile/default.png";
                                if (!empty($row['profile_image']) && file_exists("uploads/profile/".$row['profile_image'])) {
                                    $image = "uploads/profile/".$row['profile_image'];
                                }
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $row['id']; ?></td>
                                <td class="text-center"><img src="<?php echo $image; ?>" alt="Profile"></td>
                                <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                                <td class="text-center">
                                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No users found.</td>
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
