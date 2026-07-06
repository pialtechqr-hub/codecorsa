<?php

require 'includes/app.php';

// Validar ID
$id = $_GET['id'] ?? null;
$id = filter_var($id, FILTER_VALIDATE_INT);

if(!$id) {
    header('Location: /');
    exit;
}

// Consultar categoría
$stmtCat = mysqli_prepare($db, "SELECT * FROM categorias WHERE id = ?");
mysqli_stmt_bind_param($stmtCat, 'i', $id);
mysqli_stmt_execute($stmtCat);
$resCat = mysqli_stmt_get_result($stmtCat);
$categoria = mysqli_fetch_assoc($resCat);

if(!$categoria) {
    header('Location: /');
    exit;
}

// Consultar productos de esa categoría
$stmt = mysqli_prepare($db, "SELECT * FROM productos WHERE categoria_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

incluirTemplate('header');
?>

<main class="contenedor">
    <h1><?php echo $categoria['nombre']; ?></h1>

    <?php if(mysqli_num_rows($resultado) === 0): ?>
        <p>No hay productos en esta categoría</p>
    <?php else: ?>

        <div class="productos">
            <?php include 'includes/templates/productos.php'; ?>
        </div>

    <?php endif; ?>

</main>

<?php
incluirTemplate('footer');