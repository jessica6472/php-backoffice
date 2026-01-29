<?php
session_start();
require_once "config/database.php";

// Bloquear acesso para não logados
if (!isset($_SESSION["user_id"])) {
    header("Location: auth/login.php");
    exit;
}

$user_name = $_SESSION["user_name"];
$user_role = $_SESSION["user_role"];

// Contagens para os cards
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$total_users_ativos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE status=1"))['COUNT(*)'];
$total_users_inativos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE status=0"))['COUNT(*)'];
$total_documents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM documents"))['total'];

// Futuro: $total_produtos, $total_documentos
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { min-height: 100vh; display: flex; }
        .sidebar { width: 220px; background: #0d6efd; color: white; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px; }
        .sidebar a:hover { background: rgba(255,255,255,0.1); }
        .content { flex: 1; padding: 20px; }
        .card { margin-bottom: 20px; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <h3 class="text-center py-3">BackOffice</h3>
        <a href="index.php">Dashboard</a>
        <a href="users/index.php">Usuários</a>
        <a href="documents/upload.php">Documentos</a>
        <a href="auth/logout.php">Logout</a>
    </div>

    <!-- Conteúdo principal -->
    <div class="content">
        <!-- <h2>Bem-vinda, <?= htmlspecialchars($user_name) ?> 👋</h2> -->
        <p>Função: <?= $user_role ?></p>

        <!-- Gráficos -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Total de Usuários</h5>
                    <canvas id="userChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>Status de Usuários</h5>
                    <canvas id="statusChart"></canvas>
                </div>
            </div>1
        </div>

        <!-- Cards por baixo -->
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center bg-primary text-white p-3">
                    <h5>Documentos adicionados</h5>
                    <p class="fs-3"><?= $total_documents ?></p>
                    <a href="documents/list.php" class="btn btn-light btn-sm">Ver Lista</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-success text-white p-3">
                    <h5>Usuários Ativos</h5>
                    <p class="fs-3"><?= $total_users_ativos ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-danger text-white p-3">
                    <h5>Usuários Inativos</h5>
                    <p class="fs-3"><?= $total_users_inativos ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-warning text-dark p-3">
                    <h5>Produtos</h5>
                    <p class="fs-3">0</p> <!-- futuramente buscar do DB -->
                </div>
            </div>
        </div>
    </div>

<script>
const ctxUser = document.getElementById('userChart').getContext('2d');
const userChart = new Chart(ctxUser, {
    type: 'bar',
    data: {
        labels: ['Total de Usuários'],
        datasets: [{
            label: 'Usuários',
            data: [<?= $total_users ?>],
            backgroundColor: ['#0d6efd']
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

const ctxStatus = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(ctxStatus, {
    type: 'bar',
    data: {
        labels: ['Ativos', 'Inativos'],
        datasets: [{
            label: 'Status Usuários',
            data: [<?= $total_users_ativos ?>, <?= $total_users_inativos ?>],
            backgroundColor: ['#198754', '#dc3545']
        }]
    },
    options: { responsive: true }
});
</script>
</body>
</html>
