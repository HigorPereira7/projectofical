<?php
// Configurações gerais do sistema
define('DATA_DIR', __DIR__ . '/data');
define('SENSOR_DATA_FILE', DATA_DIR . '/sensor_data.json');
define('USERS_FILE', DATA_DIR . '/users.txt');

// Configurações padrão dos alarmes
define('DEFAULT_PH_MIN', 6.5);
define('DEFAULT_PH_MAX', 7.5);
define('DEFAULT_TEMP_MIN', 22);
define('DEFAULT_TEMP_MAX', 28);

// Inicializar sessão
session_start();

// Configurar alarmes padrão se não existirem
if (!isset($_SESSION['ph_alarm'])) {
    $_SESSION['ph_alarm'] = [
        'min' => DEFAULT_PH_MIN,
        'max' => DEFAULT_PH_MAX,
        'active' => true
    ];
}

if (!isset($_SESSION['temp_alarm'])) {
    $_SESSION['temp_alarm'] = [
        'min' => DEFAULT_TEMP_MIN,
        'max' => DEFAULT_TEMP_MAX,
        'active' => true
    ];
}
?>
