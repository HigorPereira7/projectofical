<?php
function render_users($users, $action_message = null) {
?>
<div id="users" class="tab-content">
    <h2><i class="fas fa-users"></i> Usuários Cadastrados</h2>
    
    <?php if (isset($action_message)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?= $action_message ?>
    </div>
    <?php endif; ?>
    
    <div class="users-container">
        <?php if (!empty($users)): ?>
            <div class="users-list">
                <?php foreach (array_reverse($users) as $index => $user): ?>
                <div class="user-card" data-user-index="<?= count($users) - $index - 1 ?>">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-info">
                        <h4><?= htmlspecialchars($user['name']) ?></h4>
                        <p><?= htmlspecialchars($user['email']) ?></p>
                        <p class="user-role" style="display: none;"><?= htmlspecialchars($user['role'] ?? 'user') ?></p>
                        <div class="user-badge <?= ($user['role'] ?? 'user') === 'admin' ? 'admin-badge' : 'user-badge' ?>">
                            <i class="fas <?= ($user['role'] ?? 'user') === 'admin' ? 'fa-user-shield' : 'fa-user' ?>"></i>
                            <?= ($user['role'] ?? 'user') === 'admin' ? 'Administrador' : 'Usuário Comum' ?>
                        </div>
                        <?php if (!empty($user['phone'])): ?>
                        <p><i class="fas fa-phone"></i> <?= htmlspecialchars($user['phone']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($user['date'])): ?>
                        <p class="user-date">Cadastro: <?= date('d/m/Y H:i', strtotime($user['date'])) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="user-actions">
                        <button class="edit-user-btn" data-user-index="<?= count($users) - $index - 1 ?>" title="Editar usuário">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="delete-user-btn" data-user-index="<?= count($users) - $index - 1 ?>" title="Excluir usuário">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-users-slash"></i>
                <h3>Nenhum usuário cadastrado</h3>
                <p>Use a aba "Cadastro" para adicionar usuários ao sistema</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Edição (fora da aba para garantir z-index correto) -->
<div id="editUserModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Editar Usuário</h3>
            <span class="close-modal">&times;</span>
        </div>
        <form id="editUserForm" method="POST" class="register-form">
            <input type="hidden" id="editUserIndex" name="edit_user_index">
            <div class="form-group">
                <label for="editName"><i class="fas fa-user"></i> Nome Completo</label>
                <input type="text" id="editName" name="edit_name" required placeholder="Digite o nome completo">
            </div>
            
            <div class="form-group">
                <label for="editEmail"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="editEmail" name="edit_email" required placeholder="seu.email@exemplo.com">
            </div>
            
            <div class="form-group">
                <label for="editPhone"><i class="fas fa-phone"></i> Telefone (Opcional)</label>
                <input type="tel" id="editPhone" name="edit_phone" placeholder="(11) 99999-9999">
            </div>
            
            <div class="form-group">
                <label for="editRole"><i class="fas fa-user-shield"></i> Tipo de Usuário</label>
                <select id="editRole" name="edit_role" required>
                    <option value="user">Usuário Comum</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="cancel-btn">Cancelar</button>
                <button type="submit" class="save-btn">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
<?php
}
