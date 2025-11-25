<?php
require_once 'config.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['ph']) || !isset($input['temp'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados incompletos']);
    exit;
}

$new_reading = [
    'ph' => floatval($input['ph']),
    'temp' => floatval($input['temp']),
    'timestamp' => date('Y-m-d H:i:s')
];

$sensor_data = load_sensor_data();
$sensor_data[] = $new_reading;

// Manter apenas as últimas 1000 leituras
if (count($sensor_data) > 1000) {
    $sensor_data = array_slice($sensor_data, -1000);
}

file_put_contents(SENSOR_DATA_FILE, json_encode($sensor_data));

// Verificar alarmes
$alerts = check_alarms($new_reading, $_SESSION['ph_alarm'], $_SESSION['temp_alarm']);

echo json_encode([
    'success' => true,
    'reading' => $new_reading,
    'alerts' => $alerts
]);
?>
