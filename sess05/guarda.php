<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comentario = $_POST["comentario"];
    // Ligação à BD
    $conn = new mysqli("localhost", "root", "", "seguranca");
    if ($conn->connect_error) {
        die("Erro: " . $conn->connect_error);
    }
    // Guardar comentário (prepared statement)
    $stmt = $conn->prepare("INSERT INTO comentarios (texto) VALUES (?)");
    $stmt->bind_param("s", $comentario);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    // Redirecionar
    header("Location: lista.php");
    exit();
}
?>