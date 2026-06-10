<?php
// login.php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        try {
            // 查询用户
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            // 调试用：如果数据库存的是明文，用 $password === $user['password']
            // 生产推荐：password_verify($password, $user['password'])
            // if ($user && password_verify($password, $user['password'])) {
            if ($user && $password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // Admin / Editor

                header("Location: dashboard.php");
                exit;
            } else {
                $error = 'Invalid email or password!';
            }
        } catch (\PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>DARKFM - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <div class="container my-5 mx-auto" style="max-width: 500px;">
      <h1 class="h1 mb-4 text-center">Login</h1>

      <div class="card p-4" style="background: rgba(255,255,255,0.02)">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger bg-danger text-white border-0 mb-3" style="--bs-bg-opacity: .2;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control text-white" id="email" name="email" style="background: rgba(255,255,255,0.02)" required/>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control text-white" id="password" name="password" style="background: rgba(255,255,255,0.02)" required/>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-outline-info mt-2 text-white-custom" style="border-color: var(--accent); color: var(--accent) ;">
              Login
            </button>
          </div>
        </form>
      </div>

      <div class="d-flex justify-content-between align-items-center gap-3 mx-auto pt-3 pb-3">
        <a href="index.html" class="text-decoration-none small"><i class="bi bi-arrow-left-circle"></i> Go back</a>
        <a href="signup.php" class="text-decoration-none small">Don't have an account? Sign up here <i class="bi bi-arrow-right-circle"></i></a>
      </div>
    </div>
  </body>
</html>