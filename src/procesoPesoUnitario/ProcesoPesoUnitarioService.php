<?php

class ProcesoPesoUnitarioService extends Service
{
    public function insert_peso_unitario($params)
    {
        $sql = "INSERT INTO proceso_peso_unitario (`id_proceso_peso`, `peso`)" .
               " VALUES ('" . $params["id_proceso_peso"] . "', '" . $params["peso"] . "')";

        return $this->formatted_database_query($sql);
    }
}

?>