<?php

require '../../includes/app.php';
estaAutenticado();

$errores = [];

// Valores por defecto
$nombre = '';
$precio = '';
$precio_original = '';
$descripcion = '';
$categoria = '';
$categoria_id = '';
$stock = '';
$caracteristicas = '';

// Obtener categorías
$categorias = mysqli_query($db, "SELECT * FROM categorias");

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = mysqli_real_escape_string($db, trim($_POST['nombre'] ?? ''));
    $precio = mysqli_real_escape_string($db, trim($_POST['precio'] ?? ''));
    $precio_original = mysqli_real_escape_string($db, trim($_POST['precio_original'] ?? ''));
    $descripcion = mysqli_real_escape_string($db, trim($_POST['descripcion'] ?? ''));
    $categoria = mysqli_real_escape_string($db, $_POST['categoria'] ?? '');
    $categoria_id = $_POST['categoria_id'] ?? '';
    $stock = mysqli_real_escape_string($db, trim($_POST['stock'] ?? ''));
    $caracteristicas = mysqli_real_escape_string($db, trim($_POST['caracteristicas'] ?? ''));

    $imagenPrincipal = $_FILES['imagen_principal'];
    $imagenes = $_FILES['imagenes'];

    // Validaciones
    if($nombre === '') $errores[] = "El nombre es obligatorio";

    // 🔥 El precio obligatorio es el ORIGINAL. El de oferta es opcional.
    if($precio_original === '' || !is_numeric($precio_original)) {
        $errores[] = "El precio es obligatorio";
    }

    // Si pusieron precio de oferta, debe ser numérico
    if($precio !== '' && !is_numeric($precio)) {
        $errores[] = "El precio de oferta debe ser un número válido";
    }

    if($descripcion === '') $errores[] = "La descripción es obligatoria";
    if(!$imagenPrincipal['name']) $errores[] = "La imagen principal es obligatoria";
    if(!$categoria_id) $errores[] = "La categoría es obligatoria";

    // Si no se puso precio de oferta, se guarda vacío (sin oferta)
    if($precio === '') $precio = '0';

    if(empty($errores)) {

        $carpetaImagenes = '../../imagenes/';
        if(!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }

        // 🔥 IMAGEN PRINCIPAL
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

        move_uploaded_file(
            $imagenPrincipal['tmp_name'],
            $carpetaImagenes . $nombreImagen
        );

        // 🔥 INSERT PRODUCTO
        $query = "INSERT INTO productos 
        (nombre, precio, precio_original, imagen, descripcion, categoria, categoria_id, stock, caracteristicas, creado)
        VALUES 
        ('$nombre', '$precio', '$precio_original', '$nombreImagen', '$descripcion', '$categoria', '$categoria_id', '$stock', '$caracteristicas', NOW())";

        $resultado = mysqli_query($db, $query);

        if($resultado) {

            $producto_id = mysqli_insert_id($db);

            // 🔥 GALERÍA MÚLTIPLE
            if(!empty($imagenes['name'][0])) {

                for($i = 0; $i < count($imagenes['name']); $i++) {

                    if($imagenes['name'][$i]) {

                        $nombreImg = md5(uniqid(rand(), true)) . ".jpg";

                        move_uploaded_file(
                            $imagenes['tmp_name'][$i],
                            $carpetaImagenes . $nombreImg
                        );

                        $queryImg = "INSERT INTO producto_imagenes (producto_id, imagen)
                                     VALUES ($producto_id, '$nombreImg')";

                        mysqli_query($db, $queryImg);
                    }
                }
            }

            header('Location: /admin');
        }
    }
}

incluirTemplate('header');
?>

<main class="contenedor admin-form">

    <h1>Crear Producto</h1>

    <?php foreach($errores as $error): ?>
        <p class="alerta-error"><?php echo $error; ?></p>
    <?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data" class="form-admin">

        <fieldset>
            <legend>Información General</legend>

            <div class="campo">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?php echo $nombre; ?>">
            </div>

            <div class="campo-grid">

                <div class="campo">
                    <label>Precio Original</label>
                    <input type="number" step="0.01" name="precio_original" value="<?php echo $precio_original; ?>">
                </div>

                <div class="campo">
                    <label>Precio Oferta (opcional)</label>
                    <input type="number" step="0.01" name="precio" value="<?php echo $precio; ?>">
                </div>

            </div>

            <!-- 🔥 IMAGEN PRINCIPAL -->
            <div class="campo">
                <label>Imagen Principal</label>
                <input type="file" id="inputPrincipal" name="imagen_principal" accept="image/jpeg, image/png">

                <!-- preview -->
                <div id="previewPrincipal"></div>
            </div>

            <!-- 🔥 GALERÍA -->
            <div class="campo">
                <label>Imágenes adicionales (galería)</label>
                <input type="file" name="imagenes[]" id="inputGaleria" multiple accept="image/jpeg, image/png">

                <!-- preview -->
                <div id="previewGaleria" class="preview-galeria"></div>
            </div>

            <div class="campo">
                <label>Descripción</label>
                <textarea name="descripcion"><?php echo $descripcion; ?></textarea>
            </div>

        </fieldset>

        <fieldset>
            <legend>Características</legend>

            <div class="campo">
                <textarea name="caracteristicas"><?php echo $caracteristicas; ?></textarea>
            </div>

        </fieldset>

        <fieldset>
            <legend>Extras</legend>

            <div class="campo-grid">

                <div class="campo">
                    <label>Categoría</label>
                    <select name="categoria_id" required>
                        <option value="">-- Seleccionar --</option>

                        <?php while($cat = mysqli_fetch_assoc($categorias)): ?>
                            <option value="<?php echo $cat['id']; ?>"
                                <?php echo ($cat['id'] == $categoria_id) ? 'selected' : ''; ?>>
                                <?php echo $cat['nombre']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="campo">
                    <label>Stock</label>
                    <input type="number" name="stock" value="<?php echo $stock; ?>">
                </div>

            </div>

            <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">

        </fieldset>

        <input type="submit" value="Crear Producto" class="boton boton-full">

    </form>

</main>

<?php incluirTemplate('footer'); ?>