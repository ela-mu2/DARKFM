<?php
header('Content-Type: application/json');
session_start();

// 1. 修复鉴权：对齐 login.php 里的 $_SESSION['username'] 或者 $_SESSION['user_id']
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => '未登录或登录已过期']);
    exit;
}

// 2. 引入数据库配置文件
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        try {
            // 3. 修复变量名：把 $db 改成真正的 PDO 实例 $pdo
            $stmt = $pdo->prepare("DELETE FROM hosts WHERE id = :id");
            $stmt->execute([':id' => $id]);
            
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => '数据库错误: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => '无效的 Host ID']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '非法请求方式']);
}