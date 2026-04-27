<?php
// Mostrar erros
if (!empty($erros)) {
    foreach ($erros as $erro) {
        echo "<p style='color:red;'>$erro</p>";
    }
}
?>