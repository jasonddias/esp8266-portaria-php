#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>
/* Ligações do MFRC522 para o ESP8266 (ESP-12)
RST = GPIO2 / SDA(SS) = GPIO4
MOSI = GPIO13 / MISO = GPIO12
SCK = GPIO14 / GND = GND
3.3V = 3.3V */
#define RST_PIN 2 // RST-PIN MFRC522 - RFID - SPI - Modulo GPIO02
#define SS_PIN 4 // SDA-PIN MFRC522 - RFID - SPI - Modulo GPIO4
MFRC522 mfrc522(SS_PIN, RST_PIN); // Cria acesso MFRC522
void setup() {
	Serial.begin(115200);delay(250);
	Serial.println();
	Serial.println("Iniciando....");
SPI.begin(); // Inicia a serial SPI para o leitor
mfrc522.PCD_Init(); // Inicia o leitor RFID
pinMode(D0, OUTPUT); // Saída que aciona a abertura da porta
WiFi.begin("ssid", "senha");
int tentativas = 0;
while ((WiFi.status() != WL_CONNECTED) && tentativas++ < 20) {
	delay(500);
	Serial.print(".");
}
if (WiFi.status() == WL_CONNECTED)
	Serial.println("WiFi conectado");
}
char * read_RFID(char *buffer) { // Função para ler as tags
// Verifica se há cartões presentes e lê um cartão
	if (mfrc522.PICC_IsNewCardPresent() &&
		mfrc522.PICC_ReadCardSerial()) {
		buffer = dump_byte_array(mfrc522.uid.uidByte,
			mfrc522.uid.size, buffer);
// Mostra o código da tag em hexadecimal
	Serial.printf("Tag UID:%s\n", buffer);
	return buffer;
} else
return NULL;
}
// Função para converter código RFID para char [] em hexadecimal
char * dump_byte_array(byte *buffer, byte bufferSize, char * result) {
	for (byte i = 0; i < bufferSize; i++) {
		char num[3];
		itoa(buffer[i], num, 16);
		if (buffer[i] <= 0xF) strcat(result, "0");
		strcat(result, num);
	}
	return result;
}void loop() {
	char code_RFID[20] = "";
if (read_RFID(code_RFID)) { // Verificar se o cartão foi lido
if(WiFi.status() == WL_CONNECTED) { // Verifica a conexão Wifi
	HTTPClient http;
	Serial.print("[HTTP] Início...\n");
// Configura URL, cabeçalhos e monta o método POST com o
	código da TAG
	http.begin("http://sergiool.esy.es/consult.php");
	http.addHeader("Content-Type", "application/x-www-formurlencoded");
	int httpCode = http.POST(String("tag=") + code_RFID);
// httpCode será negativo em caso de erro
	if(httpCode > 0) {
// Obteve resposta do servidor
		Serial.printf("[HTTP] POST... código: %d\n", httpCode);
// Achou o arquivo
if(httpCode == HTTP_CODE_OK) { // Conectou, atualiza o
	arquivo
	String payload = http.getString();
	Serial.println(payload);
	if (payload == "OK") {
		digitalWrite(D0, HIGH);
delay(100); // Tempo suficiente para acionar o
acesso
digitalWrite(D0, LOW);
}
}
} else
Serial.printf("POST Erro: %s\n",
	http.errorToString(httpCode).c_str());
http.end();
delay(300); // Tempo suficiente para acionar o acesso
}
}
}
