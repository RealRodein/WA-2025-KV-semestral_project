<?php
session_start();
require_once '../../models/Database.php';
require_once '../../models/User.php';

// Only allow admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /WA-2025-KV-semestral_project/my-rating/views/media/List.php');
    exit();
}

$db = (new Database())->getConnection();
$userModel = new User($db);
$users = $userModel->getAll();

// Handle feedback messages
$message = $_GET['message'] ?? null;
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Správa uživatelů</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/WA-2025-KV-semestral_project/my-rating/public/css/layout.css">
</head>
<body>
<?php
$navbarContext = 'list';
include __DIR__ . '/../../public/navbar.php';
?>
    <div class="main-wrapper">
        <div class="content">
            <h2 class="mb-4 text-center">Správa uživatelů</h2>
            <?php if ($message): ?>
                <div class="alert alert-success"> <?= htmlspecialchars($message) ?> </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"> <?= htmlspecialchars($error) ?> </div>
            <?php endif; ?>
            <table class="table table-dark table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Uživatelské jméno</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <?php
                        // If this user was just edited and failed, prefill with attempted values
                        $editId = $_GET['id'] ?? null;
                        $editEmail = $_GET['email'] ?? null;
                        $editRole = $_GET['role'] ?? null;
                        $isEditing = $editId && $editId == $u['id'];
                        $prefillEmail = $isEditing && $editEmail !== null ? $editEmail : $u['email'];
                        $prefillRole = $isEditing && $editRole !== null ? $editRole : $u['role'];
                    ?>
                    <tr>
                        <form method="post" action="/WA-2025-KV-semestral_project/my-rating/controllers/UserController.php?action=admin_update">
                            <td><?= htmlspecialchars($u['id']) ?></td>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td>
                                <input type="email" name="email" value="<?= htmlspecialchars($prefillEmail) ?>" class="form-control form-control-sm bg-dark text-light" style="width:180px;">
                            </td>
                            <td>
                                <select name="role" class="form-select form-select-sm bg-dark text-light" style="width:120px;">
                                    <option value="user" <?= $prefillRole==='user'?'selected':'' ?>>user</option>
                                    <option value="trusted" <?= $prefillRole==='trusted'?'selected':'' ?>>trusted</option>
                                    <option value="admin" <?= $prefillRole==='admin'?'selected':'' ?>>admin</option>
                                </select>
                            </td>
                            <td style="display:flex;gap:8px;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($u['id']) ?>">
                                <button type="submit" class="btn btn-sm btn-success">Uložit</button>
                        </form>
                        <form method="post" action="/WA-2025-KV-semestral_project/my-rating/controllers/UserController.php?action=admin_delete" onsubmit="return confirm('Opravdu smazat uživatele?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($u['id']) ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Smazat</button>
                        </form>
                            </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
