<?php
require_once 'config/db.php';
require_once 'includes/header.php';

$user_role = strtolower($_SESSION['role'] ?? ($_SESSION['user']['role'] ?? 'viewer'));
if ($user_role !== 'admin' && $user_role !== 'editor') {
    die("Permission denied.");
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Missing schedule ID.");
}

$stmt = $pdo->prepare("SELECT * FROM schedules WHERE id = :id");
$stmt->execute(['id' => $id]);
$schedule = $stmt->fetch();

if (!$schedule) {
    die("Schedule not found.");
}

$programs = $pdo->query("SELECT id, title FROM programs")->fetchAll();
$hosts    = $pdo->query("SELECT id, name FROM hosts")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DARKFM - Schedule Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="includes/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container my-4 my-md-5" style="max-width: 550px;">
    <div class="card card-dark p-3 p-md-4 shadow-sm border-0">
        <h3 class="mb-1 text-teal">Edit Schedule</h3>
        <p class="text-secondary mb-4" style="font-size: 13px;">ID: <?= htmlspecialchars($schedule['id']) ?></p>

        <form id="editScheduleForm" action="actions/action_edit_schedule.php" method="POST">
            <input type="hidden" name="id" value="<?= $schedule['id'] ?>">

            <div class="mb-3">
                <label class="form-label text-secondary">Program <span class="text-danger">*</span></label>
                <select name="program_id" class="form-control" required>
                    <?php foreach ($programs as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $p['id'] == $schedule['program_id'] ? 'selected' : '' ?> class="text-dark">
                            <?= htmlspecialchars($p['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary">Host <span class="text-danger">*</span></label>
                <select name="host_id" class="form-control" required>
                    <?php foreach ($hosts as $h): ?>
                        <option value="<?= $h['id'] ?>" <?= $h['id'] == $schedule['host_id'] ? 'selected' : '' ?> class="text-dark">
                            <?= htmlspecialchars($h['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary">Air Date <span class="text-danger">*</span></label>
                <input type="date" name="air_date" class="form-control" value="<?= $schedule['air_date'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary">Start Time <span class="text-danger">*</span></label>
                <input type="time" name="start_time" class="form-control" value="<?= $schedule['start_time'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary">End Time <span class="text-danger">*</span></label>
                <input type="time" name="end_time" class="form-control" value="<?= $schedule['end_time'] ?>" required>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                <a href="manage-schedule.php" class="btn btn-secondary w-100 w-sm-50 py-2 border-0 text-white-custom order-2 order-sm-1" style="background: rgba(255, 255, 255, 0.1);">Cancel</a>
                <button type="submit" class="btn btn-accent w-100 w-sm-50 py-2 order-1 order-sm-2">Save Changes</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>