<?php
// api/api_get.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../config/db.php';

try {
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

    echo json_encode([
        'status' => 'success',
        'data'   => $schedules
    ], JSON_UNESCAPED_UNICODE);

} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Failed to fetch schedule data: ' . $e->getMessage()
    ]);
}