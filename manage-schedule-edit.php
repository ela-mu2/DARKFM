<?php
// manage-schedule-edit.php
require_once 'header.php'; // 验证登录与角色
require_once 'db.php';

// 严格权限：只有 admin 和 editor 允许编辑
$user_role = strtolower($_SESSION['role'] ?? ($_SESSION['user']['role'] ?? 'viewer'));
if ($user_role !== 'admin' && $user_role !== 'editor') {
    die("Permission denied.");
}

// 1. 拿到要编辑的排班 ID
$id = $_GET['id'] ?? null;
if (!$id) {
    die("Missing schedule ID.");
}

// 2. 查出当前排班的旧数据
$stmt = $pdo->prepare("SELECT * FROM schedules WHERE id = :id");
$stmt->execute(['id' => $id]);
$schedule = $stmt->fetch();

if (!$schedule) {
    die("Schedule not found.");
}

// 3. 拉取所有节目和主持人（供下拉菜单选择）
$programs = $pdo->query("SELECT id, title FROM programs")->fetchAll();
$hosts = $pdo->query("SELECT id, name FROM hosts")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DARKFM - Edit Schedule</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="text-light">
<div class="container mt-5" style="max-width: 600px;">
    <h2>Edit Schedule</h2>
    <hr class="border-secondary">

    <div class="card-custom p-2">
    <form id="editScheduleForm" action="action_edit_schedule.php" method="POST">
        <input type="hidden" name="id" value="<?= $schedule['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Program</label>
            <select name="program_id" class="form-select bg-secondary border-0" required>
                <?php foreach ($programs as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id'] == $schedule['program_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Host</label>
            <select name="host_id" class="form-select bg-secondary border-0" required>
                <?php foreach ($hosts as $h): ?>
                    <option value="<?= $h['id'] ?>" <?= $h['id'] == $schedule['host_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($h['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Air Date</label>
            <input type="date" name="air_date" class="form-control bg-secondary border-0" 
                   value="<?= $schedule['air_date'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Start Time</label>
            <input type="time" name="start_time" class="form-control bg-secondary border-0" 
                   value="<?= $schedule['start_time'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">End Time</label>
            <input type="time" name="end_time" class="form-control bg-secondary border-0" 
                   value="<?= $schedule['end_time'] ?>" required>
        </div>

        <button type="submit" class="btn btn-teal w-100" style="background-color: #008080; color: black;">Save Changes</button>
        <a href="manage-schedule.php" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
    </form>
    </div>
</div>
</body>
</html>