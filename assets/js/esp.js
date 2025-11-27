// IP do seu ESP (altere para o IP real mostrado no Serial!)
const ESP_IP = "http://172.20.10.7";

// Atualiza dados na interface principal
async function buscarESP() {
    try {
        const res = await fetch(`${ESP_IP}/dados`);
        const dados = await res.json();

        // Atualiza os dados exibidos no dashboard
        document.getElementById("ph_value").textContent = dados.ph.toFixed(2);
        document.getElementById("temp_value").textContent = dados.temperatura.toFixed(2);
        document.getElementById("timestamp_value").textContent = dados.timestamp;

        // Atualiza cor de status
        document.getElementById("esp_status").innerHTML =
            "<span style='color: green;'>Online</span>";

    } catch (e) {
        console.log("Erro ao comunicar com ESP:", e);

        document.getElementById("esp_status").innerHTML =
            "<span style='color: red;'>Offline</span>";
    }
}

// Requisita a cada 5 segundos
setInterval(buscarESP, 5000);
buscarESP();

// Funções de ação
async function calibrarPH() {
    await fetch(`${ESP_IP}/calibrar`, { method: "POST" });
    alert("Calibração iniciada no ESP");
}

async function resetarESP() {
    await fetch(`${ESP_IP}/reset`, { method: "POST" });
    alert("O ESP está reiniciando");
}
