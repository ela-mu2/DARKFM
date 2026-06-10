<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 统一拦截未登录用户
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// 提取当前用户角色
$user_role = $_SESSION['role'] ?? ($_SESSION['user']['role'] ?? 'Viewer');
?>