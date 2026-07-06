<?php
session_start();
require 'includes/app.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id']);

    $query = "SELECT * FROM productos WHERE id = $id";
    $resultado = mysqli_query($db, $query);
    $producto = mysqli_fetch_assoc($resultado);

    if(!$producto){
        echo json_encode(['ok'=>false]);
        exit;
    }

    if(!isset($_SESSION['carrito'])){
        $_SESSION['carrito'] = [];
    }

    if(isset($_SESSION['carrito'][$id])){
        $_SESSION['carrito'][$id]['cantidad']++;
    } else {
        $_SESSION['carrito'][$id] = [
            'nombre'=>$producto['nombre'],
            'precio'=>$producto['precio'],
            'imagen'=>$producto['imagen'],
            'cantidad'=>1
        ];
    }

    echo json_encode([
        'ok'=>true,
        'nombre'=>$producto['nombre']
    ]);
}