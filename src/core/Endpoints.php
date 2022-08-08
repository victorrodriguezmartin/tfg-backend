
<?php

require_once("utils/DateTimeService.php");
require_once("lineas/LineaService.php");
require_once("empleados/EmpleadoService.php");
require_once("productos/ProductoService.php");
require_once("procesos/ProcesoService.php");

$endpoints = array(
    "getDate" => array(
                    "type" => "GET",
                    "service" => "DateTimeService",
                    "endpoint" => "get_date"),
                    
    "getTime" => array(
                    "type" => "GET",
                    "service" => "DateTimeService",
                    "endpoint" => "get_time"),
                    
    "getJefes" => array(
                    "type" => "GET",
                    "service" => "EmpleadoService",
                    "endpoint" => "get_jefes"),

    "getLineas" => array(
                    "type" => "GET",
                    "service" => "LineaService",
                    "endpoint" => "get_lineas"),

    "getProductos" => array(
                    "type" => "GET",
                    "service" => "ProductoService",
                    "endpoint" => "get_productos"),
    
    "nuevoProceso" => array(
                    "type" => "POST",
                    "service" => "ProcesoService",
                    "endpoint" => "add_proceso")
);

?>
