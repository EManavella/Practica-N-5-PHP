<?php
/* Termina la sesión del visitante: borra las variables de sesión y destruye la
   sesión, así el contador vuelve a cero. Después vuelve a la página 1. */
session_start();
$_SESSION = array();
session_destroy();
header('Location: Pagina1.php');
exit;