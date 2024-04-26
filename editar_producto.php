<?php
require_once "conexion.php";

$nombre = $descripcion = $precio = $categoria = "";
$nombre_err = $descripcion_err = $precio_err = $categoria_err = "";

if(isset($_POST["id"]) && !empty($_POST["id"])){

    $id = $_POST["id"];


    $input_nombre = trim($_POST["nombre"]);
    if(empty($input_nombre)){
        $nombre_err = "ingrese un nombre.";
    } elseif(!filter_var($input_nombre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $nombre_err = "ingrese un nombre válido.";
    } else{
        $nombre = $input_nombre;
    }


    $input_descripcion = trim($_POST["descripcion"]);
    if(empty($input_descripcion)){
        $descripcion_err = "ingrese una descripción.";
    } else{
        $descripcion = $input_descripcion;
    }


    $input_precio = trim($_POST["precio"]);
    if(empty($input_precio)){
        $precio_err = "ingrese el precio.";
    } elseif(!ctype_digit($input_precio)){
        $precio_err = "ingrese un valor numérico positivo.";
    } else{
        $precio = $input_precio;
    }

    $input_categoria = trim($_POST["categoria"]);
    if(empty($input_categoria)){
        $categoria_err = "ingrese una categoría.";
    } else{
        $categoria = $input_categoria;
    }

    if(empty($nombre_err) && empty($descripcion_err) && empty($precio_err) && empty($categoria_err)){
        $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, categoria=? WHERE id_producto=?";

        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ssisi", $param_nombre, $param_descripcion, $param_precio, $param_categoria, $param_id);

            $param_nombre = $nombre;
            $param_descripcion = $descripcion;
            $param_precio = $precio;
            $param_categoria = $categoria;
            $param_id = $id;

            if($stmt->execute()){
                header("location: index.php");
                exit();
            } 
        }

        $stmt->close();
    }

    $conn->close();
} else{
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id =  trim($_GET["id"]);

        $sql = "SELECT * FROM productos WHERE id_producto = ?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("i", $param_id);

            $param_id = $id;

            if($stmt->execute()){
                $result = $stmt->get_result();

                if($result->num_rows == 1){
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    $nombre = $row["nombre"];
                    $descripcion = $row["descripcion"];
                    $precio = $row["precio"];
                    $categoria = $row["categoria"];
                } else{

                    header("location: error.php");
                    exit();
                }

            } 
        }


        $stmt->close();


        $conn->close();
    }  else{

        header("location: error.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], textarea {
            width: 25%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .help-block {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
        .btn {
            padding: 10px 20px;
            cursor: pointer;
            color: #fff;
        }
        .btn-primary {
            background-color: #4CAF50;
        }
        .btn-default {
            background-color: #6c757d;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Editar producto</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
                <span class="help-block"><?php echo $nombre_err;?></span>
            </div>
            <div class="form-group <?php echo (!empty($descripcion_err)) ? 'has-error' : ''; ?>">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control"><?php echo $descripcion; ?></textarea>
                <span class="help-block"><?php echo $descripcion_err;?></span>
            </div>
            <div class="form-group <?php echo (!empty($precio_err)) ? 'has-error' : ''; ?>">
                <label>Precio</label>
                <input type="text" name="precio" class="form-control" value="<?php echo $precio; ?>">
                <span class="help-block"><?php echo $precio_err;?></span>
            </div>
            <div class="form-group <?php echo (!empty($categoria_err)) ? 'has-error' : ''; ?>">
                <label>Categoría</label>
                <input type="text" name="categoria" class="form-control" value="<?php echo $categoria; ?>">
                <span class="help-block"><?php echo $categoria_err;?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Enviar">
                <a href="index.php" class="btn btn-default">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>