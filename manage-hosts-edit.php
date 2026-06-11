<?php
// 1. 必须先引入数据库连接，否则下面第 14 行的 $pdo 无法使用
require('config/db.php');

// 2. 再引入公共 header
require('includes/header.php');

// 没有带 id 来就踢回列表
if (!isset($_GET['id'])) {
    header('Location: manage-hosts.php');
    exit;
}

$id = intval($_GET['id']);

// 读取这位主持人的现有数据
try {
    // 确保这里已经改成了 $pdo
    $stmt = $pdo->prepare("SELECT * FROM hosts WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $host = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("数据库错误: " . $e->getMessage());
}

// 找不到就踢回列表
if (!$host) {
    header('Location: manage-hosts.php');
    exit;
}

$message = '';

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name          = trim($_POST['name']);
    $bio           = trim($_POST['bio']);
    $contact_email = trim($_POST['contact_email']);

    if (!empty($name)) {
        try {
            $sql = "UPDATE hosts SET name = :name, bio = :bio, contact_email = :contact_email WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name'          => $name,
                ':bio'           => $bio,
                ':contact_email' => $contact_email,
                ':id'            => $id
            ]);
            header('Location: manage-hosts.php?updated=1');
            exit;
        } catch (PDOException $e) {
            $message = '数据库错误: ' . $e->getMessage();
        }
    } else {
        $message = '主持人名字不能为空！';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Host - DARKFM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="includes/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5" style="max-width: 550px;">
    <div class="card card-dark p-4 shadow-sm">
        <h3 class="mb-1 text-teal">编辑主持人</h3>
        <p class="text-secondary mb-4" style="font-size: 13px;">ID: <?= $host['id'] ?></p>

        <?php if (!empty($message)): ?>
            <div class="alert alert-danger bg-dark text-white border-secondary mb-3">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label text-secondary">主持人艺名 / Name <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="<?= htmlspecialchars($host['name']) ?>"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary">联系邮箱 / Contact Email</label>
                <input
                    type="email"
                    name="contact_email"
                    class="form-control"
                    value="<?= htmlspecialchars($host['contact_email'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary">个人简介 / Bio</label>
                <textarea
                    name="bio"
                    class="form-control"
                    rows="4"><?= htmlspecialchars($host['bio'] ?? '') ?></textarea>
            </div>

           <div class="d-flex gap-2 mt-4">
                <a href="manage-hosts.php" class="btn btn-secondary w-50 py-2 border-0 text-white-custom" style="background: rgba(255, 255, 255, 0.1);">取消</a>
                <button type="submit" class="btn btn-accent w-50 py-2">保存修改</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>