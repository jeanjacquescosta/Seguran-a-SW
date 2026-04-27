<?php
// ==============================
// BLOQUEAR ACESSO DIRETO
// ==============================

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["logi"], $_POST["senh"])) {
    header("Location: login.php");
    exit();
}

// ==============================
// FUNÇÃO LIMPEZA
// ==============================

function limpar_input($dados) {
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');
    return $dados;
}

// ==============================
// VARIÁVEIS
// ==============================

$erros = [];
$username = "";
$password = "";

// ==============================
// VALIDAÇÃO
// ==============================

// LOGIN
if (empty($_POST["logi"])) {
    $erros[] = "LOGIN é obrigatório.";
} else {
    $username = limpar_input($_POST["logi"]);
    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $erros[] = "LOGIN contém caracteres inválidos.";
    }
}

// PASSWORD
if (empty($_POST["senh"])) {
    $erros[] = "SENHA é obrigatória.";
} else {
    $password = limpar_input($_POST["senh"]);
}

// ==============================
// LIGAÇÃO À BD
// ==============================

$conn = new mysqli("localhost", "root", "", "seguranca");

if ($conn->connect_error) {
    die("Erro de ligação a BD: " . $conn->connect_error);
}

// ==============================
// AUTENTICAÇÃO COM BCRYPT
// ==============================

if (empty($erros)) {

    // Prepared statement (seguro)
    $stmt = $conn->prepare("SELECT password FROM utilizadores WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {

        $stmt->bind_result($hash);
        $stmt->fetch();

        // 🔐 Verificação BCrypt
        if (!password_verify($password, $hash)) {
            $erros[] = "AUTENTICAÇÃO inválida.";
        }

    } else {
        $erros[] = "UTILIZADOR não existe.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Resultado de AUTENTICAÇÃO</title>
</head>
<body>

<h2>Resultado</h2>

<?php
if (empty($erros)) {
    echo "<p style='color:green;'>AUTENTICAÇÃO efetuada com sucesso!</p>";
} else {
    foreach ($erros as $erro) {
        echo "<p style='color:red;'>$erro</p>";
    }
}
?>

<br>
<a href="login.php">Voltar a AUTENTICAÇÃO</a>

</body>
</html>

<?php
$conn->close();
?>