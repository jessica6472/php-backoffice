<?php
session_start();
require_once "../config/database.php";

// Protege a página
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Busca todos os usuários
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Usuários</h2>
    <a href="create.php" class="btn btn-success mb-3">Adicionar Usuário</a>
    <table class="table table-bordered table-striped bg-white">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['role'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="../index.php" class="btn btn-secondary">Voltar ao Dashboard</a>
</div>
</body>
</html>
