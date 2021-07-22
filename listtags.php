<?php
// Cria a conexão
$conn = new mysqli("127.0.0.1", "root", "", "test");
// Verifica a conexão
if ($conn->connect_error)
	die("Falha na conexão: " . $conn->connect_error);
// Encerra caso não consiga conectar
$sql = "SELECT tag FROM Usuarios";
$result = $conn->query($sql);
if ($result->num_rows > 0)
// Imprime cada linha da resposta, que deve conter uma tag
	while($row = $result->fetch_assoc())
		echo $row["tag"] . "\n";$conn->close();
	?>