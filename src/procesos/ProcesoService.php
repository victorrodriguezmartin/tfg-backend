
<?php

require_once("procesos/ToleranciaService.php");
require_once("productoLinea/ProductoLineaService.php");
require_once("procesoPesoUnitario/ProcesoPesoUnitarioService.php");

class ProcesoService extends Service
{
    private $toleranciaService;
    private $productoLineaService;
    private $procesoPesoUnitarioService;

    function __construct()
    {
        parent::__construct();

        $this->toleranciaService = new ToleranciaService();
        $this->productoLineaService = new ProductoLineaService();
        $this->procesoPesoUnitarioService = new ProcesoPesoUnitarioService;
    }

    public function add_proceso_incidencia($params)
    {
        $paramsProductoLinea = $this->productoLineaService->add_producto_linea_return_data($params);

        $paramsJefe = $paramsProductoLinea["params_jefe"];
        $paramsProductoLinea = $paramsProductoLinea["params_producto_linea"];

        $result = $this->insert_proceso(array(
            "id_jefe" => $paramsJefe["id_empleado"],
            "id_producto_linea" => $paramsProductoLinea["id_producto_linea"],
            "id_personalizado" => $params["idPersonalizado"],
            "kilos_teoricos" => $params["kilosTeoricos"],
            "kilos_reales" => $params["kilosReales"],
            "hora_inicio" => $params["horaInicio"],
        ));

        if ($result["success"] == 0)
            return $result;

        $id_proceso = $this->get_last_inserted_index()["data"][0];

        if (isset($params["lista"]) && !empty($params["lista"]))
        {
            foreach ($params["lista"] as $entrada)
            {
                $paramsProcesoIncidencia = array(
                    "id_proceso" => $id_proceso,
                    "descripcion" => $entrada["descripcion"],
                    "hora_parada" => $entrada["horaParada"],
                    "hora_reinicio" => $entrada["horaReinicio"]
                );
    
                $result = $this->insert_proceso_incidencia($paramsProcesoIncidencia);
                
                if ($result["success"] == 0)
                    break;
            }
        }

        return $result;
    }

    public function add_proceso_peso($params)
    {
        $paramsTolerancia = $this->toleranciaService->add_tolerancia_return_data($params);
        $paramsProductoLinea = $this->productoLineaService->add_producto_linea_return_data($params);

        $paramsJefe = $paramsProductoLinea["params_jefe"];
        $paramsProductoLinea = $paramsProductoLinea["params_producto_linea"];

        $result = $this->insert_proceso(array(
            "id_jefe" => $paramsJefe["id_empleado"],
            "id_producto_linea" => $paramsProductoLinea["id_producto_linea"],
            "id_personalizado" => $params["idPersonalizado"],
            "kilos_teoricos" => $params["kilosTeoricos"],
            "kilos_reales" => $params["kilosReales"],
            "hora_inicio" => $params["horaInicio"],
        ));

        if ($result["success"] == 0)
            return $result;

        $id_proceso = $this->get_last_inserted_index()["data"][0];

        $paramsProcesoPeso = array(
            "id_proceso" => $id_proceso,
            "id_tolerancia" => $paramsTolerancia["id_tolerancias"],
            "numero_unidades" => $params["numeroUnidades"],
            "peso_bobinas" => $params["pesoBobina"],
            "peso_total_bobina" => $params["pesoTotalBobina"],
            "numero_cubetas" => $params["numeroCubetas"],
            "peso_cubetas" => $params["pesoCubeta"],
            "peso_bobina_cubeta" => $params["pesoBobinaCubeta"],
            "peso_unitario_objetivo" => $params["pesoUnitarioObjetivo"],
            "margen_sobrepeso" => $params["margenSobrepeso"],
            "margen_subpeso" => $params["margenSubpeso"]
        );

        $result = $this->insert_proceso_peso($paramsProcesoPeso);

        if ($result["success"] == 0)
            return $result;

        $id_proceso_peso = $this->get_last_inserted_index()["data"][0];
        
        if (isset($params["lista"]) && !empty($params["lista"]))
        {
            foreach (json_decode($params["lista"]) as $entrada)
            {
                $paramsPesoUnitario = array(
                    "id_proceso_peso" => $id_proceso_peso,
                    "peso" => $entrada->peso,
                    "hora" => $entrada->hora
                );

                $result = $this->procesoPesoUnitarioService->insert_peso_unitario($paramsPesoUnitario);

                if ($result["success"] == 0)
                    break;
            }
        }

        return $result;
    }
    
