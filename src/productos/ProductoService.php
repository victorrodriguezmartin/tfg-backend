
<?php

class ProductoService extends Service
{
    // http://localhost/backend/server.php?request=getProductos
    public function get_productos()
    {
        $sql = "SELECT * FROM producto";
        
        return $this->formatted_database_query($sql);
    }

    public function get_producto_by_codigo($codigo)
    {
        if (!isset($codigo) || empty($codigo))
            return $this->format_data(0, "MISSING PARAMETER");

        $sql = "SELECT *
                FROM producto
                WHERE codigo LIKE '" . $codigo . "'";

        return $this->formatted_database_query($sql);
    }
}

?>
