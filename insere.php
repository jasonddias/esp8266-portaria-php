<HTML><BODY>
	<?php
// Cria a conexão
	$conn = new mysqli("127.0.0.1", "root", "", "test");
// Verifica se foi criada com sucesso
	if ($conn->connect_error) {
		die("Falha na conexão: " . $conn->connect_error);
	}
if (!$_POST[Nome]) { // 1a Etapa: Se não tem os dados, verifica se uma nova
// tag foi lida...
	$sql = "SELECT * FROM Insercao
	WHERE DataHora > NOW() - INTERVAL 5 MINUTE
	ORDER BY DataHora DESC LIMIT 1";
// Recupera a última tag enviada
	$result = $conn->query($sql);if ((!$result) || ($result->num_rows == 0)) {
		echo "Não foi identificada nenhuma tag. Aproxime uma tag do
		leitor <BR>";
header("Refresh:3"); // Tag não lida... Recarrega a página a cada 3s
// até a leitura.
} else { // 2a Etapa: Tag lida, monta o formulário
	$row = $result->fetch_assoc();
	echo "<form action=insere.php method=POST>
	<p>Nome: <input type=text name=Nome /></p>
	<p>Documento: <input type=text name=Documento /></p>
	<p>Tag: <input type=text name=Tag value=" . $row['TAG'] . "
	/></p>
	<p><input type=submit /></p>
	</form>";
}
} else { // 3a Etapa: Se os dados já foram enviados, insere no	banco
	$sql = "INSERT INTO Usuarios VALUES('".$_POST['Tag']."','
	".$_POST[Nome]."','".$_POST[Documento]."')";
	$result = $conn->query($sql);
	if ($result)
		echo "Inserção realizada com sucesso<BR>";
}
?>
</body></html>