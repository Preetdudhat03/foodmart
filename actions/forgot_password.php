<?php
header('Content-Type: application/json');
include '../includes/db_connection.php';
include '../includes/mailer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email is required.']);
        exit;
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id, full_name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
        $full_name = $user['full_name'];

        // Generate temporary password
        $temp_password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
        $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);

        // Update password in DB
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $hashed_password, $user_id);
        
        if ($update_stmt->execute()) {
             // Send email
            $subject = "Password Recovery - FoodMart";
            $body = "<h3>Hello, $full_name</h3><p>You requested a password recovery. Your new temporary password is:</p><h2 style='color: #FFC43F;'>$temp_password</h2><p>Please login and change your password immediately.</p>";
            
            if (sendMail($email, $subject, $body)) {
                 echo json_encode(['success' => true, 'message' => 'A new password has been sent to your email.']);
            } else {
                 echo json_encode(['success' => false, 'message' => 'Failed to send email. Please try again later.']);
            }
        } else {
             echo json_encode(['success' => false, 'message' => 'Database update failed.']);
        }
        $update_stmt->close();

    } else {
        // Security: Don't reveal if email exists or not, or just say "If email exists..."
        // But for better UX here we will say email not found
        echo json_encode(['success' => false, 'message' => 'Email not found.']);
    }

    $stmt->close();
    $conn->close();
}
?>
