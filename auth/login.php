<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../config/database.php";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"] ?? "";

    if (!$email || empty($password)) {
        $error = "Preencha todos os campos.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, name, password, role FROM users WHERE email = ? AND status = 1"
        );
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["user_role"] = $user["role"];

                // 🔑 REDIRECIONAMENTO PARA DASHBOARD (PRG)
                header("Location: ../index.php");
                exit;
            } else {
                $error = "Senha incorreta.";
            }
        } else {
            $error = "Utilizador não encontrado.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="text-center mb-3">Login</h4>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"  required>
                        </div>

                        <div class="mb-3">
                            <label>Senha</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
