<?php
session_start();
// 未登录踢走
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
// 登录了但不是 Admin 也踢走
// if ($_SESSION['role'] !== 'Admin') {
//     echo "<script>alert('Permission denied! Admin only.'); window.location.href='dashboard.php';</script>";
//     exit;
// }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>DARKFM - Manage Schedule</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Manage Schedule</h1>
        <div class="text-end">
          <a href="manage-posts-add.php" class="btn btn-accent btn-sm text-white-custom"
            >Add New Post</a
          >
        </div>
      </div>
      <div class="card mb-2 p-4 card-custom">
        <table class="table text-white">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col" style="width: 40%;">Title (Program / Host)</th>
              <th scope="col">Air Time</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody id="schedule-table-body">
            <tr>
              <td colspan="4" class="text-muted text-center">Loading schedules...</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="text-center">
        <a href="dashboard.php" class="btn btn-outline-info btn-sm"
           style="border-color: var(--accent); color: var(--accent);"
          ><i class="bi bi-arrow-left"></i> Back to Dashboard</a
        >
      </div>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
      crossorigin="anonymous"
    ></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const tableBody = document.getElementById('schedule-table-body');

        fetch('api_get.php')
            .then(response => {
                if (!response.ok) throw new Error('HTTP error ' + response.status);
                return response.json();
            })
            .then(res => {
                if (res.status === 'success') {
                    renderTable(res.data);
                } else {
                    tableBody.innerHTML = `<tr><td colspan="4" class="text-danger text-center">API Error: ${res.message}</td></tr>`;
                }
            })
            .catch(err => {
                console.error('Fetch error:', err);
                tableBody.innerHTML = `<tr><td colspan="4" class="text-danger text-center">Connection Failed: ${err.message}</td></tr>`;
            });

        function renderTable(schedules) {
            if (!schedules || schedules.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="4" class="text-muted text-center">No schedules found.</td></tr>`;
                return;
            }

            let html = '';
            schedules.forEach(item => {
                html += `
                <tr>
                  <th scope="row">${item.schedule_id}</th>
                  <td>
                    <div class="fw-bold text-white-custom">${item.program_title || 'Unknown Program'}</div>
                    <small class="text-muted-custom">Host: ${item.host_name || 'No Host'} | ${item.program_genre || 'N/A'}</small>
                  </td>
                  <td>
                    <span class="badge" style="border: 1px solid var(--accent); color: var(--accent);">${item.air_date} ${item.start_time}</span>
                  </td>
                  <td class="text-end">
                    <div class="buttons">
                      <a href="manage-posts-edit.php?id=${item.schedule_id}" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <button class="btn btn-outline-danger btn-sm btn-delete" data-id="${item.schedule_id}">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                `;
            });
            tableBody.innerHTML = html;
        }
    });
    </script>
  </body>
</html>
