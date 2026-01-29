<?php
session_start();

// Se não estiver logada, redireciona para login
if (!isset($_SESSION["user_id"])) {
    header("Location: auth/login.php");
    exit;
}
?>

<h1>Bem-vinda, <?= $_SESSION["user_name"] ?></h1>
<a href="users/index.php" class="btn btn-danger">Lista dos Usuarios</a>
