<?php
session_start();
require_once "../config/database.php";

// Protege a página
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Pegar ID do usuário
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// Buscar dados do usuário
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: index.php");
    exit;
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $status = $_POST['status'];

    if (!$name || !$email) {
        $error = "Preencha todos os campos!";
    } else {
        if (!empty($_POST['password'])) {
            // Atualiza senha se preenchida
            $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "UPDATE users SET name=?, email=?, password=?, role=?, status=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "ssssii", $name, $email, $password_hash, $role, $status, $id);
        } else {
            // Não atualiza senha
            $stmt = mysqli_prepare($conn, "UPDATE users SET name=?, email=?, role=?, status=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "sssii", $name, $email, $role, $status, $id);
        }

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Erro ao atualizar usuário: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Editar Usuário</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Senha (deixe em branco para não alterar)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>Perfil</label>
            <select name="role" class="form-control">
                <option value="user" <?= $user['role']=='user'?'selected':'' ?>>Usuário</option>
                <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Administrador</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="1" <?= $user['status']==1?'selected':'' ?>>Ativo</option>
                <option value="0" <?= $user['status']==0?'selected':'' ?>>Inativo</option>
            </select>
        </div>

        <button class="btn btn-primary">Atualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
