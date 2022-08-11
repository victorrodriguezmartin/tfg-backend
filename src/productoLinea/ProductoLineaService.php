<?php

require_once("lineas/LineaService.php");
require_once("empleados/EmpleadoService.php");
require_once("procesos/ToleranciaService.php");

class ProductoLineaService extends Service
{
    private $lineaService;
    private $empleadoService;

    function __construct()
    {
        parent::__construct();

        $this->lineaService = new LineaService();
        $this->empleadoService = new EmpleadoService();
        $this->productoService = new ProductoService();
    }

    public function add_producto_linea_return_data($params)
    {
        $paramsJefe = $this->empleadoService->get_jefe_by_nombre($params["jefe"])["data"][0];
        $paramsLinea = $this->lineaService->get_linea_by_codigo($params["linea"])["data"][0];
        $paramsProducto = $this->productoService->get_producto_by_codigo($params["producto"])["data"][0];
        
        $paramsProductoLinea = $this->get_producto_linea_by_ids(array(
            "id_linea" => $paramsLinea["id_linea"],
            "id_producto" => $paramsProducto["id_producto"]
        ));

        // SI NO EXISTE PRODUCTO LINEA
        if (!isset($paramsProductoLinea["data"]) || empty($paramsProductoLinea["data"]))
        {
            $result = $this->insert_producto_linea(array(
                "id_producto" => $paramsProducto["id_producto"],
                "id_linea" => $paramsLinea["id_linea"]
            ));

            if ($result["success"] == 0)
                return $result;
            
            $paramsProductoLinea = array(
                "id_producto_linea" => 1,
                "id_producto" => $paramsProducto["id_producto"],
                "id_linea" => $paramsLinea["id_linea"]
            );
        }
        else
            $paramsProductoLinea = $paramsProductoLinea["data"][0];

        return array(
            "params_jefe" => $paramsJefe,
            "params_linea" => $paramsLinea,
            "params_producto" => $paramsProducto,
            "params_producto_linea" => $paramsProductoLinea
        );
    }

    public function get_producto_linea_by_ids($params)
    {
        $sql = "SELECT *
                FROM producto_linea
                WHERE id_producto LIKE '" . $params["id_producto"] . "'
                    AND id_linea LIKE '" . $params["id_linea"] . "'";

        return $this->formatted_database_query($sql);
    }

    public function insert_producto_linea($params)
    {
        $sql = "INSERT INTO producto_linea (`id_producto`, `id_linea`)
                VALUES('" . $params["id_producto"] . "', '" . $params["id_linea"] . "')";

        return $this->formatted_database_query($sql);
    }
}

?>