<?php
session_start(); // Start the session
require_once '../utils/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $u_name = $firstname . ' ' . $middlename . ' ' . $lastname;
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    // Insert user data into the users table
    $sql = "INSERT INTO users (user_name, email, pass)
                VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss',  $u_name,$email, $password);

    if ($stmt->execute()) {
        $user_id = $conn->insert_id; // Get the last inserted user ID

        // Store the user_id in the session
        $_SESSION['user_id'] = $user_id;

        // Redirect to skills form after registration
        echo ("<script>alert('Thank you for registering. Please fill in your skills.')</script>");
        header('Location: dashboard.php');
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>