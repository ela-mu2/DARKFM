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
    <title>DARKFM - Manage Programs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="includes/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container my-4 my-md-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h2 class="accent-color mb-0">Manage Programs</h2>
        <?php if (in_array($role, ['admin', 'editor'])): ?>
            <a href="manage-programs-add.php" class="btn btn-accent w-md-auto">+ New Program</a>
        <?php endif; ?>
    </div>

    <div class="card bg-grey p-2 p-md-3 shadow-sm border-0 mb-4">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 bg-transparent text-nowrap" style="min-width: 600px;">
                <thead>
                    <tr>
                        <th class="accent-color" style="width: 10%;">ID</th>
                        <th class="accent-color" style="width: 50%;">Program Title</th>
                        <th class="accent-color" style="width: 25%;">Genre</th>
                        <th class="accent-color" style="width: 15%; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="program-table-body">
                    <tr>
                        <td colspan="4" class="text-center text-muted-custom py-4">Loading programs...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center">
        <a href="dashboard.php" class="btn btn-outline-info btn-sm w-sm-auto" style="border-color: var(--accent); color: var(--accent);">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('program-table-body');
    const userRole = <?php echo json_encode($role); ?>;

    // 假设你的获取节目 API 路径为 api/api_get_programs.php
    fetch('api/api_get_programs.php')
        .then(response => {
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            return response.json();
        })
        .then(res => {
            if (res.status === 'success') {
                renderTable(res.data);
            } else {
                tableBody.innerHTML = `<tr><td colspan="4" class="text-danger text-center py-4">API Error: ${res.message}</td></tr>`;
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            tableBody.innerHTML = `<tr><td colspan="4" class="text-danger text-center py-4">Connection Failed: ${err.message}</td></tr>`;
        });

    function renderTable(programs) {
        if (!programs || programs.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted-custom py-4">No programs found.</td></tr>`;
            return;
        }

        let html = '';
        programs.forEach(item => {
            let actionsHtml = `<span class="text-muted-custom">-</span>`;
            
            // 判定操作权限：admin 和 editor 可编辑，仅 admin 可删除，viewer 只能看线
            if (userRole === 'admin') {
                actionsHtml = `
                    <div class="d-flex justify-content-center gap-2">
                        <a href="manage-programs-edit.php?id=${item.program_id}" class="btn btn-sm btn-outline-info">Edit</a>
                        <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${item.program_id}">Delete</button>
                    </div>
                `;
            } else if (userRole === 'editor') {
                actionsHtml = `
                    <div class="d-flex justify-content-center gap-2">
                        <a href="manage-programs-edit.php?id=${item.program_id}" class="btn btn-sm btn-outline-info">Edit</a>
                    </div>
                `;
            }

            html += `
            <tr id="program-row-${item.program_id}" class="align-middle">
              <td class="text-white-custom">${item.program_id}</td>
              <td class="text-wrap">
                <div class="fw-bold text-white-custom">${item.program_title || 'Unknown Program'}</div>
              </td>
              <td class="text-white-custom">
                ${item.program_genre || 'N/A'}
              </td>
              <td class="text-center">
                ${actionsHtml}
              </td>
            </tr>
            `;
        });
        tableBody.innerHTML = html;
    }
});

// SweetAlert2 删除逻辑
$(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();
    const programId = $(this).data('id');

    Swal.fire({
        title: 'Confirm deletion?', 
        text: "Once deleted, this program information cannot be recovered!", 
        icon: 'warning', 
        showCancelButton: true,
        background: '#1e293b', 
        color: '#fff', 
        confirmButtonColor: '#ef4444', 
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Confirm', 
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new URLSearchParams();
            formData.append('id', programId);

            // 假设你的删除节目 API 路径为 api/api_delete_program.php
            fetch('api/api_delete_program.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Deleted', background: '#1e293b', color: '#fff', confirmButtonColor: '#14b8a6', timer: 1500, showConfirmButton: false });
                    document.getElementById('program-row-' + programId).remove();
                } else {
                    Swal.fire({ icon: 'error', title: 'Deletion failed', text: data.message, background: '#1e293b', color: '#fff' });
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                Swal.fire({ icon: 'error', title: 'System error', text: 'Connection failed.', background: '#1e293b', color: '#fff' });
            });
        }
    });
});
</script>
</body>
</html>