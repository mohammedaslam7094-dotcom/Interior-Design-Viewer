<?php
session_start();
require_once __DIR__ . '/db.php';

function is_user_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_user_logged_in()) {
        header("Location: /login");
        exit();
    }
}

function get_current_user_id()
{
    return $_SESSION['user_id'] ?? null;
}

function get_current_user_name()
{
    return $_SESSION['user_name'] ?? 'Guest';
}

function login_user($email, $password)
{
    global $conn;
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            return true;
        }
    }
    return false;
}

function register_user($username, $email, $password, $full_name, $phone)
{
    global $conn;

    // Check if email already exists
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        return "Email already registered.";
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password, full_name, phone) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $phone);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Registration failed: " . $conn->error;
    }
}

function logout_user()
{
    session_unset();
    session_destroy();
}

// Notification Helper
function create_notification($user_id, $message)
{
    global $conn;
    $sql = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
}

function get_unread_notifications($user_id)
{
    global $conn;
    $sql = "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function mark_notifications_read($user_id)
{
    global $conn;
    $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}
?>