
<?php

class IncidenciaService extends Service
{
    public function add_incidencia($params)
    {
        // $sql = "INSERT INTO incidencia_proceso (`id_proceso`, `descripcion`,
        //             `hora_parada`, `hora_reinicio`) VALUES('" .
        //             $params['idProceso'] . "', " .
        //             $params['descripcion'] . "', " .
        //             $params['horaParada'] . "', " .
        //             $params['horaReinicio'] . "')";

        // return $this->formatted_database_query($sql);

    }

    public function get_incidencia($params)
    {
        $sql = "SELECT * " .
               "FROM incidencia_proceso " .
               "WHERE id_proceso LIKE " . $params["id_proceso"];

        return $this->formatted_database_query($sql);
    }
}

?>
