<?php
// api/api_check_conflict.php
header('Content-Type: application/json');
session_start();
require_once '../config/db.php';

// Coming soon — 排班冲突检测接口
echo json_encode(['status' => 'coming_soon', 'message' => 'Conflict check not yet implemented.']);