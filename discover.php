<?php
//discover.php
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
    <title>Discover - DARKFM</title>
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
            <a class="navbar-brand fw-bold text-white-custom" href="index.php">
                <i class="bi bi-radio accent-color me-2"></i>DARK<span class="accent-color">FM</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active text-white-custom" href="discover.php">Discover</a></li>
                    
                    <?php if ($role === 'user' || $role === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link text-muted-custom" href="#">Rankings</a></li>
                        <li class="nav-item"><a class="nav-link text-muted-custom" href="#">Blogs</a></li>
                        <li class="nav-item"><a class="nav-link text-muted-custom" href="dashboard.php">Dashboard</a></li>
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

    <div class="container my-5">
        <h3 class="fw-bold text-white-custom mb-4"><i class="bi bi-compass accent-color me-2"></i>Discover Music & Podcasts</h3>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card-custom p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="ratio ratio-16x9 bg-secondary rounded mb-3" style="background: linear-gradient(135deg, #3B82F6, #1E3A8A);">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-soundwave display-4 text-white-custom opacity-50"></i>
                            </div>
                        </div>
                        <h5 class="text-white-custom fw-bold">Cyberpunk Beats</h5>
                        <p class="text-muted-custom small">Dive into the neon-lit futuristic soundscapes and heavy bass lines.</p>
                    </div>
                    <button class="btn btn-outline-info w-100 mt-2 text-white-custom" style="border-color: var(--accent); color: var(--accent);">Listen Now</button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="ratio ratio-16x9 bg-secondary rounded mb-3" style="background: linear-gradient(135deg, #EC4899, #701A75);">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-mic display-4 text-white-custom opacity-50"></i>
                            </div>
                        </div>
                        <h5 class="text-white-custom fw-bold">Deep Tech Talk</h5>
                        <p class="text-muted-custom small">Weekly discussions about AI, cyber security, and tech trends.</p>
                    </div>
                    <button class="btn btn-outline-info w-100 mt-2 text-white-custom" style="border-color: var(--accent); color: var(--accent);">Listen Now</button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="ratio ratio-16x9 bg-secondary rounded mb-3" style="background: linear-gradient(135deg, #10B981, #064E3B);">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-moon-stars display-4 text-white-custom opacity-50"></i>
                            </div>
                        </div>
                        <h5 class="text-white-custom fw-bold">Ambient Chill</h5>
                        <p class="text-muted-custom small">Relaxing lo-fi and ambient music perfect for coding or midnight resting.</p>
                    </div>
                    <button class="btn btn-outline-info w-100 mt-2 text-white-custom" style="border-color: var(--accent); color: var(--accent);">Listen Now</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>