
<?php

class ProcesoService extends Service
{
    private $lineaService;
    private $productoService;
    private $empleadoService;

    function __construct()
    {
        parent::__construct();

        $this->lineaService = new LineaService();
        $this->empleadoService = new EmpleadoService();
        $this->productoService = new ProductoService();
    }

    public function get_procesos()
    {
        $sql = "SELECT proc.id_proceso, proc.id_personalizado, proc.kilos_teoricos,
                       proc.kilos_reales, proc.hora_inicio, proc.hora_fin,
                       e.nombre AS jefe, l.codigo AS linea, prod.nombre AS producto
                    FROM proceso AS proc

                    INNER JOIN miembro_equipo AS me
                        ON me.id_miembro LIKE proc.id_jefe
                    INNER JOIN empleado AS e
                        ON e.id_empleado LIKE me.id_empleado
                    
                    INNER JOIN producto_linea AS pl
                        ON pl.id_producto_linea LIKE proc.id_producto_linea
                    INNER JOIN producto AS prod
                        ON prod.id_producto LIKE pl.id_producto
                    INNER JOIN linea AS l
                        ON l.id_linea LIKE pl.id_linea";
        
        return $this->formatted_database_query($sql); 
    }

    public function get_proceso_by_id($params)
    {
        $sql = "SELECT proc.id_proceso, proc.id_personalizado, proc.kilos_teoricos,
                        proc.kilos_reales, proc.hora_inicio, proc.hora_fin,
                        e.nombre AS jefe, l.codigo AS linea, prod.nombre AS producto
                    FROM proceso AS proc

                    INNER JOIN miembro_equipo AS me
                        ON me.id_miembro LIKE proc.id_jefe
                    INNER JOIN empleado AS e
                        ON e.id_empleado LIKE me.id_empleado
                    
                    INNER JOIN producto_linea AS pl
                        ON pl.id_producto_linea LIKE proc.id_producto_linea
                    INNER JOIN producto AS prod
                        ON prod.id_producto LIKE pl.id_producto
                    INNER JOIN linea AS l
                        ON l.id_linea LIKE pl.id_linea
                    
                    WHERE proc.id_personalizado LIKE '" . $params["id"] . "'";

            return $this->formatted_database_query($sql); 
    }


    public function add_proceso_incidencia($params)
    {
        $variables = $this->get_local_variables($params);

        // INSERT PROCESO
        $sql = "INSERT INTO proceso (`id_jefe`, `id_producto_linea`, `id_personalizado`,
                    `kilos_teoricos`, `kilos_reales`, `hora_inicio`, `hora_fin`)
                VALUES (" .
                    "'" . $variables["jefeId"] . "', " .
                    "'" . $variables["productoLineaId"] . "', " .
                    "'" . $params["idPersonalizado"] . "', " .
                    "'" . $params["kilosTeoricos"] . "', " .
                    "'" . $params["kilosReales"] . "', " .
                    "(SELECT CONVERT('" . $params["horaInicio"] . "', time)), " .
                    "(SELECT CONVERT(NOW(), time)))";
        
        $result = $this->formatted_database_query($sql);

        if ($result["success"] == 1)
        {
            if (!isset($params["lista"]) || empty($params["lista"]))
                return $result;

            foreach ($params["lista"] as $incidencia)
            {
                $result2 = $this->add_incidencia($incidencia);

                if ($result2["success"] == 0)
                    return $result2;
            }
        }

        return $result;
    }

    public function add_proceso_peso($params)
    {
        $variables = $this->get_local_variables($params);

        // INSERT PROCESO
        $sql = "INSERT INTO proceso (`id_jefe`, `id_producto_linea`, `id_personalizado`,
                    `kilos_teoricos`, `kilos_reales`, `hora_inicio`, `hora_fin`)
                VALUES (" .
                    "'" . $variables["jefeId"] . "', " .
                    "'" . $variables["productoLineaId"] . "', " .
                    "'" . $params["idPersonalizado"] . "', " .
                    "'" . $params["kilosTeoricos"] . "', " .
                    "'" . $params["kilosReales"] . "', " .
                    "(SELECT CONVERT('" . $params["horaInicio"] . "', time)), " .
                    "(SELECT CONVERT(NOW(), time)))";

        $result = $this->formatted_database_query($sql);

        if ($result["success"] == 1)
            return $this->add_peso($params);

        return $result;
    }

