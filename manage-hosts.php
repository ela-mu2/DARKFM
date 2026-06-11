<?php
require('config/db.php');
require('includes/header.php');

try {
    $stmt = $pdo->query("SELECT * FROM hosts ORDER BY id DESC");
    $hosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hosts - DARKFM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="includes/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container my-4 my-md-5">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h2 class="accent-color mb-0">Manage Hosts</h2>
        <a href="manage-hosts-add.php" class="btn btn-accent w-md-auto">+ Add New host</a>
    </div>

    <div class="card bg-grey p-2 p-md-3 shadow-sm border-0 mb-4">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 bg-transparent text-nowrap" style="min-width: 600px;">
                <thead>
                    <tr>
                        <th class="accent-color" style="width: 8%;">ID</th>
                        <th class="accent-color" style="width: 20%;">Name</th>
                        <th class="accent-color" style="width: 25%;">Contact Email</th>
                        <th class="accent-color text-wrap">Bio</th>
                        <th class="accent-color" style="width: 15%; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($hosts)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted-custom py-4">No host data available; please add one first.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($hosts as $host): ?>
                            <tr id="host-row-<?= $host['id'] ?>" class="align-middle">
                                <td class="text-white-custom"><?= $host['id'] ?></td>
                                <td class="fw-bold text-white-custom text-wrap"><?= htmlspecialchars($host['name']) ?></td>
                                <td class="text-muted-custom text-wrap"><?= htmlspecialchars($host['contact_email'] ?: 'Not provided') ?></td>
                                <td class="text-muted-custom text-wrap" style="max-width: 300px;"><?= htmlspecialchars($host['bio'] ?: 'No bio available') ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="manage-hosts-edit.php?id=<?= $host['id'] ?>" class="btn btn-sm btn-outline-info">Edit</a>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteHost(<?= $host['id'] ?>)">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center">
        <a href="dashboard.php" class="btn btn-outline-info btn-sm w-sm-auto"
           style="border-color: var(--accent); color: var(--accent);">
          <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<script>
<?php if (isset($_GET['success'])): ?>
Swal.fire({ icon: 'success', title: 'Added successfully!', background: '#1e293b', color: '#fff', confirmButtonColor: '#14b8a6', timer: 2000, showConfirmButton: false });
<?php endif; ?>

function deleteHost(hostId) {
    Swal.fire({
        title: 'Confirm deletion?', text: "Once deleted, the host's information cannot be recovered!", icon: 'warning', showCancelButton: true,
        background: '#1e293b', color: '#fff', confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b',
        confirmButtonText: 'Confirm', cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', hostId);
            fetch('api/api_delete_host.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('host-row-' + hostId).remove();
                    Swal.fire({ icon: 'success', title: 'Deleted', background: '#1e293b', color: '#fff', confirmButtonColor: '#14b8a6', timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'Deletion failed', text: data.message, background: '#1e293b', color: '#fff' });
                }
            }).catch(err => console.error("Error:", err));
        }
    });
}

<?php if (isset($_GET['updated'])): ?>
Swal.fire({ icon: 'success', title: 'Editing successful!', background: '#1e293b', color: '#fff', confirmButtonColor: '#14b8a6', timer: 2000, showConfirmButton: false });
<?php endif; ?>
</script>
</body>
</html>