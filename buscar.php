<?php

require 'includes/app.php';

$busqueda = $_GET['busqueda'] ?? '';
$busqueda = trim($busqueda);

$productos = [];

if($busqueda !== '') {

    $busqueda = mysqli_real_escape_string($db, $busqueda);

    $query = "SELECT * FROM productos 
              WHERE nombre LIKE '%${busqueda}%' 
              OR descripcion LIKE '%${busqueda}%'";

    $resultado = mysqli_query($db, $query);

    while($producto = mysqli_fetch_assoc($resultado)) {
        $productos[] = $producto;
    }
}

incluirTemplate('header');
?>

<main class="contenedor">
    <h1>Resultados para: "<?php echo htmlspecialchars($busqueda); ?>"</h1>

    <?php if(empty($productos)): ?>
        <p>No se encontraron resultados</p>
    <?php else: ?>

        <div class="productos">
            <?php foreach($productos as $producto): ?>

                <div class="producto">

                    <img src="/imagenes/<?php echo $producto['imagen']; ?>">

                    <div class="contenido-producto">
                        <h3><?php echo $producto['nombre']; ?></h3>
                        <p class="descripcion"><?php echo $producto['descripcion']; ?></p>
                        <p class="precio">$<?php echo $producto['precio']; ?></p>

                        <a href="/producto.php?id=<?php echo $producto['id']; ?>" class="boton">
                            Ver Producto
                        </a>
                    </div>

                </div>

            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</main>

<?php
incluirTemplate('footer');