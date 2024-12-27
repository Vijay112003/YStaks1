<?php
session_start();
require_once '../utils/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = '../index.php';</script>";
}

$user_id = $_SESSION['user_id'];
$user_sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    $conn->begin_transaction();

    try {
        $delete_user_sql = "DELETE FROM users WHERE id = ?";
        $delete_user_stmt = $conn->prepare($delete_user_sql);
        $delete_user_stmt->bind_param('i', $user_id);
        $delete_user_stmt->execute();

        $conn->commit();
        session_destroy();
        echo "<script>alert('Account deleted successfully.'); window.location.href = '../index.php';</script>";
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to delete account: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];

    $update_sql = "UPDATE users SET user_name=? , email=? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ssi', $user_name, $email, $user_id);
    $conn->begin_transaction();

    try {
        $update_stmt->execute();
        $conn->commit();

        echo "<script>alert('User details updated successfully.'); window.location.href='dashboard.php';</script>";
        $stmt->execute();
        $user_result = $stmt->get_result();
        $user_data = $user_result->fetch_assoc();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error updating record: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .navbar-custom {
            background-color: #343a40;
        }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link {
            color: white;
        }
        .profile-container {
            margin-top: 50px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-title {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .skill-list {
            list-style-type: none;
            padding: 0;
        }
        .skill-list li {
            background: #e9ecef;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">    
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link btn btn-danger btn-sm text-white" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container profile-container">
    <h2 class="profile-title">User Profile</h2>
    <h4>Personal Details</h4>
    <p><strong>Full Name:</strong> <?php echo $user_data['user_name']; ?></p>
    <p><strong>Email:</strong> <?php echo $user_data['email']; ?></p>

    <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
        <button type="submit" name="delete_account" class="btn btn-danger mt-3">Delete Account</button>
        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#updateModal">Update Details</button>
    </form>
</div>

<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Name</label>
                        <input type="text" class="form-control" id="lastname" name="user_name" value="<?php echo $user_data['user_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user_data['email']; ?>" required>
                    </div>
                    <button type="submit" name="update_user" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
