<?php

require 'funciones.php';
require 'config/database.php';

$db = conectarDB();

function estaAutenticado() {
    session_start();

    if(!isset($_SESSION['login']) || !$_SESSION['login']) {
        header('Location: /login.php');
        exit;
    }
}