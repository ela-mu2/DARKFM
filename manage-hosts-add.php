<?php
require('config/db.php');

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $bio = trim($_POST['bio']);
    $contact_email = trim($_POST['contact_email']);
    
    if (!empty($name)) {
        try {
            $sql = "INSERT INTO hosts (name, bio, contact_email) VALUES (:name, :bio, :contact_email)";
            $stmt = $pdo->prepare($sql); 
            $stmt->execute([
                ':name'          => $name,
                ':bio'           => $bio,
                ':contact_email' => $contact_email
            ]);
            header("Location: manage-hosts.php?success=1");
            exit;
        } catch (PDOException $e) {
            $status = 'error';
            $message = 'Database error:' . $e->getMessage();
        }
    } else {
        $status = 'error';
        $message = "Please fill in the host's name!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DARKFM - Hosts Add</title>
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
        <h3 class="mb-4 text-teal">New host</h3>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-danger bg-dark text-white border-secondary mb-3">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <form action="manage-hosts-add.php" method="POST">
            <div class="mb-3">
                <label class="form-label text-secondary">Host's name<span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Please enter the host's name" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label text-secondary">Contact Email</label>
                <input type="email" name="contact_email" class="form-control" placeholder="example@darkfm.com">
            </div>
            
            <div class="mb-3">
                <label class="form-label text-secondary">Bio</label>
                <textarea name="bio" class="form-control" rows="4" placeholder="Introduce this host's personality and strengths in programming..."></textarea>
            </div>
            
            <div class="d-flex flex-column flex-sm-row gap-2 mt-4">
                <a href="manage-hosts.php" class="btn btn-secondary w-100 w-sm-50 py-2 border-0 text-white-custom order-2 order-sm-1" style="background: rgba(255, 255, 255, 0.1);">Cancel</a>
                <button type="submit" class="btn btn-accent w-100 w-sm-50 py-2 order-1 order-sm-2">Preserve the host</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>