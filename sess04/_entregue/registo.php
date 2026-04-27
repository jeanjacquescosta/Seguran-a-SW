<?php
$erros = [];
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["logi"];
    $password = $_POST["senh"];
    $confirmar = $_POST["conf_senh"];

    // Validação
    if (empty($username)) {
        $erros[] = "Username é obrigatório.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $erros[] = "Username inválido.";
    }

    if (empty($password)) {
        $erros[] = "Password é obrigatória.";
    }

    if ($password !== $confirmar) {
        $erros[] = "As passwords não coincidem.";
    }

    // Se não houver erros
    if (empty($erros)) {

        // Hash da password (BCrypt)
        $hash = password_hash($password, PASSWORD_BCRYPT);

        // Ligação à BD
        $conn = new mysqli("localhost", "root", "", "seguranca");

        if ($conn->connect_error) {
            die("Erro: " . $conn->connect_error);
        }

        // Verificar se já existe utilizador com esta LOGIN
        $stmt = $conn->prepare("SELECT id FROM utilizadores WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erros[] = "Username já existe.";
        } else {

            // Inserir utilizador
            $stmt = $conn->prepare("INSERT INTO utilizadores (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hash);

            if ($stmt->execute()) {
                $sucesso = "Utilizador registado com sucesso!";
            } else {
                $erros[] = "Erro ao registar utilizador.";
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registo (BCrypt)</title>
</head>
<body>

<h2>Registo</h2>

<form method="post">
    Username:
    <input type="text" name="logi" required pattern="[a-zA-Z0-9_]+"><br>
    Password:
    <input type="password" name="senh" required><br>
    Confirmar Password:
    <input type="password" name="conf_senh" required><br>
    <input type="submit" value="Registar">
</form>
<hr>
<?php
if (!empty($sucesso)) {
    echo "<p style='color:green;'>$sucesso</p>";
}
foreach ($erros as $erro) {
    echo "<p style='color:red;'>$erro</p>";
}
?>
<hr>
<a href="login.php">Ir para Login</a>
</body>
</html>