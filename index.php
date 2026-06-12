<?php
session_start();
// 获取当前登录状态与角色，默认未登录时为 guest (游客)
$is_logged_in = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? 'guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DARKFM - Radio Station</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="includes/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-white-custom" href="#">
                <i class="bi bi-radio accent-color me-2"></i>DARK<span class="accent-color">FM</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav Bar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center"> <li class="nav-item"><a class="nav-link active text-white-custom" href="discover.php">Discover</a></li>
                    
                    <?php if ($role === 'user' || $role === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link text-muted-custom" href="#">Rankings</a></li>
                        <li class="nav-item"><a class="nav-link text-muted-custom" href="#">Blogs</a></li>
                        <li class="nav-item"><a class="nav-link text-muted-custom" href="dashboard.php">Dashboard</a></li>
                    <?php endif; ?>

                    <?php if ($is_logged_in): ?>
                        <?php
                        // 根据不同角色定义颜色类名（可根据 CSS 自行调整或使用 Bootstrap 内置颜色）
                        $role_color_class = 'text-muted-custom'; // 默认颜色
                        if ($role === 'admin') {
                            $role_color_class = 'text-danger fw-bold'; // 管理员红色
                        } elseif ($role === 'editor') {
                            $role_color_class = 'text-warning fw-bold'; // 编辑黄色
                        } elseif ($role === 'user') {
                            $role_color_class = 'text-info fw-bold'; // 普通用户蓝色
                        }
                        ?>
                        <li class="nav-item px-lg-2 py-2 py-lg-0">
                            <span class="<?php echo $role_color_class; ?>">
                                <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($role); ?>
                            </span>
                        </li>
                    <?php endif; ?>

                    <?php if ($is_logged_in): ?>
                        <li class="nav-item"><a class="nav-link accent-color-red" href="actions/logout.php">Logout</a></li> 
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link accent-color" href="actions/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Path -->
    <div class="container my-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                    <!-- 新增随机数 -->
                    <div>
                        <span class="badge bg-danger mb-3 px-3 py-2">LIVE Streaming</span>
                        <h2 class="fw-bold text-white-custom mb-1">Night Radio - City Neon Lights and Electronic Music</h2>
                        <p class="text-muted-custom">Current audience: <span id="audience-count">Loading...</span> people</p>
                    </div>
                    <div class="row align-items-center my-4">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div class="ratio ratio-1x1 bg-secondary rounded-3 d-flex align-items-center justify-content-center"
                                 style="max-width: 200px; margin: 0 auto; background: linear-gradient(135deg, #1E293B, #0D9488);">
                                <i class="bi bi-music-note-beamed display-1 text-white-custom opacity-50"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-white-custom mb-2">Midnight Synthwave Mix</h4>
                            <p class="accent-color mb-3">DJ Shadow / Guest Mix</p>
                            <div class="progress bg-dark mb-2" style="height: 6px;">
                                <div class="progress-bar progress-bar-accent" style="width: 45%"></div>
                            </div>
                            <div class="d-flex justify-content-between small text-muted-custom">
                                <span>02:15</span>
                                <span>05:00</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <button class="btn text-muted-custom fs-4"><i class="bi bi-shuffle"></i></button>
                        <button class="btn text-white-custom fs-3"><i class="bi bi-skip-start-fill"></i></button>
                        <button class="btn btn-accent rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-play-fill fs-3"></i>
                        </button>
                        <button class="btn text-white-custom fs-3"><i class="bi bi-skip-end-fill"></i></button>
                        <button class="btn text-muted-custom fs-4"><i class="bi bi-volume-up-fill"></i></button>
                    </div>
                </div>
            </div>

            <!-- Next Program Path -->
            <div class="col-lg-4">
                <div class="card-custom p-4 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="fw-bold text-white-custom mb-4">Next program</h4>
                        <div class="d-flex flex-column gap-3" id="next-programs">
                            <div class="text-center text-muted-custom py-3">Loading...</div>
                        </div>
                    </div>
                    
                    <?php 
                    $schedule_url = ($role === 'guest' || $role === 'viewer') ? 'schedule.php' : 'manage-schedule.php'; 
                    ?>
                    <a href="<?php echo $schedule_url; ?>" class="btn btn-outline-info w-100 mt-4 text-white-custom"
                    style="border-color: var(--accent); color: var(--accent); background: transparent;">
                        View the complete program schedule
                    </a>
                </div>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', () => {
                const nextProgramsContainer = document.getElementById('next-programs');

                fetch('api/api_get.php')
                    .then(response => response.json())
                    .then(res => {
                        if (res.status === 'success' && res.data && res.data.length > 0) {
                            const topFive = res.data.slice(0, 5);
                            let html = '';
                            
                            topFive.forEach((item, index) => {
                                const timeColorClass = index === 0 ? 'accent-color' : 'text-muted-custom';
                                const hoverClass = index === 0 ? 'hover-bg' : '';
                                
                                // 核心修改：处理时间格式 hh:mm:ss -> hh:mm
                                const fullTime = item.start_time || '00:00:00';
                                const shortTime = fullTime.slice(0, 5); 
                                
                                html += `
                                <div class="d-flex align-items-center p-2 rounded ${hoverClass}" style="background: rgba(255,255,255,0.02)">
                                    <div class="me-3 ${timeColorClass} fw-bold">${shortTime}</div>
                                    <div>
                                        <h6 class="mb-0 text-white-custom">${item.program_title || 'Unknown Program'}</h6>
                                        <small class="text-muted-custom">Host: ${item.host_name || 'No Host'}</small>
                                    </div>
                                </div>
                                `;
                            });
                            nextProgramsContainer.innerHTML = html;
                        } else {
                            nextProgramsContainer.innerHTML = '<div class="text-muted-custom py-3">No upcoming programs.</div>';
                        }
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        nextProgramsContainer.innerHTML = '<div class="text-danger py-3">Failed to load.</div>';
                    });
                });

            // 随机数运算
            document.addEventListener('DOMContentLoaded', () => {
                // 生成 2400 到 2499 之间的随机整数
                const min = 2400;
                const max = 2499;
                const randomAudience = Math.floor(Math.random() * (max - min + 1)) + min;

                // 写入到页面中
                document.getElementById('audience-count').textContent = randomAudience.toLocaleString();
            });
            </script>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>