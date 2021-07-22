<html><body><form action=listacess.php method=POST>
	<p>Data Inicial: <input type=datetime-local name=DataI />
		Data Final: <input type=datetime-local name=DataF /></p>
		<p><input type=submit /></p>
	</form>
	<?php
	if ($_POST['DataI']) {
		$conn = new mysqli("127.0.0.1", "root", "", "test");
// Verifica conexão
		if ($conn->connect_error) {
			die("Falha na conexão: " . $conn->connect_error);
		}
		$sql = "SELECT Usuarios.Nome, Acessos.DataHora
		FROM Usuarios, Acessos
		WHERE Acessos.DataHora > '" . $_POST['DataI'] . "' AND
		Acessos.DataHora < '" . $_POST['DataF'] . "' AND
		Acessos.tag = Usuarios.TAG";
		echo $sql;
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
// Imprime os dados de cada linha da tabela
			echo "<TABLE><tr><th>Nome<th>Data e Hora";
			while($row = $result->fetch_assoc()) {
				echo "<tr><td>" . $row["Nome"] . "<td>" .
				$row["DataHora"] . "<BR>";
			}
		} else {
			echo "Resultado Vazio";
		}
		$conn->close();
	}
	?>
	</html></body>