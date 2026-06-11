<?php
session_start();

// 如果用户已经确认要退出（通过 URL 参数 ?confirm=true 触发）
if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }
    session_destroy();
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging out... - DARKFM</title>
    <link rel="stylesheet" href="../includes/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #0f172a;"> <script>
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        title: 'See you next time!',
        text: 'You are logging out of DARKFM...',
        icon: 'success',
        background: '#1e293b',
        color: '#fff',
        confirmButtonColor: '#14b8a6', // 使用你的 Teal 青色
        timer: 2500,
        showConfirmButton: false,
        willClose: () => {
            // 弹窗倒计时结束后，刷新并携带 confirm 参数安全销毁 Session
            window.location.href = 'logout.php?confirm=true';
        }
    });
});
</script>

</body>
</html>