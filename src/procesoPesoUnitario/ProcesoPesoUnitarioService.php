<?php

class ProcesoPesoUnitarioService extends Service
{
    public function get_procesos_peso_unitario_by_proceso_id($params)
    {
        $sql = "SELECT peso FROM proceso_peso_unitario
                WHERE id_proceso_peso LIKE '" . $params["id"]. "'";
        
        return $this->formatted_database_query($sql);
    }

    public function insert_peso_unitario($params)
    {
        $sql = "INSERT INTO proceso_peso_unitario (`id_proceso_peso`, `peso`)" .
               " VALUES ('" . $params["id_proceso_peso"] . "', '" . $params["peso"] . "')";

        return $this->formatted_database_query($sql);
    }
}

?>