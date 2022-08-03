
<?php

class LineaService extends Service
{
    // http://localhost/backend/server.php?request=getLineas
    public function get_lineas()
    {
        $sql = "SELECT * FROM linea";
        
        return $this->formatted_database_query($sql);
    }
}

?>
