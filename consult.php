<?php
// Cria a conexão
$conn = new mysqli("127.0.0.1", "root", "", "test");
// Verifica se foi criada com sucesso
if ($conn->connect_error) {
die("Falha na conexão: " . $conn->connect_error);
}
$sql = "SELECT * FROM Usuarios WHERE tag = '" . $_POST[tag] . "'";
$result = $conn->query($sql);
if ((!$result) || ($result->num_rows == 0)) { // Tag não cadastrada
echo "NOTAG\n";
$result->free();
$sql = "INSERT INTO Insercao (TAG) VALUES ('". $_POST[tag] .
"')";
$result = $conn->query($sql);
} else { // Tag existente, registra acesso
echo "OK\n";
$result->free();
$sql = "INSERT INTO Acessos (TAG) VALUES ('". $_POST[tag] .
"')";
$result = $conn->query($sql);}
$conn->close();
?>