    public function get_proceso_incidencia()
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

    public function get_proceso_incidencia_by_id($param)
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

    public function get_proceso_peso()
    {
        $sql = "SELECT proc.id_proceso, proc.id_personalizado, proc.kilos_teoricos,
                       proc.kilos_reales, proc.hora_inicio, proc.hora_fin,
                       e.nombre AS jefe, l.codigo AS linea, prod.nombre AS producto,
                       pp.id_proceso_peso, pp.numero_unidades, pp.peso_bobinas,
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

    public function get_proceso_peso_by_id()
    {
        $sql = "SELECT proc.id_proceso, proc.id_personalizado, proc.kilos_teoricos,
                       proc.kilos_reales, proc.hora_inicio, proc.hora_fin,
                       e.nombre AS jefe, l.codigo AS linea, prod.nombre AS producto,
                       pp.id_proceso_peso, pp.numero_unidades, pp.peso_bobinas,
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

    // PARAMS = [ id_jefe, id_producto_linea, id_personalizado, kilos_teoricos, kilos_reales, hora_inicio ]
    private function insert_proceso($params)
    {
        $sql = "INSERT INTO proceso (`id_jefe`, `id_producto_linea`, `id_personalizado`,
                    `kilos_teoricos`, `kilos_reales`, `hora_inicio`, `hora_fin`)
                VALUES (" .
                    "'" . $params["id_jefe"] . "', " .
                    "'" . $params["id_producto_linea"] . "', " .
                    "'" . $params["id_personalizado"] . "', " .
                    "'" . $params["kilos_teoricos"] . "', " .
                    "'" . $params["kilos_reales"] . "', " .
                    "(SELECT CONVERT('" . $params["hora_inicio"] . "', time)), " .
                    "(SELECT CONVERT(NOW(), time)))";

        return $this->formatted_database_query($sql);
    }

    // PARAMS = [ id_proceso, descripcion, hora_parada, hora_reinicio ]
    private function insert_proceso_incidencia($params)
    {
        $sql = "INSERT INTO proceso_incidencia (`id_proceso`, `descripcion`,
                    `hora_parada`, `hora_reinicio`) VALUES(" .
                    $params['id_proceso'] . ", '" .
                    $params['descripcion'] . "', " .
                    "(SELECT CONVERT('" . $params['hora_parada'] . "', time)), " .
                    "(SELECT CONVERT('" . $params['hora_reinicio'] . "', time)))";
        
        return $this->formatted_database_query($sql);
    }

    // PARAMS = [ id_proceso, id_tolerancias, numero_unidades, peso_bobinas, peso_total_bobina,
    //            numero_cubetas, peso_cubetas, peso_bobina_cubeta, peso_objetivo, margen_sobrepeso,
    //            margen_subpeso ]
    private function insert_proceso_peso($params)
    {
        $sql = "INSERT INTO proceso_peso (`id_proceso`, `id_tolerancias`, 
                    `numero_unidades`, `peso_bobinas`, `peso_total_bobina`,
                    `numero_cubetas`, `peso_cubetas`, `peso_bobina_cubetas`,
                    `peso_objetivo`, `margen_sobrepeso`, `margen_subpeso`)
                VALUES ('" .
                    $params["id_proceso"] . "', '" .
                    $params["id_tolerancia"] . "', '" .
                    $params["numero_unidades"] . "', '" .
                    $params["peso_bobinas"] . "', '" .
                    $params["peso_total_bobina"] . "', '" .
                    $params["numero_cubetas"] . "', '" .
                    $params["peso_cubetas"] . "', '" .
                    $params["peso_bobina_cubeta"] . "', '" .
                    $params["peso_unitario_objetivo"] . "', '" .
                    $params["margen_sobrepeso"] . "', '" .
                    $params["margen_subpeso"] . "')";
        
        return $this->formatted_database_query($sql);
    }
};

