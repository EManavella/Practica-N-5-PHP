<?php

$destinatario = "emanavella4444@gmail.com";
$remitente    = "no-reply@ejemplo.com";
$asunto       = "Prueba de correo en formato HTML";

$cuerpo = '
<html>
<head>
  <meta charset="UTF-8">
  <title>Prueba</title>
</head>
<body>
  <h1>Prueba de envío de correo</h1>
  <p>Esto es una <b>prueba</b> de envío de correo <i>en formato HTML</i>
     a través del servidor.</p>
  <ul>
    <li>Primer ítem</li>
    <li>Segundo ítem</li>
  </ul>
  <p>Más información en <a href="http://www.ejemplo.com">nuestro sitio</a>.</p>
</body>
</html>';

// Cabeceras: las dos primeras son las que convierten el correo en HTML.
$cabeceras  = "MIME-Version: 1.0\r\n";
$cabeceras .= "Content-type: text/html; charset=UTF-8\r\n";
$cabeceras .= "From: Mi sitio <" . $remitente . ">\r\n";
$cabeceras .= "Reply-To: " . $remitente . "\r\n";

$asunto_codificado = '=?UTF-8?B?' . base64_encode($asunto) . '?=';

if (mail($destinatario, $asunto_codificado, $cuerpo, $cabeceras)) {
    echo "El correo fue aceptado para su envío.";
} else {
    echo "No se pudo enviar el correo. Revisar la configuración de correo del servidor.";
}
?>