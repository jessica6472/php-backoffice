<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

// Buscar documentos com base na sua estrutura
$stmt = mysqli_prepare($conn, "
    SELECT d.id, d.file_name, d.file_path, d.created_at, u.name AS uploader
    FROM documents d
    JOIN users u ON d.user_id = u.id
    ORDER BY d.created_at DESC
");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Documentos Enviados</h2>
    <a href="upload.php" class="btn btn-success mb-3">Enviar Novo Documento</a>

    <table class="table table-striped table-bordered bg-white">
        <thead>
            <tr>
                <th>ID</th>
                <th>Arquivo</th>
                <th>Enviado por</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($doc = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $doc['id'] ?></td>
                <td><?= htmlspecialchars($doc['file_name']) ?></td>
                <td><?= htmlspecialchars($doc['uploader']) ?></td>
                <td><?= $doc['created_at'] ?></td>
                <td>
                    <a href="../documents/<?= $doc['file_path'] ?>" class="btn btn-primary btn-sm" target="_blank">Baixar</a>
                    <a href="delete.php?id=<?= $doc['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="../index.php" class="btn btn-secondary">Voltar ao Dashboard</a>
</div>
</body>
</html>
