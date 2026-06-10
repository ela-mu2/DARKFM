<?php
// api/api_check_conflict.php
header('Content-Type: application/json');
session_start();
require_once '../config/db.php';

// 1. 严格权限拦截：仅限管理员 (admin) 和编辑 (editor) 进行排班操作
if (!isset($_SESSION['role']) || !in_array(strtolower($_SESSION['role']), ['admin', 'editor'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

// 2. 仅接收 POST 请求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// 3. 获取并安全解析前端传来的参数
$host_id    = isset($_POST['host_id']) ? (int)$_POST['host_id'] : null;
$date       = isset($_POST['date']) ? trim($_POST['date']) : null;
$start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : null;
$end_time   = isset($_POST['end_time']) ? trim($_POST['end_time']) : null;
$exclude_id = isset($_POST['exclude_id']) ? (int)$_POST['exclude_id'] : null; // 用于编辑回显时排除自身

// 验证必填字段
if (!$host_id || !$date || !$start_time || !$end_time) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
    exit;
}

try {
    // 4. 冲突检测核心 SQL 逻辑（将 date 改为 air_date）
    $sql = "SELECT COUNT(*) FROM schedules 
            WHERE host_id = :host_id 
              AND air_date = :air_date 
              AND start_time < :end_time 
              AND end_time > :start_time";
    
    // 如果是编辑模式，排除当前正在修改的这条排班记录本身
    if ($exclude_id) {
        $sql .= " AND id != :exclude_id";
    }

    $stmt = $pdo->prepare($sql);
    $params = [
        ':host_id'    => $host_id,
        ':air_date'   => $date, // 前端传来的数据保持不变，绑定到 :air_date 上
        ':start_time' => $start_time,
        ':end_time'   => $end_time
    ];
    if ($exclude_id) {
        $params[':exclude_id'] = $exclude_id;
    }

    $stmt->execute($params);
    $hasConflict = $stmt->fetchColumn() > 0;

    // 5. 根据检测结果返回对应的 JSON 响应
    if ($hasConflict) {
        echo json_encode(['status' => 'conflict', 'message' => '该主持人在该时间段已有排班任务！']);
    } else {
        echo json_encode(['status' => 'success', 'message' => '时间段可用，无冲突。']);
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}