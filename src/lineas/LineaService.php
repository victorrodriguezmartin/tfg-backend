
<?php

class LineaService extends Service
{
    // http://localhost/backend/server.php?request=getLineas
    public function get_lineas()
    {
        $sql = "SELECT * FROM linea";
        
        return $this->formatted_database_query($sql);
    }

    public function get_linea_by_codigo($codigo)
    {
        if (!isset($codigo) || empty($codigo))
            return $this->format_data(0, "MISSING PARAMETER");

        $sql = "SELECT * 
                FROM linea
                WHERE codigo LIKE '" . $codigo . "'";

        return $this->formatted_database_query($sql);
    }
}

?>
