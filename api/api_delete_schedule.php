<?php
// api/api_delete_schedule.php
header('Content-Type: application/json');
session_start();
require_once '../config/db.php';

// 1. 未登录直接返回 JSON
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

// 2. 提取当前用户角色
$user_role = $_SESSION['role'] ?? ($_SESSION['user']['role'] ?? 'Viewer');

// 3. 严格权限检查
if ($user_role !== 'admin' && $user_role !== 'editor') {
    echo json_encode(['success' => false, 'message' => 'Permission denied. Admin or Editor only.']);
    exit;
}

// 4. 检查请求方法
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'Missing schedule ID.']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM schedules WHERE id = :id");
    $stmt->execute(['id' => $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Schedule deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Schedule not found or already deleted.']);
    }
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}