    private function get_local_variables($params)
    {
        // GET JEFE ID BY NAME.
        $jefeId = $this->empleadoService->get_jefe_by_name($params["jefe"]);
        $jefeId = $jefeId["data"][0]["id_empleado"];

        // GET PRODUCTO ID BY CODE.
        $productoId = $this->productoService->get_producto_by_codigo($params["producto"]);
        $productoId = $productoId["data"][0]["id_producto"];

        // GET LINEA ID BY CODE.
        $lineaId = $this->lineaService->get_linea_by_codigo($params["linea"]);
        $lineaId = $lineaId["data"][0]["id_linea"];

        // GET IF EXISTS PRODUCTO_LINEA BY VALUES
        $productoLineaId = $this->get_product_linea_id_by_value_ids($productoId, $lineaId);

        if (count($productoLineaId["data"]) == 0)
        {
            $productoLineaId = $this->add_producto_linea($productoId, $lineaId);
            $productoLineaId = $this->get_product_linea_id_by_value_ids($productoId, $lineaId);
        }

        if (!$productoLineaId["success"])
            return $productoLineaId;
        
        $productoLineaId = $productoLineaId["data"][0]["id_producto_linea"];

        return array(
            "jefeId" => $jefeId,
            "productoLineaId" => $productoLineaId
        );
    }

    private function add_incidencia($params)
    {
        $sql = "INSERT INTO proceso_incidencia (`id_proceso`, `descripcion`,
                    `hora_parada`, `hora_reinicio`) VALUES(" .
                    "(SELECT MAX(id_proceso) FROM proceso), '" .
                    $params["descripcion"] . "', " .
                    "(SELECT CONVERT('" . $params['horaParada'] . "', time)), " .
                    "(SELECT CONVERT('" . $params['horaReinicio'] . "', time)))";

        return $this->formatted_database_query($sql);
    }

    private function add_peso($params)
    {
        if ($this->add_tolerancias($params)["success"] == 0)
            return $result;

        $tolerancias = $this->get_num_tolerancias()["data"][0];
        $proceso = $this->get_num_procesos()["data"][0];

        $sql = "INSERT INTO proceso_peso (`id_proceso`, `id_tolerancias`, `peso_produccion`,
                    `numero_unidades`, `peso_bobinas`, `peso_total_bobina`,
                    `numero_cubetas`, `peso_cubetas`, `peso_bobina_cubetas`,
                    `peso_objetivo`, `margen_sobrepeso`, `margen_subpeso`)
                VALUES ('" .
                $proceso . "', '" .
                $tolerancias . "', '" .
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

    private function add_tolerancias($params)
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

    private function get_product_linea_id_by_value_ids($productoId, $lineaId)
    {
        $sql = "SELECT id_producto_linea " .
               "    FROM producto_linea " .
               "    WHERE id_producto LIKE '" . $productoId . "'" .
               "          AND id_linea LIKE '" . $lineaId . "'";

        return $this->formatted_database_query($sql);
    }

    private function add_producto_linea($producto, $linea)
    {
        $sql = "INSERT INTO producto_linea (`id_producto`, `id_linea`) " .
               "    VALUES('" . $producto . "', '" . $linea . "')";
        
        return $this->formatted_database_query($sql);
    }

    private function get_num_procesos()
    {
        $sql = "SELECT COUNT(*) FROM proceso";
        return $this->formatted_database_query($sql, DATABASE_QUERY_VALUES);
    }

    private function get_num_tolerancias()
    {
        $sql = "SELECT COUNT(*) FROM tolerancias";
        return $this->formatted_database_query($sql, DATABASE_QUERY_VALUES);
    }
}

?>
