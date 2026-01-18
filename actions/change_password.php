<?php
session_start();
include '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo "<script>alert('All fields are required.'); window.location.href='../account.php';</script>";
        exit;
    }

    if ($new_password !== $confirm_password) {
        echo "<script>alert('New passwords do not match.'); window.location.href='../account.php';</script>";
        exit;
    }

    // specific check for security: don't allow same password
    if ($current_password === $new_password) {
        echo "<script>alert('New password cannot be the same as the current password.'); window.location.href='../account.php';</script>";
        exit;
    }

    // Verify current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($current_password, $user['password'])) {
            // Update password
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $new_hashed_password, $user_id);
            
            if ($update_stmt->execute()) {
                echo "<script>alert('Password updated successfully.'); window.location.href='../account.php';</script>";
            } else {
                echo "<script>alert('Error updating password.'); window.location.href='../account.php';</script>";
            }
            $update_stmt->close();
        } else {
            echo "<script>alert('Incorrect current password.'); window.location.href='../account.php';</script>";
        }
    } else {
        echo "<script>alert('User not found.'); window.location.href='../actions/logout.php';</script>";
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../index.php");
    exit;
}
?>
