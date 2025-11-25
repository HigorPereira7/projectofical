<?php
// Carregar dados do sensor
function load_sensor_data() {
    if (file_exists(SENSOR_DATA_FILE)) {
        $data = file_get_contents(SENSOR_DATA_FILE);
        return json_decode($data, true) ?: [];
    }
    return [];
}

// Verificar alarmes
function check_alarms($reading, $ph_alarm, $temp_alarm) {
    $alerts = [];
    
    if ($ph_alarm['active']) {
        if ($reading['ph'] < $ph_alarm['min']) {
            $alerts['ph'] = "ALARME: pH BAIXO (" . $reading['ph'] . " < " . $ph_alarm['min'] . ")";
        } elseif ($reading['ph'] > $ph_alarm['max']) {
            $alerts['ph'] = "ALARME: pH ALTO (" . $reading['ph'] . " > " . $ph_alarm['max'] . ")";
        }
    }
    
    if ($temp_alarm['active']) {
        if ($reading['temp'] < $temp_alarm['min']) {
            $alerts['temp'] = "ALARME: TEMP. BAIXA (" . $reading['temp'] . "°C < " . $temp_alarm['min'] . "°C)";
        } elseif ($reading['temp'] > $temp_alarm['max']) {
            $alerts['temp'] = "ALARME: TEMP. ALTA (" . $reading['temp'] . "°C > " . $temp_alarm['max'] . "°C)";
        }
    }
    
    return $alerts;
}

// Carregar usuários
function load_users() {
    $users = [];
    if (file_exists(USERS_FILE)) {
        $user_lines = file(USERS_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($user_lines as $line) {
            $parts = explode('|', $line);
            if (count($parts) >= 3) {
                // Novo formato com senha e role: email|nome|telefone|senha_hash|role|data
                // Formato antigo: email|nome|telefone|data
                $user = [
                    'email' => $parts[0],
                    'name' => $parts[1],
                    'phone' => $parts[2] ?? '',
                    'date' => $parts[5] ?? ($parts[4] ?? ($parts[3] ?? ''))
                ];
                
                // Se tem 6 partes, significa que tem senha e role
                if (count($parts) >= 6) {
                    $user['password'] = $parts[3];
                    $user['role'] = $parts[4];
                } elseif (count($parts) >= 5) {
                    // Se tem 5 partes, pode ter senha sem role
                    $user['password'] = $parts[3];
                    $user['role'] = 'user'; // Role padrão
                } else {
                    // Usuários antigos sem senha
                    $user['role'] = 'user'; // Role padrão
                }
                
                $users[] = $user;
            }
        }
    }
    return $users;
}

// Adicionar novo usuário
function add_user($email, $name, $phone = '') {
    $user_data = implode('|', [
        filter_var($email, FILTER_SANITIZE_EMAIL),
        htmlspecialchars($name),
        htmlspecialchars($phone),
        date('Y-m-d H:i:s')
    ]) . "\n";
    
    return file_put_contents(USERS_FILE, $user_data, FILE_APPEND);
}

// Editar usuário existente
function edit_user($user_index, $name, $email, $phone = '', $role = 'user') {
    $users = load_users();
    
    if (isset($users[$user_index])) {
        $users[$user_index]['name'] = htmlspecialchars($name);
        $users[$user_index]['email'] = filter_var($email, FILTER_SANITIZE_EMAIL);
        $users[$user_index]['phone'] = htmlspecialchars($phone);
        $users[$user_index]['role'] = $role;
        
        return save_users($users);
    }
    
    return false;
}

// Excluir usuário
function delete_user($user_index) {
    $users = load_users();
    
    if (isset($users[$user_index])) {
        unset($users[$user_index]);
        $users = array_values($users); // Reindexar array
        
        return save_users($users);
    }
    
    return false;
}

// Salvar lista de usuários
function save_users($users) {
    $user_lines = [];
    foreach ($users as $user) {
        // Para todos os usuários, usar o novo formato com role
        $user_data = [
            $user['email'],
            $user['name'],
            $user['phone'] ?? '',
            $user['password'] ?? '',
            $user['role'] ?? 'user',
            $user['date'] ?? date('Y-m-d H:i:s')
        ];
        $user_lines[] = implode('|', $user_data);
    }
    
    return file_put_contents(USERS_FILE, implode("\n", $user_lines) . "\n");
}

// Função para verificar se usuário é admin
function is_admin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}
