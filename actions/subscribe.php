<?php
session_start();
include '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = trim($_POST['email']);
    $subscribe_newsletter = isset($_POST['subscribe']); // Checkbox

    if (empty($email)) {
        header("Location: ../index.php?subscribe=empty");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         header("Location: ../index.php?subscribe=invalid");
         exit();
    }

    // Check if email already exists
    $check_stmt = $conn->prepare("SELECT id FROM subscribers WHERE email = ?");
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        header("Location: ../index.php?subscribe=exists");
        $check_stmt->close();
        exit();
    }
    $check_stmt->close();

    // Insert new subscriber
    $stmt = $conn->prepare("INSERT INTO subscribers (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);

    if ($stmt->execute()) {
        header("Location: ../index.php?subscribe=success");
    } else {
        header("Location: ../index.php?subscribe=error");
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../index.php");
}
?>
