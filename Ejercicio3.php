<?php

require_once 'Mail comun.php';

$errores      = array();
$enviado      = false;
$tu_nombre    = '';
$tu_email     = '';
$amigo_nombre = '';
$amigo_email  = '';
$mensaje      = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tu_nombre    = limpiar_cabecera(valor_post('tu_nombre'));
    $tu_email     = limpiar_cabecera(valor_post('tu_email'));
    $amigo_nombre = limpiar_cabecera(valor_post('amigo_nombre'));
    $amigo_email  = limpiar_cabecera(valor_post('amigo_email'));
    $mensaje      = trim(valor_post('mensaje'));

    if ($tu_nombre === '')    { $errores[] = 'Ingresá tu nombre.'; }
    if ($tu_email === '')     { $errores[] = 'Ingresá tu correo electrónico.'; }
    elseif (!email_valido($tu_email)) { $errores[] = 'Tu correo electrónico no es válido.'; }
    if ($amigo_nombre === '') { $errores[] = 'Ingresá el nombre de tu amigo.'; }
    if ($amigo_email === '')  { $errores[] = 'Ingresá el correo de tu amigo.'; }
    elseif (!email_valido($amigo_email)) { $errores[] = 'El correo de tu amigo no es válido.'; }

    if (count($errores) === 0) {
        $asunto = $tu_nombre . ' te recomienda visitar ' . SITIO_NOMBRE;

        $cuerpo  = '<html><head><meta charset="UTF-8"></head><body>';
        $cuerpo .= '<p>Hola ' . h($amigo_nombre) . ':</p>';
        $cuerpo .= '<p>' . h($tu_nombre) . ' (' . h($tu_email) . ') te recomienda visitar ';
        $cuerpo .= '<a href="' . h(SITIO_URL) . '">' . h(SITIO_NOMBRE) . '</a>.</p>';
        if ($mensaje !== '') {
            $cuerpo .= '<p>Te dejó este mensaje:</p>';
            $cuerpo .= '<blockquote>' . nl2br(h($mensaje)) . '</blockquote>';
        }
        $cuerpo .= '<p>Visitalo en <a href="' . h(SITIO_URL) . '">' . h(SITIO_URL) . '</a></p>';
        $cuerpo .= '</body></html>';

        $cabeceras  = "MIME-Version: 1.0\r\n";
        $cabeceras .= "Content-type: text/html; charset=UTF-8\r\n";
        $cabeceras .= "From: " . codificar_mime(SITIO_NOMBRE) . " <" . EMAIL_REMITENTE . ">\r\n";
        $cabeceras .= "Reply-To: " . $tu_email . "\r\n";

        $enviado = mail($amigo_email, codificar_mime($asunto), $cuerpo, $cabeceras);
        if (!$enviado) {
            $errores[] = 'No se pudo enviar la recomendación. Intentá nuevamente más tarde.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recomendar el sitio a un amigo</title>
</head>
<body>
<h1>Recomendar el sitio a un amigo</h1>

<?php if ($enviado) { ?>
    <p><b>¡Listo!</b> Le enviamos tu recomendación a <?php echo h($amigo_nombre); ?>.</p>
<?php } else { ?>
    <p>Completá los datos y le enviamos un correo a tu amigo con el enlace al sitio.</p>

    <?php if (count($errores) > 0) { ?>
        <ul style="color:#b00000;">
        <?php foreach ($errores as $error) { ?>
            <li><?php echo h($error); ?></li>
        <?php } ?>
        </ul>
    <?php } ?>

    <form method="post">
        <p>
            <label>Tu nombre:<br>
            <input type="text" name="tu_nombre" size="40" value="<?php echo h($tu_nombre); ?>"></label>
        </p>
        <p>
            <label>Tu correo electrónico:<br>
            <input type="text" name="tu_email" size="40" value="<?php echo h($tu_email); ?>"></label>
        </p>
        <p>
            <label>Nombre de tu amigo:<br>
            <input type="text" name="amigo_nombre" size="40" value="<?php echo h($amigo_nombre); ?>"></label>
        </p>
        <p>
            <label>Correo electrónico de tu amigo:<br>
            <input type="text" name="amigo_email" size="40" value="<?php echo h($amigo_email); ?>"></label>
        </p>
        <p>
            <label>Mensaje (opcional):<br>
            <textarea name="mensaje" rows="5" cols="50"><?php echo h($mensaje); ?></textarea></label>
        </p>
        <p>
            <input type="submit" name="enviar" value="Recomendar">
            <input type="reset" value="Borrar">
        </p>
    </form>
<?php } ?>
</body>
</html>