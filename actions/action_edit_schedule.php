<?php
// action_edit_schedule.php
require_once 'header.php';
require_once 'db.php';

$user_role = strtolower($_SESSION['role'] ?? ($_SESSION['user']['role'] ?? 'viewer'));
if ($user_role !== 'admin' && $user_role !== 'editor') {
    die("Permission denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $program_id = $_POST['program_id'] ?? null;
    $host_id = $_POST['host_id'] ?? null;
    $air_date = $_POST['air_date'] ?? null;
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;

    if (!$id || !$program_id || !$host_id || !$air_date || !$start_time || !$end_time) {
        die("All fields are required.");
    }

    try {
        $stmt = $pdo->prepare("UPDATE schedules SET 
            program_id = :program_id, 
            host_id = :host_id, 
            air_date = :air_date, 
            start_time = :start_time, 
            end_time = :end_time 
            WHERE id = :id");
        
        $stmt->execute([
            'program_id' => $program_id,
            'host_id' => $host_id,
            'air_date' => $air_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'id' => $id
        ]);

        // 更新成功，直接重定向回主管理页
        header("Location: manage-schedule.php");
        exit;

    } catch (\PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}