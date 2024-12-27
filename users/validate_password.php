<?php
require_once '../utils/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $passw = $_POST['password'];

    $query = "SELECT pass FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($passw, $user['pass'])) { 
            echo 'valid'; // Password matches
        } else {
            echo 'invalid'; // Incorrect password
        }
    } else {
        echo 'invalid'; // Email not found
    }

    $stmt->close();
}
?>
