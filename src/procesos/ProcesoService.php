
<?php

class ProcesoService extends Service
{
    private $lineaService;
    private $productoService;
    private $empleadoService;

    private $procesoIncidenciaService;
    private $procesoPesoService;

    function __construct()
    {
        parent::__construct();

        $this->lineaService = new LineaService();
        $this->empleadoService = new EmpleadoService();
        $this->productoService = new ProductoService();
        $this->procesoIncidenciaService = new ProcesoIncidenciaService();
        $this->procesoPesoService = new ProcesoPesoService();
    }

    public function get_procesos_and_proceso_incidencia()
    {
        $sql = "SELECT proc.id_proceso, proc.id_personalizado, proc.kilos_teoricos,
                       proc.kilos_reales, proc.hora_inicio, proc.hora_fin,
                       e.nombre AS jefe, l.codigo AS linea, prod.nombre AS producto,
                       pi.descripcion as descripcion, pi.hora_parada as horaParada,
                       pi.hora_reinicio as horaReinicio
                    FROM proceso AS proc
                    
                    INNER JOIN miembro_equipo AS me
                        ON me.id_miembro_equipo LIKE proc.id_jefe
                    INNER JOIN empleado AS e
                        ON e.id_empleado LIKE me.id_empleado
                    
                    INNER JOIN producto_linea AS pl
                        ON pl.id_producto_linea LIKE proc.id_producto_linea
                    INNER JOIN producto AS prod
                        ON prod.id_producto LIKE pl.id_producto
                    INNER JOIN linea AS l
                        ON l.id_linea LIKE pl.id_linea

                    LEFT JOIN proceso_incidencia as pi
                        ON pi.id_proceso LIKE proc.id_proceso

                    LEFT JOIN proceso_peso as pp
                        ON pp.id_proceso LIKE proc.id_proceso
                        WHERE pp.id_proceso IS NULL";

        return $this->formatted_database_query($sql);
    }

    public function get_procesos_and_proceso_incidencia_by_id($param)
    {
            $sql = "SELECT proc.id_proceso, proc.id_personalizado, proc.kilos_teoricos,
                       proc.kilos_reales, proc.hora_inicio, proc.hora_fin,
                       e.nombre AS jefe, l.codigo AS linea, prod.nombre AS producto,
                       pi.descripcion as descripcion, pi.hora_parada as horaParada,
                       pi.hora_reinicio as horaReinicio
                    FROM proceso AS proc
                    
                    INNER JOIN miembro_equipo AS me
                        ON me.id_miembro_equipo LIKE proc.id_jefe
                    INNER JOIN empleado AS e
                        ON e.id_empleado LIKE me.id_empleado
                    
                    INNER JOIN producto_linea AS pl
                        ON pl.id_producto_linea LIKE proc.id_producto_linea
                    INNER JOIN producto AS prod
                        ON prod.id_producto LIKE pl.id_producto
                    INNER JOIN linea AS l
                        ON l.id_linea LIKE pl.id_linea

                    LEFT JOIN proceso_incidencia as pi
                        ON pi.id_proceso LIKE proc.id_proceso

                    LEFT JOIN proceso_peso as pp
                        ON pp.id_proceso LIKE proc.id_proceso
                    WHERE pp.id_proceso IS NULL
                        AND proc.id_personalizado LIKE '" . $param["id"] . "'";

        return $this->formatted_database_query($sql);
    }

    public function get_procesos_and_proceso_peso()
    {
        $sql = "SELECT proc.id_proceso, proc.id_personalizado, proc.kilos_teoricos,
                       proc.kilos_reales, proc.hora_inicio, proc.hora_fin,
                       e.nombre AS jefe, l.codigo AS linea, prod.nombre AS producto,
                       pp.peso_produccion, pp.numero_unidades, pp.peso_bobinas,
                       pp.peso_total_bobina, pp.numero_cubetas, pp.peso_cubetas,
                       pp.peso_bobina_cubetas, pp.peso_objetivo, pp.margen_sobrepeso,
                       pp.margen_subpeso
                    FROM proceso AS proc
                    
                    INNER JOIN miembro_equipo AS me
                        ON me.id_miembro_equipo LIKE proc.id_jefe
                    INNER JOIN empleado AS e
                        ON e.id_empleado LIKE me.id_empleado
                    
                    INNER JOIN producto_linea AS pl
                        ON pl.id_producto_linea LIKE proc.id_producto_linea
                    INNER JOIN producto AS prod
                        ON prod.id_producto LIKE pl.id_producto
                    INNER JOIN linea AS l
                        ON l.id_linea LIKE pl.id_linea

                    INNER JOIN proceso_peso as pp
                        ON pp.id_proceso LIKE proc.id_proceso";

        return $this->formatted_database_query($sql);
    }

    public function get_procesos_and_proceso_peso_by_id($param)
    {
        $sql = "SELECT proc.id_proceso, proc.id_personalizado, proc.kilos_teoricos,
                       proc.kilos_reales, proc.hora_inicio, proc.hora_fin,
                       e.nombre AS jefe, l.codigo AS linea, prod.nombre AS producto,
                       pp.peso_produccion, pp.numero_unidades, pp.peso_bobinas,
                       pp.peso_total_bobina, pp.numero_cubetas, pp.peso_cubetas,
                       pp.peso_bobina_cubetas, pp.peso_objetivo, pp.margen_sobrepeso,
                       pp.margen_subpeso
                    FROM proceso AS proc
                    
                    INNER JOIN miembro_equipo AS me
                        ON me.id_miembro_equipo LIKE proc.id_jefe
                    INNER JOIN empleado AS e
                        ON e.id_empleado LIKE me.id_empleado
                    
                    INNER JOIN producto_linea AS pl
                        ON pl.id_producto_linea LIKE proc.id_producto_linea
                    INNER JOIN producto AS prod
                        ON prod.id_producto LIKE pl.id_producto
                    INNER JOIN linea AS l
                        ON l.id_linea LIKE pl.id_linea

                    INNER JOIN proceso_peso as pp
                        ON pp.id_proceso LIKE proc.id_proceso
                    WHERE proc.id_personalizado LIKE '" . $param["id"] . "'";

        return $this->formatted_database_query($sql);
    }

    public function add_proceso_and_proceso_incidencia($params)
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
                $incidencia["idProceso"] = "(SELECT MAX(id_proceso) FROM proceso)";
                $result2 = $this->procesoIncidenciaService->add_proceso_incidencia($incidencia);

                if ($result2["success"] == 0)
                    return $result2;
            }
        }

        return $result;
    }

    public function add_proceso_and_proceso_peso($params)
    {
        $variables = $this->get_local_variables($params);

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
            if ($this->procesoPesoService->add_tolerancias($params)["success"] == 0)
                return $result;

            $params["tolerancias"] = $this->get_num_tolerancias()["data"][0];
            $params["numProceso"] = $this->get_num_procesos()["data"][0];

            return $this->procesoPesoService->add_proceso_peso($params);
        }

        return $result;
    }

    private function get_local_variables($params)
    {
        // GET JEFE ID BY NAME.
        $jefeId = $this->empleadoService->get_jefe_by_name($params["jefe"]);
        // print_r($jefeId);
        $jefeId = $jefeId["data"][0]["id_empleado"];
        // print_r($jefeId);

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
