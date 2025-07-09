<?php
// app/index.php

// Sanitizar la vista desde la URL
$vista = isset($_GET['view']) ? basename($_GET['view']) : 'crear_votacion';

// Lista blanca de vistas permitidas
$listaBlanca = ['crear_votacion', 'gestionar_planchas', 'gestionar_votaciones'];

// Ruta absoluta a las vistas admin
$rutaBase = __DIR__ . "/views/admin/";

// Verificamos si la vista está permitida
if (in_array($vista, $listaBlanca)) {
    $archivoVista = $rutaBase . $vista . ".php";

    if (file_exists($archivoVista)) {
        require_once $archivoVista;
    } else {
        echo "<h2>Error: La vista '$vista' no existe.</h2>";
    }
} else {
    echo "<h2>Error: Vista no permitida.</h2>";
}
