
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

    public function add_proceso_peso($params)
    {
        $sql = "INSERT INTO proceso_peso (`id_proceso`, `id_tolerancias`, `peso_produccion`,
                    `numero_unidades`, `peso_bobinas`, `peso_total_bobina`,
                    `numero_cubetas`, `peso_cubetas`, `peso_bobina_cubetas`,
                    `peso_objetivo`, `margen_sobrepeso`, `margen_subpeso`)
                VALUES ('" .
                $params["numProceso"] . "', '" .
                $params["tolerancias"] . "', '" .
                $params["pesoProduccion"] . "', '" .
                $params["numeroUnidades"] . "', '" .
                $params["pesoBobina"] . "', '" .
                $params["pesoTotalBobina"] . "', '" .
                $params["numeroCubetas"] . "', '" .
                $params["pesoCubeta"] . "', '" .
                $params["pesoBobinaCubeta"] . "', '" .
                $params["pesoUnitarioObjetivo"] . "', '" .
                $params["margenSobrepeso"] . "', '" .
                $params["margenSubpeso"] . "')";

        return $this->formatted_database_query($sql);
    }

    public function add_tolerancias($params)
    {
        $sql = "INSERT INTO tolerancias (`rango_1`, `rango_2`, `rango_3`,
                    `rango_4`, `rango_5`, `rango_6`, `rango_7`) VALUES('" .
                    $params["tolerancia1"] . "', '" .
                    $params["tolerancia2"] . "', '" .
                    $params["tolerancia3"] . "', '" .
                    $params["tolerancia4"] . "', '" .
                    $params["tolerancia5"] . "', '" .
                    $params["tolerancia6"] . "', '" .
                    $params["tolerancia7"] . "')";

        return $this->formatted_database_query($sql);
    }
}

?>