<?php
// ==============================
// PROCESSAR REGISTO
// ==============================


$erros = [];


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // Receber dados
    $username = $_POST["logi"];
    $password = $_POST["senh"];
    $confirmar = $_POST["conf_senh"];


    // ==============================
    // VALIDAÇÃO
    // ==============================


    if (empty($username)) {
        $erros[] = "Username é obrigatório.";
    }


    if (empty($password)) {
        $erros[] = "Password é obrigatória.";
    }


    if ($password !== $confirmar) {
        $erros[] = "As passwords não coincidem.";
    }


    // ==============================
    // SE NÃO HOUVER ERROS
    // ==============================


    if (empty($erros)) {


        // 🔐 HASH DA PASSWORD (BCrypt)
        $hash = password_hash($password, PASSWORD_BCRYPT);


        // Ligação à BD
        $conn = new mysqli("localhost", "root", "", "seguranca");


        if ($conn->connect_error) {
            die("Erro de ligação: " . $conn->connect_error);
        }


        // Inserir utilizador (seguro)
        $stmt = $conn->prepare("INSERT INTO utilizadores (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hash);


        if ($stmt->execute()) {
            echo "<p style='color:green;'>Utilizador registado com sucesso!</p>";
        } else {
            echo "<p style='color:red;'>Erro ao registar utilizador.</p>";
        }


        $stmt->close();
        $conn->close();
    }
}
?>