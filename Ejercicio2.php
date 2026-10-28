<?php
/* ============================================================================
   PRÁCTICA N°5 - PHP: función mail(), variables de sesión | EJERCICIO 2
   Consigna: confeccionar una página de Contacto que presente un formulario
   para que los visitantes puedan enviar consultas al webmaster.

   CÓMO FUNCIONA
   Es una página que se procesa a sí misma (como el Documento 2 de la Práctica 4):
   - Si se entra por primera vez (método GET), muestra el formulario.
   - Si se envía (método POST), valida los datos. Si hay errores, vuelve a
     mostrar el formulario con los mensajes y con los datos ya escritos. Si
     está todo bien, arma el correo, lo envía al webmaster con mail() y
     muestra un mensaje de confirmación.
   - Validaciones: los campos obligatorios no pueden estar vacíos y el correo
     del visitante tiene que tener un formato válido.
   - Seguridad (ver mail_comun.php): los datos que van a las cabeceras se
     limpian de saltos de línea (inyección de cabeceras) y todo lo que se
     muestra se escapa con h() (XSS).
   - El correo sale desde una dirección fija del sitio (EMAIL_REMITENTE) y se
     usa Reply-To con el correo del visitante: así el webmaster responde
     directo al visitante y el servidor no "falsifica" un remitente ajeno.
   - Como el formulario no tiene atributo action, se envía a la misma página.
   - Un formulario público puede recibir spam; en un sitio real conviene sumar
     un captcha o un límite de envíos.

   ANTES DE USAR: configurar las constantes de mail_comun.php.
   ============================================================================ */

require_once 'Mail comun.php';

$errores  = array();
$enviado  = false;
$nombre   = '';
$email    = '';
$asunto   = '';
$consulta = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = limpiar_cabecera(valor_post('nombre'));
    $email    = limpiar_cabecera(valor_post('email'));
    $asunto   = limpiar_cabecera(valor_post('asunto'));
    $consulta = trim(valor_post('consulta'));

    if ($nombre === '')   { $errores[] = 'Ingresá tu nombre.'; }
    if ($email === '')    { $errores[] = 'Ingresá tu correo electrónico.'; }
    elseif (!email_valido($email)) { $errores[] = 'El correo electrónico no es válido.'; }
    if ($asunto === '')   { $errores[] = 'Ingresá el asunto.'; }
    if ($consulta === '') { $errores[] = 'Escribí tu consulta.'; }

    if (count($errores) === 0) {
        // Cuerpo en texto plano, con las líneas cortadas a 70 caracteres.
        $cuerpo  = "Consulta recibida desde el sitio " . SITIO_NOMBRE . "\r\n\r\n";
        $cuerpo .= "Nombre: " . $nombre . "\r\n";
        $cuerpo .= "Correo: " . $email . "\r\n";
        $cuerpo .= "Asunto: " . $asunto . "\r\n\r\n";
        $cuerpo .= "Consulta:\r\n" . wordwrap($consulta, 70, "\r\n", true);

        $cabeceras  = "MIME-Version: 1.0\r\n";
        $cabeceras .= "Content-type: text/plain; charset=UTF-8\r\n";
        $cabeceras .= "From: " . codificar_mime(SITIO_NOMBRE) . " <" . EMAIL_REMITENTE . ">\r\n";
        $cabeceras .= "Reply-To: " . $email . "\r\n";

        $enviado = mail(EMAIL_WEBMASTER, codificar_mime('[Contacto] ' . $asunto), $cuerpo, $cabeceras);
        if (!$enviado) {
            $errores[] = 'No se pudo enviar la consulta. Intentá nuevamente más tarde.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Contacto</title>
</head>
<body>
<h1>Contacto</h1>

<?php if ($enviado) { ?>
    <p><b>¡Gracias, <?php echo h($nombre); ?>!</b> Tu consulta fue enviada. Te vamos a responder a <?php echo h($email); ?>.</p>
<?php } else { ?>
    <p>Completá el formulario para enviar una consulta al webmaster. Todos los campos son obligatorios.</p>

    <?php if (count($errores) > 0) { ?>
        <ul style="color:#b00000;">
        <?php foreach ($errores as $error) { ?>
            <li><?php echo h($error); ?></li>
        <?php } ?>
        </ul>
    <?php } ?>

    <form method="post">
        <p>
            <label>Nombre:<br>
            <input type="text" name="nombre" size="40" value="<?php echo h($nombre); ?>"></label>
        </p>
        <p>
            <label>Correo electrónico:<br>
            <input type="text" name="email" size="40" value="<?php echo h($email); ?>"></label>
        </p>
        <p>
            <label>Asunto:<br>
            <input type="text" name="asunto" size="40" value="<?php echo h($asunto); ?>"></label>
        </p>
        <p>
            <label>Consulta:<br>
            <textarea name="consulta" rows="8" cols="50"><?php echo h($consulta); ?></textarea></label>
        </p>
        <p>
            <input type="submit" name="enviar" value="Enviar consulta">
            <input type="reset" value="Borrar">
        </p>
    </form>
<?php } ?>
</body>
</html>