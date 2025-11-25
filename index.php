<?php
require_once 'config.php';
require_once 'includes/functions.php';

// Inicializar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se o usuário já está logado, redirecionar para o dashboard
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

// Processar login
$login_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validações
    if (empty($email) || empty($password)) {
        $login_error = "Por favor, preencha todos os campos.";
    } else {
        // Verificar credenciais
        $users = load_users();
        $authenticated = false;
        
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                // Verificar senha (em produção, usar password_verify)
                // Para demonstração, vamos usar uma verificação simples
                if (isset($user['password'])) {
                    // Se o usuário tem senha armazenada, verificar
                    if (password_verify($password, $user['password'])) {
                        $authenticated = true;
                        $_SESSION['user'] = $user;
                        break;
                    }
                } else {
                    // Para usuários antigos sem senha, usar senha padrão
                    if ($password === '123456') {
                        $authenticated = true;
                        $_SESSION['user'] = $user;
                        break;
                    }
                }
            }
        }
        
        if ($authenticated) {
            // Redirecionar para dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $login_error = "Email ou senha incorretos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AquaTeste</title>
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
        <div style="text-align: center; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 15px; border: 1px solid rgba(255, 255, 255, 0.2); padding: 40px; max-width: 400px; width: 100%;">
            <div class="logo-container" style="margin-bottom: 20px;">
                <img src="assets/img/Aqua.svg" alt="AquaTeste Logo" class="logo" style="height: 120px;">
            </div>
            <div style="margin-bottom: 25px;">
                <h1 style="font-size: 1.125rem; margin-bottom: 8px; color: #ffffff;">Sistema de Monitoramento de Aquário</h1>
                <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.75rem;">Acesse sua conta para monitorar seu aquário</p>
            </div>
            <h2 style="font-size: 1.5rem; margin-bottom: 25px;"><i class="fas fa-sign-in-alt"></i> Acessar Sistema</h2>
            
            <?php if (isset($login_error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?= $login_error ?>
            </div>
            <?php endif; ?>
            
            <div class="register-form-container">
                <form method="POST" class="register-form">
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="email" name="email" required 
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                               placeholder="seu.email@exemplo.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Senha</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Digite sua senha">
                    </div>
                    
                    <button type="submit" name="login" class="register-button">
                        <i class="fas fa-sign-in-alt"></i> Entrar
                    </button>
                    
                    <div style="text-align: center; margin-top: 20px; color: rgba(255, 255, 255, 0.8);">
                        <p>Não tem uma conta? 
                            <a href="register.php" style="color: #3498db; text-decoration: none; font-weight: 500;">
                                <i class="fas fa-user-plus"></i> Criar conta
                            </a>
                        </p>
                    </div>
                    
                    <div style="text-align: center; margin-top: 15px; font-size: 0.9rem; color: rgba(255, 255, 255, 0.6);">
                        <p><strong>Dica:</strong> Para usuários antigos, use a senha padrão: <code>123456</code></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="assets/js/script.js"></script>
</body>
