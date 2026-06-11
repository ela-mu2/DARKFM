<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: actions/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DARKFM - Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="includes/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container my-4 my-md-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h2 class="accent-color mb-0">Schedule</h2>
        <a href="manage-hosts-add.php" class="btn btn-accent w-md-auto">+ New Schedule</a>
    </div>

    <div class="card bg-grey p-2 p-md-3 shadow-sm border-0 mb-4">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 bg-transparent text-nowrap" style="min-width: 600px;">
                <thead>
                    <tr>
                        <th class="accent-color" style="width: 8%;">ID</th>
                        <th class="accent-color" style="width: 45%;">Title (Program / Host)</th>
                        <th class="accent-color" style="width: 32%;">Air Time</th>
                        <th class="accent-color" style="width: 15%; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody id="schedule-table-body">
                    <tr>
                        <td colspan="4" class="text-center text-muted-custom py-4">Loading schedules...</td>
                    </tr>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('schedule-table-body');

    fetch('api/api_get.php')
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

    function renderTable(schedules) {
        if (!schedules || schedules.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted-custom py-4">No schedules found.</td></tr>`;
            return;
        }

        let html = '';
        schedules.forEach(item => {
            html += `
            <tr id="schedule-row-${item.schedule_id}" class="align-middle">
              <td class="text-white-custom">${item.schedule_id}</td>
              <td class="text-wrap">
                <div class="fw-bold text-white-custom">${item.program_title || 'Unknown Program'}</div>
                <small class="text-muted-custom">Host: ${item.host_name || 'No Host'} | ${item.program_genre || 'N/A'}</small>
              </td>
              <td>
                <span class="badge d-inline-block text-wrap text-start" style="border: 1px solid var(--accent); color: var(--accent); background: transparent; padding: 6px 10px;">${item.air_date} ${item.start_time}</span>
              </td>
              <td class="text-center">
                <div class="d-flex justify-content-center gap-2">
                    <a href="manage-schedule-edit.php?id=${item.schedule_id}" class="btn btn-sm btn-outline-info">Edit</a>
                    <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${item.schedule_id}">Delete</button>
                </div>
              </td>
            </tr>
            `;
        });
        tableBody.innerHTML = html;
    }
});

$(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();
    const scheduleId = $(this).data('id');

    Swal.fire({
        title: 'Confirm deletion?', text: "Once deleted, this schedule information cannot be recovered!", icon: 'warning', showCancelButton: true,
        background: '#1e293b', color: '#fff', confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b',
        confirmButtonText: 'Confirm', cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new URLSearchParams();
            formData.append('id', scheduleId);

            fetch('api/api_delete_schedule.php', {
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
                    document.getElementById('schedule-row-' + scheduleId).remove();
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