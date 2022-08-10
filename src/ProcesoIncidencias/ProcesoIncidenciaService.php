
<?php

class ProcesoIncidenciaService extends Service
{
    public function get_proceso_incidencias()
    {
        $sql = "SELECT * FROM proceso_incidencia";
        return $this->formatted_database_query($sql);
    }

    public function get_proceso_incidencias_by_id($params)
    {
        $sql = "SELECT * 
               FROM proceso_incidencia
               WHERE id_proceso LIKE " . $params["id"];

        return $this->formatted_database_query($sql);
    }

    public function add_proceso_incidencia($params)
    {
        $sql = "INSERT INTO proceso_incidencia (`id_proceso`, `descripcion`,
                `hora_parada`, `hora_reinicio`) VALUES(" .
                $params['idProceso'] . ", '" .
                $params['descripcion'] . "', " .
                "(SELECT CONVERT('" . $params['horaParada'] . "', time)), " .
                "(SELECT CONVERT('" . $params['horaReinicio'] . "', time)))";

        return $this->formatted_database_query($sql);
    }
}

?>
