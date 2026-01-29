<?php
session_start();

// ✅ Se não estiver logada, redireciona para login
if (!isset($_SESSION["user_id"])) {
    header("Location: auth/login.php");
    exit;
}
?>

<h1>Bem-vinda, <?= $_SESSION["user_name"] ?> 👋</h1>
<p>Dashboard inicial</p>
<a href="auth/logout.php" class="btn btn-danger">Logout</a>
