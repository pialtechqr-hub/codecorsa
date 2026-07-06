<?php

require '../includes/app.php';
estaAutenticado(); // PROTECCIÓN

// 🔥 ELIMINAR PRODUCTO
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if($id) {

        // Obtener imagen
        $stmt = mysqli_prepare($db, "SELECT imagen FROM productos WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $producto = mysqli_fetch_assoc($resultado);

        // Eliminar imagen
        if($producto && file_exists('../imagenes/' . $producto['imagen'])) {
            unlink('../imagenes/' . $producto['imagen']);
        }

        // Eliminar registro
        $stmtDelete = mysqli_prepare($db, "DELETE FROM productos WHERE id = ?");
        mysqli_stmt_bind_param($stmtDelete, 'i', $id);
        mysqli_stmt_execute($stmtDelete);

        header('Location: /admin/');
        exit;
    }
}

// 🔥 CONSULTAR PRODUCTOS
$query = "SELECT productos.*, categorias.nombre AS categoria_nombre
FROM productos
LEFT JOIN categorias ON productos.categoria_id = categorias.id";

$resultado = mysqli_query($db, $query);

incluirTemplate('header');
?>

<main class="contenedor admin-panel">

    <!-- 🔥 HEADER -->
    <div class="admin-header">
        <h1>Administrador de Productos</h1>

        <div class="admin-acciones">
            <a href="/admin/productos/crear.php" class="boton">
                +Agregar
            </a>

        </div>
    </div>

    <!-- 🔥 TABLA -->
    <div class="tabla-responsive">
        <table class="tabla-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php while($producto = mysqli_fetch_assoc($resultado)): ?>
                <tr>

                    <td><?php echo $producto['id']; ?></td>

                    <td>
                        <img class="img-admin" src="/imagenes/<?php echo $producto['imagen']; ?>">
                    </td>

                    <td class="nombre-prod"><?php echo $producto['nombre']; ?></td>

                    <td class="precio-admin">S/. <?php echo $producto['precio']; ?></td>

                    <td>
                        <?php echo $producto['categoria_nombre'] ?? 'Sin categoría'; ?>
                    </td>

                    <td>
                        <span class="stock <?php echo $producto['stock'] <= 5 ? 'bajo' : ''; ?>">
                            <?php echo $producto['stock']; ?>
                        </span>
                    </td>

                    <td class="acciones-admin">

                        <a href="/admin/productos/actualizar.php?id=<?php echo $producto['id']; ?>" class="btn-editar">
                            Editar
                        </a>

                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                            <input type="submit" value="Eliminar" class="btn-eliminar">
                        </form>

                    </td>

                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</main>

<?php
incluirTemplate('footer');