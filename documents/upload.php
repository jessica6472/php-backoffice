<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["documento"])) {
    $arquivo = $_FILES["documento"];
    $nome_original = $arquivo["name"];
    $tmp_name = $arquivo["tmp_name"];
    $tamanho = $arquivo["size"];
    $erro = $arquivo["error"];

    $extensoes_permitidas = ['pdf','doc','docx','png','jpg'];
    $ext = pathinfo($nome_original, PATHINFO_EXTENSION);

    if ($erro === 0) {
        if (in_array(strtolower($ext), $extensoes_permitidas)) {
            if ($tamanho <= 5*1024*1024) {
                // Salvar arquivo com nome único
                $novo_nome = time() . "_" . $nome_original;
                move_uploaded_file($tmp_name, "../documents/" . $novo_nome);

                // Salvar no banco usando sua estrutura
                $stmt = mysqli_prepare($conn, "INSERT INTO documents (user_id, file_name, file_path) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "iss", $_SESSION['user_id'], $nome_original, $novo_nome);
                mysqli_stmt_execute($stmt);

                $message = "Upload realizado com sucesso!";
            } else { $message = "Arquivo muito grande! Máx 5MB."; }
        } else { $message = "Extensão não permitida!"; }
    } else { $message = "Erro no upload!"; }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Upload de Documento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Upload de Documento</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Selecione o arquivo</label>
            <input type="file" name="documento" class="form-control" required>
        </div>
        <button class="btn btn-success">Enviar</button>
        <a href="../index.php" class="btn btn-secondary">Voltar</a>
    </form>
</div>
</body>
</html>
