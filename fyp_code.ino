// fyp code
#include <Wire.h>
#include "MAX30100_PulseOximeter.h"

#include <WiFi.h>
#include <PubSubClient.h>

#include <LiquidCrystal_I2C.h>

#define REPORTING_PERIOD_MS     2000

PulseOximeter pox;

uint32_t tsLastReport = 0;

float heart_rate;
int SpO2;

char ssid[] = "cp";
char pass[] = "xxxxxxxxxx";

// MQTT Broker
const char *mqtt_broker = "broker.emqx.io";
const char *mqtt_username = "emqx";
const char *mqtt_password = "public";
const int mqtt_port = 1883;

const char *heart_rate_topic = "fyp/heart_rate";
const char *spo2_topic = "fyp/spo2";
const char *ecg_topic = "fyp/ecg";

WiFiClient espClient;
PubSubClient client(espClient);

// Callback (registered below) fired when a pulse is detected
void onBeatDetected()
{
    Serial.println("♥ Beat!");
}

void setup()
{
    Serial.begin(115200);

    // Connect to WiFi
    WiFi.begin(ssid, pass);
    while (WiFi.status() != WL_CONNECTED) {
      delay(500);
      Serial.println("Connecting to WiFi..");
    }
    Serial.println("Connected to the WiFi network");
  
    // Connect to MQTT Broker
    client.setServer(mqtt_broker, mqtt_port);
    client.setCallback(callback);
    while (!client.connected()) {
      String client_id = "esp32-client-";
      client_id += String(WiFi.macAddress());
      Serial.printf("The client %s connects to the public MQTT broker\n", client_id.c_str());
      if (client.connect(client_id.c_str(), mqtt_username, mqtt_password)) {
        Serial.println("Public EMQX MQTT broker connected");
      } else {
        Serial.print("Failed with state ");
        Serial.print(client.state());
        delay(2000);
      }
    }

    Serial.print("Initializing pulse oximeter..");
    // Initialize the PulseOximeter instance
    // Failures are generally due to an improper I2C wiring, missing power supply
    // or wrong target chip
    if (!pox.begin()) {
        Serial.println("FAILED TO CONNECT");
        for(;;);
    } else {
        Serial.println("SUCCESS TO CONNECT");
    }

    // The default current for the IR LED is 50mA 
     pox.setIRLedCurrent(MAX30100_LED_CURR_7_6MA);

    // Register a callback for the beat detection
    pox.setOnBeatDetectedCallback(onBeatDetected);
    
    pinMode(41, INPUT); // Setup for leads off detection LO + //pin 41 = TX0
    pinMode(40, INPUT); // Setup for leads off detection LO - //pin 40 = RX0
}

void loop()
{
    // max30100
    pox.update();
    heart_rate = pox.getHeartRate();
    SpO2 = pox.getSpO2();

    // For heart rate and spo2, a value of 0 means "invalid"
    if (millis() - tsLastReport > REPORTING_PERIOD_MS) {
        Serial.print("Heart rate:");
        Serial.print(heart_rate);
        Serial.print("bpm / SpO2:");
        Serial.print(SpO2);
        Serial.println("%");
        
        // Publish Heart rate and SpO2 to MQTT topic
        char heartrateStr[6];
        dtostrf(heart_rate, 4, 2, heartrateStr);
        char spo2Str[6];
        dtostrf(SpO2, 4, 2, spo2Str);
        client.publish(heart_rate_topic, heartrateStr);
        client.publish(spo2_topic, spo2Str);  

        // AD8232 ECG
        if((digitalRead(40) == 1)||(digitalRead(41 ) == 1)){
          Serial.println('!');
        }
        else{
            Serial.print("AD8232 Value: ");
            Serial.println(analogRead(A0));
            Serial.print("\n");
            
            // Publish ecg value to MQTT topic
            char ecgStr[6];
            dtostrf(analogRead(A0), 4, 2, ecgStr);
            client.publish(ecg_topic , ecgStr);
        }

        tsLastReport = millis();
    }
}

void callback(char *topic, byte *payload, unsigned int length) {
  Serial.print("Message arrived in topic: ");
  Serial.println(topic);
  Serial.print("Message:");
  for (int i = 0; i < length; i++) {
    Serial.print((char) payload[i]);
  }
  Serial.println();
  Serial.println("-----------------------");
}
