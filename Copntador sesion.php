<?php

session_start();

$pagina_actual = basename($_SERVER['SCRIPT_NAME']);

if (!isset($_SESSION['total_paginas'])) {
    $_SESSION['total_paginas'] = 0;
    $_SESSION['por_pagina']    = array();
}
$_SESSION['total_paginas']++;

if (!isset($_SESSION['por_pagina'][$pagina_actual])) {
    $_SESSION['por_pagina'][$pagina_actual] = 0;
}
$_SESSION['por_pagina'][$pagina_actual]++;

function mostrar_contador() {
    echo '<p><b>Páginas visitadas en esta sesión: ' . (int)$_SESSION['total_paginas'] . '</b></p>';
    echo '<ul>';
    foreach ($_SESSION['por_pagina'] as $pagina => $veces) {
        echo '<li>' . htmlspecialchars($pagina, ENT_QUOTES, 'UTF-8') . ': ' . (int)$veces . ($veces == 1 ? ' vez' : ' veces') . '</li>';
    }
    echo '</ul>';
}