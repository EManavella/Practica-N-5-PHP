<?php


define('SITIO_NOMBRE',    'Mi sitio');                 // nombre que se muestra en los correos
define('SITIO_URL',       'http://www.ejemplo.com/');  // dirección del sitio
define('EMAIL_WEBMASTER', 'webmaster@ejemplo.com');    // quien recibe las consultas
define('EMAIL_REMITENTE', 'no-reply@ejemplo.com');     // dirección desde la que "sale" el correo


function limpiar_cabecera($texto) {
    return trim(str_replace(array("\r", "\n"), ' ', $texto));
}

function email_valido($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function codificar_mime($texto) {
    return '=?UTF-8?B?' . base64_encode($texto) . '?=';
}


function h($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}


function valor_post($campo) {
    return isset($_POST[$campo]) ? $_POST[$campo] : '';
}
?>