<?php
// actions/login.php
session_start();
require_once '../config/db.php';

$error = '';
$login_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];

                $login_success = true;
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DARKFM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="../includes/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #0f172a;">

    <?php if (!$login_success): ?>
    <div class="container mx-auto my-5" style="max-width: 450px;">
      <div class="card card-custom p-4 shadow-sm">
        <h2 class="text-center text-white-custom mb-4">
            <i class="bi bi-shield-lock accent-color me-2"></i>Login to DARKFM
        </h2>
        
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label text-white-custom">Email Address</label>
                <input type="email" name="email" id="email" class="form-control bg-dark text-white border-secondary" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label text-white-custom">Password</label>
                <input type="password" name="password" id="password" class="form-control bg-dark text-white border-secondary" required>
            </div>
            <button type="submit" class="btn btn-accent w-100 text-white-custom mb-3">Sign In</button>
        </form>
        
        <div class="text-center">
          <p class="text-muted-custom small mb-0">
              Don't have an account? <a href="signup.php" class="accent-color text-decoration-none">Sign Up</a>
          </p>
        </div>
      </div>

      <div class="text-center mt-3">
          <a href="../index.php" class="btn btn-outline-info btn-sm w-sm-auto"
            style="border-color: var(--accent); color: var(--accent);">
              <i class="bi bi-arrow-left"></i> Back to Homepage
          </a>
      </div>
    </div>
    <?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // 登录成功提示
    <?php if ($login_success): ?>
        Swal.fire({
            title: 'Welcome Back!',
            text: 'Login successful, entering the lobby...',
            icon: 'success',
            background: '#1e293b',
            color: '#fff',
            confirmButtonColor: '#14b8a6',
            timer: 2500,
            showConfirmButton: false,
            willClose: () => {
                window.location.href = '../index.php';
            }
        });
    <?php endif; ?>

    // 登录失败提示
    <?php if (!empty($error)): ?>
        Swal.fire({
            title: 'Login Failed',
            text: '<?php echo addslashes($error); ?>',
            icon: 'error',
            background: '#1e293b',
            color: '#fff',
            confirmButtonColor: '#ef4444'
        });
    <?php endif; ?>
});
</script>

</body>
</html>