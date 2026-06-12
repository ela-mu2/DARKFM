<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DARKFM - Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="includes/assets/css/styles.css">
</head>
<body>

<div class="container my-4 my-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="accent-color mb-0">Schedule</h2>
    </div>

    <div class="card bg-grey p-2 p-md-3 shadow-sm border-0 mb-4">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 bg-transparent text-nowrap" style="min-width: 600px;">
                <thead>
                    <tr>
                        <th class="accent-color" style="width: 10%;">ID</th>
                        <th class="accent-color" style="width: 50%;">Title (Program / Host)</th>
                        <th class="accent-color" style="width: 40%;">Air Time</th>
                    </tr>
                </thead>
                <tbody id="schedule-table-body">
                    <tr>
                        <td colspan="3" class="text-center text-muted-custom py-4">Loading schedules...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center">
        <a href="index.php" class="btn btn-outline-info btn-sm w-sm-auto" style="border-color: var(--accent); color: var(--accent);">
            <i class="bi bi-arrow-left"></i> Back to Homepage
        </a>
    </div>
</div>

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
                tableBody.innerHTML = `<tr><td colspan="3" class="text-danger text-center py-4">API Error: ${res.message}</td></tr>`;
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            tableBody.innerHTML = `<tr><td colspan="3" class="text-danger text-center py-4">Connection Failed: ${err.message}</td></tr>`;
        });

    function renderTable(schedules) {
        if (!schedules || schedules.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="3" class="text-center text-muted-custom py-4">No schedules found.</td></tr>`;
            return;
        }

        let html = '';
        schedules.forEach(item => {
            html += `
            <tr class="align-middle">
              <td class="text-white-custom">${item.schedule_id}</td>
              <td class="text-wrap">
                <div class="fw-bold text-white-custom">${item.program_title || 'Unknown Program'}</div>
                <small class="text-muted-custom">Host: ${item.host_name || 'No Host'} | ${item.program_genre || 'N/A'}</small>
              </td>
              <td>
                <span class="badge d-inline-block text-wrap text-start" style="border: 1px solid var(--accent); color: var(--accent); background: transparent; padding: 6px 10px;">${item.air_date} ${item.start_time}</span>
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