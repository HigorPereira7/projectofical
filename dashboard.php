<?php
require_once 'config.php';
require_once 'includes/functions.php';

// Inicializar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário está logado
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Processar logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

require_once 'includes/layouts/layout.php';
require_once 'includes/layouts/dashboard.php';
require_once 'includes/layouts/alarms.php';
require_once 'includes/layouts/register.php';
require_once 'includes/layouts/users.php';

// Carregar dados
$esp_ip = "http://172.20.10.7/dados"; // coloque o IP real do ESP

$latest_reading = null;

try {
    $json = file_get_contents($esp_ip);
    if ($json !== false) {
        $data = json_decode($json, true);

        $latest_reading = [
            'ph' => $data['ph'],
            'temp' => $data['temperatura'],
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // opcional: salvar histórico local
        $sensor_data = load_sensor_data();
        $sensor_data[] = $latest_reading;
        //save_sensor_data($sensor_data);

    } else {
        throw new Exception("Falha ao conectar ESP.");
    }

} catch (Exception $e) {
    // fallback caso ESP esteja offline
    $sensor_data = load_sensor_data();
    $latest_reading = !empty($sensor_data)
        ? end($sensor_data)
        : ['ph' => 7.0, 'temp' => 26.0, 'timestamp' => date('Y-m-d H:i:s')];
}


// Variáveis para mensagens de sucesso
$alarm_success = null;
$registration_success = null;

// Processar formulários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['set_alarms'])) {
        $ph_alarm = [
            'min' => floatval($_POST['ph_min']),
            'max' => floatval($_POST['ph_max']),
            'active' => isset($_POST['ph_active'])
        ];
        $temp_alarm = [
            'min' => floatval($_POST['temp_min']),
            'max' => floatval($_POST['temp_max']),
            'active' => isset($_POST['temp_active'])
        ];
        
        $_SESSION['ph_alarm'] = $ph_alarm;
        $_SESSION['temp_alarm'] = $temp_alarm;
        $alarm_success = "Alarmes configurados com sucesso!";
    }
    
    if (isset($_POST['register'])) {
        if (add_user($_POST['email'], $_POST['name'], $_POST['phone'] ?? '')) {
            $registration_success = "Cadastro realizado com sucesso!";
            $users = load_users(); // Recarregar lista de usuários
        }
    }
    
    if (isset($_POST['edit_user_index'])) {
        if (edit_user($_POST['edit_user_index'], $_POST['edit_name'], $_POST['edit_email'], $_POST['edit_phone'] ?? '', $_POST['edit_role'] ?? 'user')) {
            $action_message = "Usuário editado com sucesso!";
            $users = load_users(); // Recarregar lista de usuários
        }
    }
    
    if (isset($_POST['delete_user_index'])) {
        if (delete_user($_POST['delete_user_index'])) {
            $action_message = "Usuário excluído com sucesso!";
            $users = load_users(); // Recarregar lista de usuários
        }
    }
}

// Verificar alarmes e determinar status
$alerts = check_alarms($latest_reading, $_SESSION['ph_alarm'], $_SESSION['temp_alarm']);
$status_class = empty($alerts) ? 'status-good' : 'status-alert';

// Renderizar página
get_header('AquaTeste - Sistema de Monitoramento de Aquário', $status_class);

// Renderizar conteúdo das abas
render_dashboard($sensor_data, $latest_reading, $alerts, $_SESSION['ph_alarm'], $_SESSION['temp_alarm']);
render_alarms($_SESSION['ph_alarm'], $_SESSION['temp_alarm'], $alarm_success);
render_register($registration_success);
render_users($users, $action_message ?? null);

// Adicionar dados do sensor para os gráficos
echo "<script>const sensorData = " . json_encode($sensor_data) . ";</script>";

get_footer();
?>
