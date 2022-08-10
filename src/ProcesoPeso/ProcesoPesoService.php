
<?php

class ProcesoPesoService extends Service
{
    public function get_proceso_pesos()
    {
        $sql = "SELECT * FROM proceso_peso";
        return $this->formatted_database_query($sql);
    }

    public function get_proceso_pesos_by_id($params)
    {
        $sql = "SELECT * " .
               "FROM proceso_incidencia " .
               "WHERE id_proceso LIKE " . $params["id"];

        return $this->formatted_database_query($sql);
    }
}

?>