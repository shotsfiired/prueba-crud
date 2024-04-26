<?php
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    require_once "conexion.php";
    
    $sql = "DELETE FROM productos WHERE id_producto = ?";
    
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $param_id);
        
        $param_id = trim($_GET["id"]);
        
        if($stmt->execute()){
            header("location: index.php");
            exit();
        } else{
            echo "Error al intentar eliminar el producto.";
        }
    }
     
    $stmt->close();
    
    $conn->close();
} else{
    header("location: index.php");
    exit();
}
?>