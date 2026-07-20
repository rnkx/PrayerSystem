<?php
session_start();
include 'database/db.php';

$error = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] == "admin") {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: index.php");
                }

                exit();

            } else {
                $error = "Invalid password.";
            }

        } else {
            $error = "Username not found.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f5f5;
}

.login-card{

    margin-top:80px;
    border-radius:15px;
    box-shadow:0px 0px 15px rgba(0,0,0,.15);

}

.logo{

    font-size:35px;
    color:#6f42c1;
    font-weight:bold;

}

</style>

</head>

<body>

<div class="container">

<div class="row justify-content-center">

<div class="col-md-5">

<div class="card login-card">

<div class="card-body">

<h2 class="text-center mb-4 logo">

Prayer System

</h2>

<h4 class="text-center">

Login

</h4>

<?php

if($error!="")
{

echo "<div class='alert alert-danger'>$error</div>";

}

?>

<form method="POST">

<div class="mb-3">

<label class="form-label">

Username

</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Password

</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<div class="d-grid">

<button
class="btn btn-primary"
name="login">

Login

</button>

</div>

</form>

<hr>

<div class="text-center">

<a href="index.php">

← Back to Home

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</body>

</html>