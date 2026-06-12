<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DARKFM - Manage Stations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="includes/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="container my-4 my-md-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <h2 class="accent-color mb-0">Manage Stations</h2>
        <a href="manage-stations-add.php" class="btn btn-accent w-md-auto">+ New Station</a>
    </div>

    <div class="row g-4" id="stations-container">
        <div class="col-12 text-center text-muted-custom py-5">
            Loading stations...
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="dashboard.php" class="btn btn-outline-info btn-sm" style="border-color: var(--accent); color: var(--accent); background: transparent;">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('stations-container');

    fetch('api/api_get_stations.php')
        .then(response => {
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            return response.json();
        })
        .then(res => {
            if (res.status === 'success') {
                renderStations(res.data);
            } else {
                container.innerHTML = `<div class="col-12 text-danger text-center py-4">API Error: ${res.message}</div>`;
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            container.innerHTML = `<div class="col-12 text-danger text-center py-4">Connection Failed: ${err.message}</div>`;
        });

    function renderStations(stations) {
        if (!stations || stations.length === 0) {
            container.innerHTML = `<div class="col-12 text-center text-muted-custom py-4">No stations found.</div>`;
            return;
        }

        let html = '';
        stations.forEach(item => {
            const liveBadge = parseInt(item.is_live) === 1 
                ? `<span class="badge bg-danger animate-pulse" style="font-size: 0.75rem;">LIVE</span>` 
                : '';

            const listeners = item.listener_count ? parseInt(item.listener_count).toLocaleString() : '0';

            html += `
            <div class="col-md-6 col-lg-4" id="station-card-${item.id}">
                <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center me-3" style="background: rgba(13, 148, 136, 0.1); width: 52px; height: 52px;">
                                    <i class="bi bi-broadcast accent-color fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-white-custom mb-0 text-break">${item.name || 'Unknown Station'}</h5>
                                    <small class="text-muted-custom">${item.frequency || 'N/A'}</small>
                                </div>
                            </div>
                            ${liveBadge}
                        </div>
                        <p class="text-muted-custom small mb-3 description-text">${item.description || 'No description available.'}</p>
                    </div>
                    
                    <div>
                        <div class="d-flex align-items-center justify-content-between text-muted-custom small">
                            <span><i class="bi bi-people me-1"></i> ${listeners} listeners</span>
                            <span class="text-secondary">#ID: ${item.id}</span>
                        </div>
                        <div class="d-flex gap-2 mt-3 pt-3 border-top border-secondary">
                            <a href="manage-stations-edit.php?id=${item.id}" class="btn btn-sm btn-outline-info flex-grow-1">Edit</a>
                            <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${item.id}">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            `;
        });
        container.innerHTML = html;
    }
});

// SweetAlert2 删除逻辑
$(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();
    const stationId = $(this).data('id');

    Swal.fire({
        title: 'Confirm deletion?',
        text: "Once deleted, this station cannot be recovered!",
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
            formData.append('id', stationId);

            fetch('api/api_delete_station.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response error');
                return response.json();
            })
            .then(data => {
                if (data.status === 'success' || data.success) {
                    Swal.fire({ icon: 'success', title: 'Deleted', background: '#1e293b', color: '#fff', confirmButtonColor: '#14b8a6', timer: 1500, showConfirmButton: false });
                    document.getElementById('station-card-' + stationId).remove();
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