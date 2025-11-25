<?php 
function get_header($title = 'AquaTeste - Sistema de Monitoramento de Aquário', $status_class = 'status-good') {
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="aquarium-background">
        <div class="fish fish-1"></div>
        <div class="fish fish-2"></div>
        <div class="fish fish-3"></div>
        <div class="bubble bubble-1"></div>
        <div class="bubble bubble-2"></div>
        <div class="bubble bubble-3"></div>
        <div class="plant plant-1"></div>
        <div class="plant plant-2"></div>
    </div>

    <div class="container">
        <header>
            <div class="header-content">
                <div class="logo-container">
                    <img src="assets/img/Aqua.svg" alt="AquaTeste Logo" class="logo">
                </div>
                <div class="header-text">
                    <h1>Sistema de Monitoramento de Aquário</h1>
                    <?php if (isset($_SESSION['user'])): ?>
                    <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem; margin-top: 5px;">
                        <i class="fas fa-user"></i> Logado como: <?= htmlspecialchars($_SESSION['user']['name']) ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <div class="status-indicator <?= $status_class ?>">
                    <i class="fas <?= $status_class === 'status-good' ? 'fa-check-circle' : 'fa-exclamation-triangle' ?>"></i>
                    <?= $status_class === 'status-good' ? 'Tudo Normal' : 'Alerta!' ?>
                </div>
                <?php if (isset($_SESSION['user'])): ?>
                <a href="dashboard.php?logout=true" class="logout-button" style="background: rgba(231, 76, 60, 0.3); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.4); padding: 8px 15px; border-radius: 20px; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 5px; transition: all 0.3s ease;">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
                <?php endif; ?>
            </div>
        </header>

        <div class="layout-container">
            <nav class="sidebar">
                <div class="sidebar-header">
                    <h3><i class="fas fa-bars"></i> Menu</h3>
                </div>
                <div class="sidebar-content">
                    <button class="sidebar-button active" data-tab="dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </button>
                    <button class="sidebar-button" data-tab="alarms">
                        <i class="fas fa-bell"></i>
                        <span>Alarmes</span>
                    </button>
                    <?php if (is_admin()): ?>
                    <button class="sidebar-button" data-tab="register">
                        <i class="fas fa-user-plus"></i>
                        <span>Cadastro</span>
                    </button>
                    <button class="sidebar-button" data-tab="users">
                        <i class="fas fa-users"></i>
                        <span>Usuários</span>
                    </button>
                    <?php endif; ?>
                    
                    <!-- Botão para desligar animações de fundo -->
                    <div class="animation-control" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                        <button type="button" id="toggleAnimations" class="animation-toggle-button" style="background: rgba(52, 152, 219, 0.2); border-color: rgba(52, 152, 219, 0.4); width: 100%; border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; padding: 15px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 12px; font-weight: 500; text-align: left;">
                            <i class="fas fa-pause"></i>
                            <span>Pausar Animações</span>
                        </button>
                    </div>
                </div>
            </nav>

            <main class="main-content">
<?php
}

function get_footer() {
?>
        </main>
    </div>
    
    <!-- Overlay para fundo escuro e desfocado (cobre todo o site) -->
    <div id="modalOverlay" class="modal-overlay" style="display: none;"></div>
    
    <script src="assets/js/script.js"></script>
</body>
</html>
<?php
}
