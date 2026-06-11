<?php
require_once 'config/db.php';
require_once 'includes/header.php';

if ($user_role !== 'admin' && $user_role !== 'editor') {
    echo "<script>alert('Permission denied! Authorized personnel only.'); window.location.href='dashboard.php';</script>";
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $program_id = $_POST['program_id'] ?? '';
    $host_id    = $_POST['host_id'] ?? '';
    $air_date   = $_POST['air_date'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time   = $_POST['end_time'] ?? '';

    if (!empty($program_id) && !empty($host_id) && !empty($air_date) && !empty($start_time) && !empty($end_time)) {
        try {
            $sql = "INSERT INTO schedules (program_id, host_id, air_date, start_time, end_time) 
                    VALUES (:program_id, :host_id, :air_date, :start_time, :end_time)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'program_id' => $program_id,
                'host_id'    => $host_id,
                'air_date'   => $air_date,
                'start_time' => $start_time,
                'end_time'   => $end_time
            ]);
            $success = "New schedule added successfully!";
        } catch (\PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "All fields are required.";
    }
}

try {
    $programs = $pdo->query("SELECT id, title FROM programs ORDER BY title ASC")->fetchAll();
    $hosts    = $pdo->query("SELECT id, name FROM hosts ORDER BY name ASC")->fetchAll();
} catch (\PDOException $e) {
    die("Failed to fetch metadata: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DARKFM - Add New Schedule</title>
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
        <h3 class="mb-4 text-teal">Add New Schedule</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger bg-dark text-white border-secondary mb-3"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-dark bg-success text-teal border-secondary mb-3"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form id="addScheduleForm" method="POST">
            <div class="mb-3">
                <label class="form-label text-secondary">Select Program <span class="text-danger">*</span></label>
                <select class="form-control" name="program_id" required>
                    <option value="" class="text-dark">-- Choose Program --</option>
                    <?php foreach ($programs as $p): ?>
                        <option value="<?= $p['id'] ?>" class="text-dark"><?= htmlspecialchars($p['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary">Select Host <span class="text-danger">*</span></label>
                <select class="form-control" name="host_id" required>
                    <option value="" class="text-dark">-- Choose Host --</option>
                    <?php foreach ($hosts as $h): ?>
                        <option value="<?= $h['id'] ?>" class="text-dark"><?= htmlspecialchars($h['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary">Air Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="air_date" required/>
            </div>

            <div class="row">
                <div class="col-12 col-sm-6 mb-3">
                    <label class="form-label text-secondary">Start Time <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" name="start_time" required/>
                </div>
                <div class="col-12 col-sm-6 mb-3">
                    <label class="form-label text-secondary">End Time <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" name="end_time" required/>
                </div>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                <a href="manage-schedule.php" class="btn btn-secondary w-100 w-sm-50 py-2 border-0 text-white-custom order-2 order-sm-1" style="background: rgba(255, 255, 255, 0.1);">Cancel</a>
                <button type="submit" class="btn btn-accent w-100 w-sm-50 py-2 order-1 order-sm-2">Add Schedule</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>