<?php
// manage-posts-add.php
require_once 'header.php'; // 引入后自动拉起 Session 与权限拦截
require_once 'db.php';

// 允许 Admin 和 Editor 角色进入
// 已修复大小写问题
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
<html>
<head>
  <title>DARKFM - Add New Schedule</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container mx-auto my-5" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h1 class="h1">Add New Schedule</h1>
    </div>

    <div class="card mb-2 p-4 card-custom">
      <?php if (!empty($error)): ?>
          <div class="alert alert-danger bg-danger text-white border-0 mb-3" style="--bs-bg-opacity: .2;"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
          <div class="alert alert-success bg-success text-white border-0 mb-3" style="--bs-bg-opacity: .2;"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <form method="POST" action="manage-posts-add.php">
        <div class="mb-3">
          <label for="program_id" class="form-label">Select Program</label>
          <select class="form-control text-white" id="program_id" name="program_id" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1);" required>
            <option value="" class="text-dark">-- Choose Program --</option>
            <?php foreach ($programs as $p): ?>
                <option value="<?= $p['id'] ?>" class="text-dark"><?= htmlspecialchars($p['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="host_id" class="form-label">Select Host</label>
          <select class="form-control text-white" id="host_id" name="host_id" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1);" required>
            <option value="" class="text-dark">-- Choose Host --</option>
            <?php foreach ($hosts as $h): ?>
                <option value="<?= $h['id'] ?>" class="text-dark"><?= htmlspecialchars($h['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="air_date" class="form-label">Air Date</label>
          <input type="date" class="form-control text-white" id="air_date" name="air_date" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1);" required/>
        </div>

        <div class="row">
          <div class="col-6 mb-3">
            <label for="start_time" class="form-label">Start Time</label>
            <input type="time" class="form-control text-white" id="start_time" name="start_time" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1);" required/>
          </div>
          <div class="col-6 mb-3">
            <label for="end_time" class="form-label">End Time</label>
            <input type="time" class="form-control text-white" id="end_time" name="end_time" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1);" required/>
          </div>
        </div>

        <div class="text-end mt-3">
          <button type="submit" class="btn btn-accent text-white-custom" style="background-color: var(--accent);">Add Schedule</button>
        </div>
      </form>
    </div>

    <div class="text-center">
      <a href="manage-schedule.php" class="btn btn-outline-info btn-sm mt-2 text-white-custom" style="border-color: var(--accent); color: var(--accent);"><i class="bi bi-arrow-left"></i> Back to Schedule</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>