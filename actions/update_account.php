<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $zip_code = $_POST['zip_code'];
    $country = $_POST['country'];

    $sql = "UPDATE users SET full_name = ?, phone = ?, address = ?, city = ?, zip_code = ?, country = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $full_name, $phone, $address, $city, $zip_code, $country, $user_id);

    if ($stmt->execute()) {
        header("Location: ../account.php?update=success");
    } else {
        header("Location: ../account.php?update=error");
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: ../account.php");
}
?>
