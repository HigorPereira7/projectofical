<?php
function render_alarms($ph_alarm, $temp_alarm, $alarm_success = null) {
?>
<div id="alarms" class="tab-content">
    <h2><i class="fas fa-bell"></i> Configuração de Alarmes</h2>
    
    <?php if (isset($alarm_success)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?= $alarm_success ?>
    </div>
    <?php endif; ?>
    
    <div class="alarms-configuration">
        <form method="POST" class="alarm-form">
            <div class="alarm-config-card">
                <div class="alarm-header">
                    <i class="fas fa-tint"></i>
                    <h3>Alarme de pH</h3>
                    <label class="switch">
                        <input type="checkbox" name="ph_active" <?= $ph_alarm['active'] ? 'checked' : '' ?>>
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="alarm-body">
                    <p>Configure os limites aceitáveis para o pH da água</p>
                    <div class="range-inputs">
                        <div class="input-group">
                            <label>Mínimo</label>
                            <input type="number" step="0.1" name="ph_min" value="<?= $ph_alarm['min'] ?>" placeholder="6.0" required>
                        </div>
                        <div class="range-separator">à</div>
                        <div class="input-group">
                            <label>Máximo</label>
                            <input type="number" step="0.1" name="ph_max" value="<?= $ph_alarm['max'] ?>" placeholder="8.0" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alarm-config-card">
                <div class="alarm-header">
                    <i class="fas fa-thermometer-half"></i>
                    <h3>Alarme de Temperatura</h3>
                    <label class="switch">
                        <input type="checkbox" name="temp_active" <?= $temp_alarm['active'] ? 'checked' : '' ?>>
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="alarm-body">
                    <p>Configure os limites aceitáveis para a temperatura da água</p>
                    <div class="range-inputs">
                        <div class="input-group">
                            <label>Mínimo (°C)</label>
                            <input type="number" step="0.1" name="temp_min" value="<?= $temp_alarm['min'] ?>" placeholder="20.0" required>
                        </div>
                        <div class="range-separator">à</div>
                        <div class="input-group">
                            <label>Máximo (°C)</label>
                            <input type="number" step="0.1" name="temp_max" value="<?= $temp_alarm['max'] ?>" placeholder="30.0" required>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" name="set_alarms" class="save-button">
                <i class="fas fa-save"></i> Salvar Configurações
            </button>
        </form>
    </div>
</div>
<?php
}
?>