
<?php

class ProductoService extends Service
{
    // http://localhost/backend/server.php?request=getProductos
    public function get_productos()
    {
        $sql = "SELECT * FROM producto";
        
        return $this->formatted_database_query($sql);
    }
}

?>
