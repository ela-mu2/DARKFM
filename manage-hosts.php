<?php
require('config/db.php');
require('includes/header.php');

try {
    // 确保使用 $pdo
    $stmt = $pdo->query("SELECT * FROM hosts ORDER BY id DESC");
    $hosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("数据库查询失败: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Hosts - DARKFM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="includes/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="accent-color">主持人管理面板</h2>
        <a href="manage-hosts-add.php" class="btn btn-accent">+ 新增主持人</a>
    </div>

    <div class="card bg-grey p-3 shadow-sm border-0">
        <table class="table table-dark table-hover mb-0 bg-transparent">
            <thead>
                <tr>
                    <th class="accent-color" style="width: 8%;">ID</th>
                    <th class="accent-color" style="width: 20%;">主持人名字</th>
                    <th class="accent-color" style="width: 25%;">联系邮箱</th>
                    <th class="accent-color">个人简介</th>
                    <th class="accent-color" style="width: 15%; text-align: center;">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($hosts)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted-custom py-4">暂无主持人数据，请先新增。</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($hosts as $host): ?>
                        <tr id="host-row-<?= $host['id'] ?>" class="align-middle">
                            <td class="text-white-custom"><?= $host['id'] ?></td>
                            <td class="fw-bold text-white-custom"><?= htmlspecialchars($host['name']) ?></td>
                            <td class="text-muted-custom"><?= htmlspecialchars($host['contact_email'] ?: '未提供') ?></td>
                            <td class="text-muted-custom"><?= htmlspecialchars($host['bio'] ?: '暂无简介') ?></td>
                            <td class="text-center">
                                <a href="manage-hosts-edit.php?id=<?= $host['id'] ?>" class="btn btn-sm btn-outline-info me-1">编辑</a>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteHost(<?= $host['id'] ?>)">删除</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center">
        <a href="dashboard.php" class="btn btn-outline-info btn-sm"
           style="border-color: var(--accent); color: var(--accent);">
          <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<script>
<?php if (isset($_GET['success'])): ?>
Swal.fire({ icon: 'success', title: '新增成功！', background: '#1e293b', color: '#fff', confirmButtonColor: '#14b8a6', timer: 2000, showConfirmButton: false });
<?php endif; ?>

function deleteHost(hostId) {
    Swal.fire({
        title: '确定删除？', text: "删除后，该主持人的信息将无法恢复！", icon: 'warning', showCancelButton: true,
        background: '#1e293b', color: '#fff', confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b',
        confirmButtonText: '确定删除', cancelButtonText: '取消'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', hostId);
            fetch('api/api_delete_host.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('host-row-' + hostId).remove();
                    Swal.fire({ icon: 'success', title: '已删除', background: '#1e293b', color: '#fff', confirmButtonColor: '#14b8a6', timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: '删除失败', text: data.message, background: '#1e293b', color: '#fff' });
                }
            }).catch(err => console.error("Error:", err));
        }
    });
}

<?php if (isset($_GET['updated'])): ?>
Swal.fire({ icon: 'success', title: '修改成功！', background: '#1e293b', color: '#fff', confirmButtonColor: '#14b8a6', timer: 2000, showConfirmButton: false });
<?php endif; ?>
</script>
</body>
</html>