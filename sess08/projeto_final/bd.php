<?php
// ======================================
// Ligação à Base de Dados
// ======================================

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "seguranca_final"
);

if ($conn->connect_error) {
    die("Erro de ligação à BD");
}

?>