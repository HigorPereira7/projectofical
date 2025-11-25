<?php
function render_register($registration_success = null) {
?>
<div id="register" class="tab-content">
    <h2><i class="fas fa-user-plus"></i> Cadastro de Usuário</h2>
    
    <?php if (isset($registration_success)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?= $registration_success ?>
    </div>
    <?php endif; ?>
    
    <div class="register-form-container">
        <form method="POST" class="register-form">
            <div class="form-group">
                <label for="name"><i class="fas fa-user"></i> Nome Completo</label>
                <input type="text" id="name" name="name" required placeholder="Digite seu nome completo">
            </div>
            
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" required placeholder="seu.email@exemplo.com">
            </div>
            
            <div class="form-group">
                <label for="phone"><i class="fas fa-phone"></i> Telefone (Opcional)</label>
                <input type="tel" id="phone" name="phone" placeholder="(11) 99999-9999">
            </div>
            
            <button type="submit" name="register" class="register-button">
                <i class="fas fa-user-plus"></i> Cadastrar
            </button>
        </form>
    </div>
</div>
<?php
}
?>