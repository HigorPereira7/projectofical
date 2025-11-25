<?php
require_once 'config.php';
require_once 'includes/functions.php';

// Inicializar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Processar cadastro
$registration_success = null;
$registration_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validações
    if (empty($name) || empty($email) || empty($password)) {
        $registration_error = "Por favor, preencha todos os campos obrigatórios.";
    } elseif ($password !== $confirm_password) {
        $registration_error = "As senhas não coincidem.";
    } elseif (strlen($password) < 6) {
        $registration_error = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        // Verificar se email já existe
        $users = load_users();
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $registration_error = "Este email já está cadastrado.";
                break;
            }
        }
        
        if (!$registration_error) {
            // Adicionar usuário com senha (em produção, usar hash)
            $user_data = implode('|', [
                filter_var($email, FILTER_SANITIZE_EMAIL),
                htmlspecialchars($name),
                htmlspecialchars($phone),
                password_hash($password, PASSWORD_DEFAULT),
                date('Y-m-d H:i:s')
            ]) . "\n";
            
            if (file_put_contents(USERS_FILE, $user_data, FILE_APPEND)) {
                $registration_success = "Cadastro realizado com sucesso!";
                // Limpar formulário
                $_POST = [];
            } else {
                $registration_error = "Erro ao salvar cadastro. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - AquaTeste</title>
    <link rel="stylesheet" href="assets/css/style.css">
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

    <div style="display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px;">
        <div style="text-align: center; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 15px; border: 1px solid rgba(255, 255, 255, 0.2); padding: 40px; max-width: 450px; width: 100%;">
            <div class="logo-container" style="margin-bottom: 20px;">
                <img src="assets/img/Aqua.svg" alt="AquaTeste Logo" class="logo" style="height: 120px;">
            </div>
            <div style="margin-bottom: 25px;">
                <h1 style="font-size: 1.125rem; margin-bottom: 8px; color: #ffffff;">Sistema de Monitoramento de Aquário</h1>
                <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem;">Crie sua conta para acessar o sistema</p>
            </div>
            <h2 style="font-size: 1.5rem; margin-bottom: 25px;"><i class="fas fa-user-plus"></i> Criar Nova Conta</h2>
            
            <?php if (isset($registration_success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= $registration_success ?>
                <p style="margin-top: 10px;">
                    <a href="login.php" style="color: #2ecc71; text-decoration: underline;">
                        <i class="fas fa-sign-in-alt"></i> Fazer login agora
                    </a>
                </p>
            </div>
            <?php endif; ?>
            
            <?php if (isset($registration_error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?= $registration_error ?>
            </div>
            <?php endif; ?>
            
            <div class="register-form-container">
                <form method="POST" class="register-form">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-user"></i> Nome Completo *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" 
                               placeholder="Digite seu nome completo">
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email *</label>
                        <input type="email" id="email" name="email" required 
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                               placeholder="seu.email@exemplo.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone"><i class="fas fa-phone"></i> Telefone (Opcional)</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" 
                               placeholder="(11) 99999-9999">
                    </div>
                    
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Senha *</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Digite sua senha (mínimo 6 caracteres)">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password"><i class="fas fa-lock"></i> Confirmar Senha *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               placeholder="Digite a senha novamente">
                    </div>
                    
                    <button type="submit" name="register" class="register-button">
                        <i class="fas fa-user-plus"></i> Criar Conta
                    </button>
                    
                    <div style="text-align: center; margin-top: 20px; color: rgba(255, 255, 255, 0.8);">
                        <p>Já tem uma conta? 
                            <a href="login.php" style="color: #3498db; text-decoration: none; font-weight: 500;">
                                <i class="fas fa-sign-in-alt"></i> Fazer login
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="assets/js/script.js"></script>
</body>
</html>
