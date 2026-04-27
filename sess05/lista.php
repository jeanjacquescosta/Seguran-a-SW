<?php
$conn = new mysqli("localhost", "root", "", "seguranca");
if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}
$result = $conn->query("SELECT texto FROM comentarios");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Lista de Comentários</title>
</head>
<body>
<h2>Comentários</h2>
<?php
while ($row = $result->fetch_assoc()) {
    $comentario = htmlspecialchars($row["texto"], ENT_QUOTES, 'UTF-8');         // PROTEÇÃO XSS
    echo "<p>$comentario</p>";
}
?>
<br>
<a href="comentario.php">Novo comentário</a>
</body>
</html>
<?php
$conn->close();
?>