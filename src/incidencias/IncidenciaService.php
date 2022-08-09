
<?php

class IncidenciaService extends Service
{
    public function add_incidencia($params)
    {
        $sql = "INSERT INTO incidencia_proceso (`id_proceso`, `descripcion`,
                `hora_parada`, `hora_reinicio`) VALUES('" .
                $params['idProceso'] . "', " .
                $params['descripcion'] . "', " .
                "(SELECT CONVERT('" . $params['horaParada'] . "', time)), " .
                "(SELECT CONVERT('" . $params['horaReinicio'] . "', time)))";

        return $this->formatted_database_query($sql);

    }

    public function get_incidencia($params)
    {
        $sql = "SELECT * " .
               "FROM incidencia_proceso " .
               "WHERE id_proceso LIKE " . $params["id"];

        return $this->formatted_database_query($sql);
    }
}

?>
