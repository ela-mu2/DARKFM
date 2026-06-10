<?php
// api_get.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // 允许跨域（本地开发备用）

require_once 'db.php';

try {
    // 联合查询：排班表、节目表、主持人表
    // 严格对应你在 student-guide.md 中规划的字段名
    $sql = "SELECT 
                s.id AS schedule_id,
                s.air_date,
                s.start_time,
                p.title AS program_title,
                p.genre AS program_genre,
                h.name AS host_name
            FROM schedules s
            LEFT JOIN programs p ON s.program_id = p.id
            LEFT JOIN hosts h ON s.host_id = h.id
            ORDER BY s.air_date DESC, s.start_time ASC";

    $stmt = $pdo->query($sql);
    $schedules = $stmt->fetchAll();

    // 吐出标准 JSON
    echo json_encode([
        'status' => 'success',
        'data' => $schedules
    ], JSON_UNESCAPED_UNICODE);

} catch (\PDOException $e) {
    // 捕获异常，输出错误 JSON（生产环境建议记录日志，这里方便你调试）
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch schedule data: ' . $e->getMessage()
    ]);
}