<?php
// signup.php
require_once 'db.php';

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? ''); // 严格对齐 users.username 字段
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $message = 'All fields are required.';
        $status  = 'danger';
    } elseif ($password !== $confirm) {
        $message = 'Passwords do not match.';
        $status  = 'danger';
    } else {
        try {
            // 1. 检查 Email 是否已被注册
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $message = 'Email already exists.';
                $status  = 'danger';
            } else {
                // 2. 加密密码并写入数据库
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                
                // 默认角色设为 'Admin' 方便测试后台，后续可以改成 'Viewer'
                $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, 'Admin')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'username' => $username,
                    'email'    => $email,
                    'password' => $hashedPassword
                ]);

                $message = 'Registration successful! Go to Login.';
                $status  = 'success';
            }
        } catch (\PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
            $status  = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>DARKFM - Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <div class="container my-5 mx-auto" style="max-width: 500px;">
      <h1 class="h1 mb-4 text-center">Sign Up</h1>

      <div class="card p-4" style="background: rgba(255,255,255,0.02)">
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $status ?> border-0 mb-3" style="--bs-bg-opacity: .2; color: #fff;"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="signup.php">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control text-white" id="username" name="username" style="background: rgba(255,255,255,0.02)" required/>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control text-white" id="email" name="email" style="background: rgba(255,255,255,0.02)" required/>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control text-white" id="password" name="password" style="background: rgba(255,255,255,0.02)" required/>
          </div>
          <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control text-white" id="confirm_password" name="confirm_password" style="background: rgba(255,255,255,0.02)" required/>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-outline-info mt-2 text-white-custom" style="border-color: var(--accent); color: var(--accent) !important;">
              Sign Up
            </button>
          </div>
        </form>
      </div>

      <div class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3">
        <a href="index.php" class="text-decoration-none small"><i class="bi bi-arrow-left-circle"></i> Go back</a>
        <a href="login.php" class="text-decoration-none small">Already have an account? Login here <i class="bi bi-arrow-right-circle"></i></a>
      </div>
    </div>
  </body>
</html>