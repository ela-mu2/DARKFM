<?php
session_start();
// 拦截未登录用户或游客，直接踢回主页
if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'guest') {
    header("Location: index.php");
    exit;
}
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DARKFM - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="includes/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    <div class="container mx-auto my-5" style="max-width: 800px;">
      <h1 class="h1 mb-4 text-center text-white-custom">Dashboard</h1>
      <div class="row g-3">
        
      <?php if ($role === 'admin' || $role === 'editor'): ?>
      <div class="col-lg-4">
        <div class="card card-custom h-100">
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title text-center text-white-custom">
              <div class="mb-2"><i class="bi bi-calendar-week accent-color" style="font-size: 3.5rem;"></i></div>
              Schedule
            </h5>
            <div class="text-center mt-3">
              <a href="manage-schedule.php" class="btn btn-accent btn-sm text-white-custom">Access</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card card-custom h-100">
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title text-center text-white-custom">
              <div class="mb-2"><i class="bi bi-broadcast-pin accent-color" style="font-size: 3.5rem;"></i></div>
              Stations
            </h5>
            <div class="text-center mt-3">
              <a href="manage-programs.php" class="btn btn-accent btn-sm text-white-custom">Access</a>
            </div>
          </div>
        </div>
      </div>
        
      <div class="col-lg-4">
        <div class="card card-custom h-100">
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title text-center text-white-custom">
              <div class="mb-2"><i class="bi bi-collection-play accent-color" style="font-size: 3.5rem;"></i></div>
              Programs
            </h5>
            <div class="text-center mt-3">
              <a href="manage-programs.php" class="btn btn-accent btn-sm text-white-custom">Access</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-2"></div>
      
      <div class="col-lg-4">
        <div class="card card-custom h-100">
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title text-center text-white-custom">
              <div class="mb-2"><i class="bi bi-mic accent-color" style="font-size: 3.5rem;"></i></div>
              Hosts
            </h5>
            <div class="text-center mt-3">
              <a href="manage-hosts.php" class="btn btn-accent btn-sm text-white-custom">Access</a>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-4">
        <div class="card card-custom h-100">
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title text-center text-white-custom">
              <div class="mb-2"><i class="bi bi-person-circle accent-color" style="font-size: 3.5rem;"></i></div>
              Users
            </h5>
            <div class="text-center mt-3">
              <a href="manage-users.php" class="btn btn-accent btn-sm text-white-custom">Access</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-2"></div>
      <?php endif; ?>

      <?php if ($role === 'viewer'): ?>
      <div class="col-lg-6">
        <div class="card card-custom h-100">
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title text-center text-white-custom">
              <div class="mb-2"><i class="bi bi-calendar-week accent-color" style="font-size: 3.5rem;"></i></div>
              Schedule
            </h5>
            <div class="text-center mt-3">
              <a href="manage-schedule.php" class="btn btn-accent btn-sm text-white-custom">Access</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-custom h-100">
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title text-center text-white-custom">
              <div class="mb-2"><i class="bi bi-broadcast-pin accent-color" style="font-size: 3.5rem;"></i></div>
              Stations
            </h5>
            <div class="text-center mt-3">
              <a href="manage-programs.php" class="btn btn-accent btn-sm text-white-custom">Access</a>
            </div>
          </div>
        </div>
      </div>
        
      <div class="col-lg-6">
        <div class="card card-custom h-100">
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title text-center text-white-custom">
              <div class="mb-2"><i class="bi bi-collection-play accent-color" style="font-size: 3.5rem;"></i></div>
              Programs
            </h5>
            <div class="text-center mt-3">
              <a href="manage-programs.php" class="btn btn-accent btn-sm text-white-custom">Access</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-custom h-100">
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title text-center text-white-custom">
              <div class="mb-2"><i class="bi bi-mic accent-color" style="font-size: 3.5rem;"></i></div>
              Hosts
            </h5>
            <div class="text-center mt-3">
              <a href="manage-hosts.php" class="btn btn-accent btn-sm text-white-custom">Access</a>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>
      
      </div>
      
      <div class="mt-5 text-center">
        <a href="index.php" class="btn btn-outline-info btn-sm"
          style="border-color: var(--accent); color: var(--accent); background: transparent;">
          <i class="bi bi-arrow-left"></i> Back to Homepage
        </a>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>