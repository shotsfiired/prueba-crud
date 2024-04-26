<!DOCTYPE html>
<html>
<head>
    <title>Prueba de CRUD completo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-bottom: 20px;
        }

        form input[type="text"], form input[type="submit"] {
            padding: 8px;
            margin-right: 10px;
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php
    require_once "conexion.php";

    function crearProducto($nombre, $descripcion, $precio, $categoria) {
        global $conn;
        $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria) VALUES ('$nombre', '$descripcion', $precio, '$categoria')";
        if ($conn->query($sql) === TRUE) {
            echo "" . $conn->error;
        }
    }

    function mostrarProductos() {
        global $conn;
        $sql = "SELECT * FROM productos";
        $result = $conn->query($sql);

        echo "<table>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Categoría</th><th></th></tr>";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_producto"] . "</td>";
                echo "<td>" . $row["nombre"] . "</td>";
                echo "<td>" . $row["descripcion"] . "</td>";
                echo "<td>" . $row["precio"] . "</td>";
                echo "<td>" . $row["categoria"] . "</td>";
                echo "<td><a href='editar_producto.php?id=" . $row["id_producto"] . "'>Editar</a> | <a href='eliminar_producto.php?id=" . $row["id_producto"] . "'>Eliminar</a></td>";
                echo "</tr>";
            }
        } 
        echo "</table>";
    }

    function mostrarProductoPorID($id) {
        global $conn;
        $sql = "SELECT * FROM productos WHERE id_producto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<table>";
            echo "<tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Categoría</th><th></th></tr>";
            echo "<tr>";
            echo "<td>" . $row["id_producto"] . "</td>";
            echo "<td>" . $row["nombre"] . "</td>";
            echo "<td>" . $row["descripcion"] . "</td>";
            echo "<td>" . $row["precio"] . "</td>";
            echo "<td>" . $row["categoria"] . "</td>";
            echo "<td><a href='editar_producto.php?id=" . $row["id_producto"] . "'>Editar</a> | <a href='eliminar_producto.php?id=" . $row["id_producto"] . "'>Eliminar</a></td>";
            echo "</tr>";
            echo "</table>";
        } else {
            echo "No hay productos con este id.";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST["nombre"];
        $descripcion = $_POST["descripcion"];
        $precio = $_POST["precio"];
        $categoria = $_POST["categoria"];
        crearProducto($nombre, $descripcion, $precio, $categoria);
    }
    ?>

    <h2>Agregar nuevo producto</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Nombre: <input type="text" name="nombre"><br>
        Descripción: <input type="text" name="descripcion"><br>
        Precio: <input type="text" name="precio"><br>
        Categoría: <input type="text" name="categoria"><br>
        <input type="submit" value="Agregar Producto">
    </form>

    <h2>Buscar producto por el id</h2>
    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        ID: <input type="text" name="id"><br>
        <input type="submit" value="Buscar">
    </form>

    <h2>Tabla de productos</h2>
    <?php
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        mostrarProductoPorID($_GET["id"]);
    } else {
        mostrarProductos();
    }
    ?>

    <?php $conn->close(); ?>
</body>
</html>
