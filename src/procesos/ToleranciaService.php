<?php

class ToleranciaService extends Service
{
    public function add_tolerancia_return_data($params)
    {
        $tolerancias = array(
            "tolerancia_1" => $params["tolerancia1"],
            "tolerancia_2" => $params["tolerancia2"],
            "tolerancia_3" => $params["tolerancia3"],
            "tolerancia_4" => $params["tolerancia4"],
            "tolerancia_5" => $params["tolerancia5"],
            "tolerancia_6" => $params["tolerancia6"],
            "tolerancia_7" => $params["tolerancia7"],
        );

        $paramsTolerancia = $this->get_tolerancia_by_values($tolerancias);

        // SI NO EXISTE TOLERANCIA
        if (!isset($paramsTolerancia["data"]) || empty($paramsTolerancia["data"]))
        {
            $result = $this->insert_tolerancia($tolerancias);

            if ($result["success"] == 0)
                return $result;
         
            $paramsTolerancia = array(
                "id_tolerancias" => 1,
                "tolerancia_1" => $params["tolerancia1"],
                "tolerancia_2" => $params["tolerancia2"],
                "tolerancia_3" => $params["tolerancia3"],
                "tolerancia_4" => $params["tolerancia4"],
                "tolerancia_5" => $params["tolerancia5"],
                "tolerancia_6" => $params["tolerancia6"],
                "tolerancia_7" => $params["tolerancia7"]
            );
        }
        else $paramsTolerancia = $paramsTolerancia["data"][0];

        return $paramsTolerancia;
    }

    public function get_tolerancia_by_values($params)
    {
        $sql = "SELECT * FROM tolerancias " .
                " WHERE tolerancia_1 LIKE '" . $params["tolerancia_1"] . "' ".
                "  AND tolerancia_2 LIKE '" . $params["tolerancia_2"] . "' " .
                "  AND tolerancia_3 LIKE '" . $params["tolerancia_3"] . "' " .
                "  AND tolerancia_4 LIKE '" . $params["tolerancia_4"] . "' " .
                "  AND tolerancia_5 LIKE '" . $params["tolerancia_5"] . "' " .
                "  AND tolerancia_6 LIKE '" . $params["tolerancia_6"] . "' " .
                "  AND tolerancia_7 LIKE '" . $params["tolerancia_7"] . "'";

        return $this->formatted_database_query($sql);
    }

    // PARAMS = [ tolerancia_1, tolerancia_2, ..., tolerancia_7 ]
    public function insert_tolerancia($params)
    {
        $sql = "INSERT INTO tolerancias (`tolerancia_1`, `tolerancia_2`, `tolerancia_3`,
                    `tolerancia_4`, `tolerancia_5`, `tolerancia_6`, `tolerancia_7`) VALUES('" .
                    $params["tolerancia_1"] . "', '" .
                    $params["tolerancia_2"] . "', '" .
                    $params["tolerancia_3"] . "', '" .
                    $params["tolerancia_4"] . "', '" .
                    $params["tolerancia_5"] . "', '" .
                    $params["tolerancia_6"] . "', '" .
                    $params["tolerancia_7"] . "')";

        return $this->formatted_database_query($sql);
    }
}

?>