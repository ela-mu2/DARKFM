<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 统一拦截未登录用户
// 使用绝对路径重定向，避免因调用方所在目录不同导致路径错误
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user'])) {
    $root = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    // 向上找到项目根目录（DARKFM/），统一跳转到 actions/login.php
    header("Location: /actions/login.php");
    exit;
}

// 提取当前用户角色，统一小写便于比较
$user_role = strtolower($_SESSION['role'] ?? ($_SESSION['user']['role'] ?? 'viewer'));