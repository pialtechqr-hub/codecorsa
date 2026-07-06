<?php
// INICIAR SESIÓN DE FORMA SEGURA
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CONTADOR DEL CARRITO
$totalItems = 0;

if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $producto) {
        $totalItems += isset($producto['cantidad']) ? (int)$producto['cantidad'] : 1;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CODECORSA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/build/css/app.css">
</head>

<body>

<header class="header" id="siteHeader">

    <!-- HEADER PRINCIPAL -->
    <div class="header-top contenedor">

        <!-- LOGO -->
        <div class="logo">
            <a class="img-logo" href="/">
                <img src="/build/img/logos/logo.svg" alt="Logo Codecorsa">
            </a>
        </div>

       <!-- 🛒 CARRITO -->
        <div class="acciones">
            <a href="/carrito.php" id="btnCarrito" class="carrito">
                🛒 <span id="contadorCarrito">(<?php echo $totalItems; ?>)</span>
            </a>
        </div>

        <!-- BUSCADOR -->
        <div class="buscador">
            <input 
                type="text" 
                id="buscadorInput"
                placeholder="Busca productos, marcas y más..."
                autocomplete="off"
            >
            <div id="resultadosBusqueda" class="resultados-busqueda"></div>
        </div>

        
 <!-- CATEGORÍAS (DESKTOP) -->

        <!-- HAMBURGUESA MOBILE -->
         <div>
            <div class="menu-toggle" id="menu-toggle">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 6H25" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    <path d="M1 12H25" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    <path d="M1 18H25" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

         </div>
        

    </div>

    <!-- MEGA MENÚ -->
   <div class="menu-categorias">

    <ul class="menu-horizontal">

        <li>
    <a href="/categoria.php?id=1" class="menu-padre">Roller</a>
    <ul class="submenu">
        <li><a href="/producto.php?id=1">Roller Blackout</a></li>
        <li><a href="/producto.php?id=2">Roller Screen</a></li>
    </ul>
        </li>

        <li>
            <a href="/categoria.php?id=2" class="menu-padre">Cortinas</a>
            <ul class="submenu">
                <li><a href="/producto.php?id=3">Ripple Flod Motorizado</a></li>
                <li><a href="/producto.php?id=4">Barra de Acero</a></li>
                <li><a href="/producto.php?id=4">Estores Plegables</a></li>
            </ul>
        </li>

        <li>
            <a href="/categoria.php?id=3" class="menu-padre">Puertas</a>
            <ul class="submenu">
                <li><a href="/producto.php?id=3">Puerta de Ducha</a></li>
                <li><a href="/producto.php?id=3">Plegable PVC</a></li>
            </ul>
        </li>

        <li>
            <a href="/categoria.php?id=4" class="menu-padre">Toldo Retráctil</a>
            <ul class="submenu">
                <li><a href="/producto.php?id=8">Toldo Motorizado</a></li>
            </ul>
        </li>

    </ul>

</div>

    <!-- MENÚ PRINCIPAL -->
    <div class="menu-bar">
        <nav id="menu" class="contenedor">
            <!-- MOBILE CATEGORÍAS -->
            <div class="categorias-mobile">

                <a href="/categoria.php?id=1" class="grupo-cat">Roller</a>
                <a href="/producto.php?id=1">Roller Blackout</a>
                <a href="/producto.php?id=2">Roller Screen</a>

                <a href="/categoria.php?id=2" class="grupo-cat">Cortinas</a>
                <a href="/producto.php?id=2">Riple Flod Motorizado</a>
                <a href="/producto.php?id=4">Barra de Acero</a>
                <a href="/producto.php?id=4">Estores Plegables</a>

                <a href="/categoria.php?id=3" class="grupo-cat">Puertas</a>
                <a href="/producto.php?id=3">Puerta de Ducha</a>
                <a href="/producto.php?id=3">Plegable PVC</a>

                <a href="/categoria.php?id=4" class="grupo-cat">Toldo Retráctil</a>
                <a href="/producto.php?id=8">Toldo Motorizado</a>
            </div>

            <?php if(isset($_SESSION['login']) && $_SESSION['login']): ?>
                <a href="/admin">Admin</a>
                <a href="/logout.php">Cerrar Sesión</a>
            <?php endif; ?>

        </nav>
    </div>

</header>

<!-- 🔥 MINI CARRITO -->
<div id="miniCarrito" class="mini-carrito">

    <div class="mini-header">
        <h3>🛒 Tu carrito</h3>
        <span id="cerrarMini">✖</span>
    </div>

    <div id="miniContenido" class="mini-contenido">
        <p>Tu carrito está vacío</p>
    </div>

    <a href="/carrito.php" class="boton">
        Ver carrito
    </a>

</div>

<!-- 🔥 MODAL -->
<div id="modalCarrito" class="modal-carrito">
    <div class="modal-contenido">

        <h3>Producto agregado 🛒</h3>
        <p id="productoAgregado"></p>

        <div class="modal-botones">
            <button id="seguirComprando" class="boton-secundario">
                Seguir comprando
            </button>

            <a href="/carrito.php" class="boton">
                Ir al carrito
            </a>
        </div>

    </div>
</div>

<!-- OVERLAY -->
<div id="overlayCarrito" class="overlay-carrito"></div>