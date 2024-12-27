<?php
session_start();
require_once '../utils/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = '../index.php';</script>";
}
$user_id = $_SESSION['user_id'];

// Fetch the logged-in user's details
$user_query = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param('i', $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_data = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YS Innovations Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
        .dropdown-menu {
            left: -75px; /* Position the dropdown under the profile icon */
        }
        .job-listing-container {
            margin-top: 50px;
        }
        .job-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .job-title {
            font-size: 24px;
            font-weight: bold;
            color: #343a40;
        }
        .job-location {
            color: #17a2b8;
            font-size: 16px;
        }
        .job-skills {
            color: #dc3545;
            font-size: 14px;
        }
        .job-salary {
            color: #28a745;
            font-size: 18px;
            font-weight: bold;
        }
        .job-details {
            margin-top: 20px;
        }
        .posted-by {
            margin-top: 10px;
            color: #6c757d;
            font-size: 14px;
        }
        .apply-btn {
            margin-top: 15px;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>
<body>

<!-- Navbar with Profile and Add Jobs options -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarProfile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Job Listings -->
<div class="container job-listing-container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center">Welcome to the Dashboard <?php echo $user_data['user_name'] ?></h2>
            <hr>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
