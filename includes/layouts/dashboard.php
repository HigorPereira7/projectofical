<?php
function render_dashboard($sensor_data, $latest_reading, $alerts, $ph_alarm, $temp_alarm) {
?>
<div id="dashboard" class="tab-content active">
    <h2><i class="fas fa-tachometer-alt"></i> Dashboard do Aquário</h2>
    
    <?php if (!empty($alerts)): ?>
    <div class="alerts-container">
        <?php foreach ($alerts as $alert): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> <?= $alert ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <!-- Central de Controle de Equipamentos -->
    <div class="control-panel">
        <h3><i class="fas fa-sliders-h"></i> Central de Controle</h3>
        <!--<div class="equipment-grid">
            <div class="equipment-item">
                <div class="equipment-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="equipment-info">
                    <h4>Luz Principal</h4>
                    <p>Iluminação LED</p>
                </div>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider round"></span>
                </label>
            </div>
            
            <div class="equipment-item">
                <div class="equipment-icon">
                    <i class="fas fa-tint"></i>
                </div>
                <div class="equipment-info">
                    <h4>Bomba de Recalque</h4>
                    <p>Circulação de água</p>
                </div>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider round"></span>
                </label>
            </div>
            
            <div class="equipment-item">
                <div class="equipment-icon">
                    <i class="fas fa-wind"></i>
                </div>
                <div class="equipment-info">
                    <h4>Skimmer</h4>
                    <p>Remoção de proteínas</p>
                </div>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider round"></span>
                </label>
            </div>
            
            <div class="equipment-item">
                <div class="equipment-icon">
                    <i class="fas fa-snowflake"></i>
                </div>
                <div class="equipment-info">
                    <h4>Chiller</h4>
                    <p>Controle de temperatura</p>
                </div>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                </label>
            </div>
            
            <div class="equipment-item">
                <div class="equipment-icon">
                    <i class="fas fa-fan"></i>
                </div>
                <div class="equipment-info">
                    <h4>Ventilador</h4>
                    <p>Resfriamento</p>
                </div>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                </label>
            </div>
            
            <div class="equipment-item">
                <div class="equipment-icon">
                    <i class="fas fa-water"></i>
                </div>
                <div class="equipment-info">
                    <h4>Dosadora</h4>
                    <p>Suplementação</p>
                </div>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider round"></span>
                </label>
            </div>
            
            <div class="equipment-item">
                <div class="equipment-icon">
                    <i class="fas fa-moon"></i>
                </div>
                <div class="equipment-info">
                    <h4>Luz Noturna</h4>
                    <p>Iluminação lunar</p>
                </div>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                </label>
            </div>
            
            <div class="equipment-item">
                <div class="equipment-icon">
                    <i class="fas fa-recycle"></i>
                </div>
                <div class="equipment-info">
                    <h4>Reator de Cálcio</h4>
                    <p>Manutenção química</p>
                </div>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider round"></span>
                </label>
            </div>
            
            <div class="equipment-item">
                <div class="equipment-icon">
                    <i class="fas fa-filter"></i>
                </div>
                <div class="equipment-info">
                    <h4>Filtro UV</h4>
                    <p>Esterilização</p>
                </div>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                </label>
            </div>
            
            <div class="equipment-item">
                <div class="equipment-icon">
                    <i class="fas fa-wave-square"></i>
                </div>
                <div class="equipment-info">
                    <h4>Gerador de Ondas</h4>
                    <p>Movimentação da água</p>
                </div>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>
    
    <div class="current-readings">
        <div class="reading-card">
            <div class="reading-icon">
                <i class="fas fa-tint"></i>
            </div>-->
            <h3>Nível de pH</h3>
            <div class="value <?= isset($alerts['ph']) ? 'alert-value' : '' ?>">
                <?= number_format($latest_reading['ph'], 2) ?>
            </div>
            <div class="reading-footer">
                <span class="timestamp"><i class="fas fa-clock"></i> <?= date('H:i:s', strtotime($latest_reading['timestamp'])) ?></span>
                <span class="date"><?= date('d/m/Y', strtotime($latest_reading['timestamp'])) ?></span>
            </div>
        </div>
        
        <div class="reading-card">
            <div class="reading-icon">
                <i class="fas fa-thermometer-half"></i>
            </div>
            <h3>Temperatura da Água</h3>
            <div class="value <?= isset($alerts['temp']) ? 'alert-value' : '' ?>">
                <?= number_format($latest_reading['temp'], 2) ?>°C
            </div>
            <div class="reading-footer">
                <span class="timestamp"><i class="fas fa-clock"></i> <?= date('H:i:s', strtotime($latest_reading['timestamp'])) ?></span>
                <span class="date"><?= date('d/m/Y', strtotime($latest_reading['timestamp'])) ?></span>
            </div>
        </div>
    </div>

    <div class="charts-container">
        <div class="chart-wrapper">
            <div class="chart-header">
                <h3><i class="fas fa-chart-line"></i> Variação de pH (Últimas 12h)</h3>
                <span class="chart-range">6.0 - 8.0</span>
            </div>
            <div class="chart-container">
                <canvas id="phChart" height="150"></canvas>
            </div>
        </div>
        
        <div class="chart-wrapper">
            <div class="chart-header">
                <h3><i class="fas fa-chart-line"></i> Variação de Temperatura (Últimas 12h)</h3>
                <span class="chart-range">20°C - 30°C</span>
            </div>
            <div class="chart-container">
                <canvas id="tempChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <div class="readings-table-container">
        <div class="table-header">
            <h3><i class="fas fa-history"></i> Histórico de Leituras</h3>
            <span><?= count($sensor_data) ?> registros</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Data/Hora</th>
                        <th>pH</th>
                        <th>Temperatura</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $recent_readings = array_slice($sensor_data, -8);
                    foreach (array_reverse($recent_readings) as $reading): 
                        $reading_alerts = check_alarms($reading, $ph_alarm, $temp_alarm);
                        $row_class = empty($reading_alerts) ? '' : 'alert-row';
                    ?>
                    <tr class="<?= $row_class ?>">
                        <td><?= date('d/m H:i', strtotime($reading['timestamp'])) ?></td>
                        <td><?= $reading['ph'] ?></td>
                        <td><?= $reading['temp'] ?>°C</td>
                        <td>
                            <?php if (empty($reading_alerts)): ?>
                                <span class="status-ok"><i class="fas fa-check"></i> OK</span>
                            <?php else: ?>
                                <span class="status-alert"><i class="fas fa-exclamation-triangle"></i> Alerta</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
}
?>
