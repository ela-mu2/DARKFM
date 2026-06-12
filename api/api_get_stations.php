<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// 修正：改为你项目中真实的数据库连接文件路径
require_once '../config/db.php'; 

try {
    // 确保这里的变量名 $pdo 和你 config/db.php 里定义的一模一样
    $stmt = $pdo->prepare("SELECT id, name, frequency, description, listener_count, is_live FROM stations ORDER BY id DESC");
    $stmt->execute();
    $stations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $stations
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}