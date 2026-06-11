<?php
require('config/db.php');
require('includes/header.php');

if (!isset($_GET['id'])) {
    header('Location: manage-hosts.php');
    exit;
}

$id = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("SELECT * FROM hosts WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $host = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error:" . $e->getMessage());
}

if (!$host) {
    header('Location: manage-hosts.php');
    exit;
}

$message = '';

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
            $message = 'Database error:' . $e->getMessage();
        }
    } else {
        $message = "The host's name cannot be empty!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DARKFM - Hosts Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"/>
    <link rel="stylesheet" href="includes/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container my-4 my-md-5" style="max-width: 550px;">
    <div class="card card-dark p-3 p-md-4 shadow-sm">
        <h3 class="mb-1 text-teal">Edit host</h3>
        <p class="text-secondary mb-4" style="font-size: 13px;">ID: <?= $host['id'] ?></p>

        <?php if (!empty($message)): ?>
            <div class="alert alert-danger bg-dark text-white border-secondary mb-3">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label text-secondary">Host's name<span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="<?= htmlspecialchars($host['name']) ?>"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary">Contact Email</label>
                <input
                    type="email"
                    name="contact_email"
                    class="form-control"
                    value="<?= htmlspecialchars($host['contact_email'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label text-secondary">Bio</label>
                <textarea
                    name="bio"
                    class="form-control"
                    rows="4"><?= htmlspecialchars($host['bio'] ?? '') ?></textarea>
            </div>

           <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                <a href="manage-hosts.php" class="btn btn-secondary w-100 w-sm-50 py-2 border-0 text-white-custom order-2 order-sm-1" style="background: rgba(255, 255, 255, 0.1);">Cancel</a>
                <button type="submit" class="btn btn-accent w-100 w-sm-50 py-2 order-1 order-sm-2">Save changes</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>