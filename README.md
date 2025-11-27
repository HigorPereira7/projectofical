# Monitor-pH
codigo Arduino

#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <ArduinoJson.h>
#include <EEPROM.h>

// Configuração do  WiFi

const char* ssid = "iPhone de Rodrigo";
const char* password = "@Maria1209";

ESP8266WebServer server(80);


// sensor de pH

#define PH_PIN A0
float phValue = 0.0;

// Coeficientes da reta pH = a*V + b
float ph_a = -5.5555;          
float ph_b = 19.9387645;       

// intercept ajustado p/ pH=7.5 em ADC=858


// sensor de temperatura

#define ONE_WIRE_BUS D2
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
float temperatureC = 0.0;


// guardar coeficientes na EPROM

#define EEPROM_ADDR_A 0
#define EEPROM_ADDR_B 8

void salvarCoef() {
  EEPROM.put(EEPROM_ADDR_A, ph_a);
  EEPROM.put(EEPROM_ADDR_B, ph_b);
  EEPROM.commit();
}

void carregarCoef() {
  EEPROM.get(EEPROM_ADDR_A, ph_a);
  EEPROM.get(EEPROM_ADDR_B, ph_b);

  if (isnan(ph_a) || isnan(ph_b)) {
    ph_a = -5.5555;
    ph_b = 19.9387645;   
  }
}


// setup

void setup() {
  Serial.begin(115200);
  EEPROM.begin(32);

  carregarCoef();

  sensors.begin();

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(800);
    Serial.println("Conectando ao WiFi...");
  }
  Serial.println("WiFi conectado! IP: " + WiFi.localIP().toString());

  server.on("/dados", HTTP_GET, enviarDados);
  server.on("/calibrar", HTTP_POST, calibrarPH);
  server.on("/reset", HTTP_POST, resetarESP);

  server.begin();

  Serial.println("Servidor iniciado");
}


// LOOP

void loop() {
  server.handleClient();

  lerSensorPH();
  lerTemperatura();

  delay(2000);
}


// Leituras

void lerSensorPH() {
  int analogValue = analogRead(PH_PIN);

  // Conversão REAL usada no seu hardware
  float voltage = analogValue * (2.67 / 1023.0);

  phValue = ph_a * voltage + ph_b;

  Serial.printf("ADC: %d  V: %.3f  pH: %.3f\n",
                analogValue, voltage, phValue);
}

void lerTemperatura() {
  sensors.requestTemperatures();
  temperatureC = sensors.getTempCByIndex(0);
}


// API - enviar dados

void enviarDados() {
  StaticJsonDocument<200> json;
  json["ph"] = phValue;
  json["temperatura"] = temperatureC;
  json["a"] = ph_a;
  json["b"] = ph_b;
  json["timestamp"] = millis();

  String resposta;
  serializeJson(json, resposta);

  server.send(200, "application/json", resposta);
}


// API - Calibração

void calibrarPH() {
  StaticJsonDocument<512> json;

  if (server.hasArg("plain") == false) {
    server.send(400, "text/plain", "JSON não recebido");
    return;
  }

  DeserializationError erro = deserializeJson(json, server.arg("plain"));
  if (erro) {
    server.send(400, "text/plain", "Erro ao ler JSON");
    return;
  }

  JsonArray pontos = json["pontos"];
  int N = pontos.size();

  if (N < 2) {
    server.send(400, "text/plain", "Forneça ao menos 2 pontos");
    return;
  }

  double sumV = 0, sumPH = 0, sumVPH = 0, sumV2 = 0;

  for (int i = 0; i < N; i++) {
    float ph = pontos[i]["ph"];
    int adc = pontos[i]["adc"];

    float V = adc * (2.67 / 1023.0);

    sumV += V;
    sumPH += ph;
    sumVPH += V * ph;
    sumV2 += V * V;
  }

  double denom = (N * sumV2 - sumV * sumV);

  if (fabs(denom) < 1e-9) {
    server.send(400, "text/plain", "Erro matemático (pontos inválidos)");
    return;
  }

  ph_a = (N * sumVPH - sumV * sumPH) / denom;
  ph_b = (sumPH - ph_a * sumV) / N;

  salvarCoef();

  String resp = "Calibrado com sucesso!\n";
  resp += "a = " + String(ph_a, 8) + "\n";
  resp += "b = " + String(ph_b, 8);

  server.send(200, "text/plain", resp);
}


// API - reset
void resetarESP() {
  server.send(200, "text/plain", "Reiniciando...");
  ESP.restart();
}
