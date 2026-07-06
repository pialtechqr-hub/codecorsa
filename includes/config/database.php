<?php

function conectarDB() : mysqli {

    // Si existen variables de entorno (Railway/Render en producción), se usan esas.
    // Si no existen (XAMPP local), se usan los valores por defecto de siempre.
    $host = getenv('MYSQLHOST') ?: '127.0.0.1';
    $user = getenv('MYSQLUSER') ?: 'root';
    $pass = getenv('MYSQLPASSWORD') ?: '';
    $name = getenv('MYSQLDATABASE') ?: 'codecorsa';
    $port = getenv('MYSQLPORT') ?: 3307;

    $db = mysqli_init();
    $conectado = @mysqli_real_connect($db, $host, $user, $pass, $name, (int) $port);

    if(!$conectado) {
        echo "Error no se pudo conectar: " . mysqli_connect_error();
        exit;
    }

    return $db;
}