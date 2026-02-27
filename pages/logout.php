<?php
require_once __DIR__ . '/../includes/user_auth.php';
logout_user();
header("Location: /login");
exit();
?>