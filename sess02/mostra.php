<?php
if ($_SERVER["REQUEST_METHOD"] != "POST" || 
    !isset($_POST["nome"], $_POST["idade"], $_POST["genero"])) {        // Se não for POST OU se não existirem os campos redireciona
    
    header("Location: insere.php");                                     // redireciona para formulário de inserção dos dados
    exit();                                                             
}

function limpar_input($dados) {
    $dados = trim($dados);                                              // remove espaços no início/fim
    $dados = stripslashes($dados);                                      // remove barras invertidas
    $dados = htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');             // converte caracteres especiais (previne XSS)
    return $dados;
}

$erros = [];                                                            // vetor para guardar erros
$nome = $idade = $genero = "";

// ---- Nome ----
if (empty($_POST["nome"])) {                                            // verifica preenchimento
    $erros[] = "Nome é obrigatório.";
} else {
    $nome = limpar_input($_POST["nome"]);                               // limpar entrada
    if (strlen($nome) > 60) {                                           // verificar tamanho máximo de 60 caracteres
        $erros[] = "Nome não pode exceder 60 caracteres.";              
    }
    if (!preg_match("/^[\p{L} ]+$/u", $nome)) {                         // só letras (com acentos) e espaços | regex (whitelist)
        $erros[] = "Nome só pode conter letras e espaços.";            
    }
}

// ---- Idade ----
if (empty($_POST["idade"])) {                                            // verificar preenchimento
    $erros[] = "Idade é obrigatória.";
} else {
    $idade = filter_var($_POST["idade"], FILTER_VALIDATE_INT);           // validar inteiro
    if ($idade === false || $idade < 0 || $idade > 130) {                // verificar intervalo entre 0 e 130
        $erros[] = "Idade deve estar entre 0 e 130.";
    }
}


// ---- Género ----
if (empty($_POST["genero"])) {
    $erros[] = "Género é obrigatório.";                                 // verificar preenchimento
} else {
    $genero = limpar_input($_POST["genero"]);                           // limpar entrada
    if (!in_array($genero, ["m", "f"])) {                               // whitelist → só aceita m ou f
        $erros[] = "Género inválido.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>
<body>

<h2>Resultado</h2>

<?php
// ==============================
// APRESENTAÇÃO DE RESULTADOS
// ==============================

// Se não houver erros
if (empty($erros)) {

    // definir saudação com base no género
    if ($genero == "m") {
        $saudacao = "Caro";
    } elseif ($genero == "f") {
        $saudacao = "Cara";
    } else {
        $saudacao = "Caro/a";
    }

    // mostrar resultado
    echo "<p>$saudacao $nome, tem $idade anos.</p>";

} else {
    // mostrar todos os erros
    foreach ($erros as $erro) {
        echo "<p style='color:red;'>$erro</p>";
    }
}
?>

<br>

<!-- Link para voltar ao formulário -->
<a href="insere.php">Voltar</a>

</body>
</html>