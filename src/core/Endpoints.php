
<?php

require_once("utils/DateTimeService.php");
require_once("lineas/LineaService.php");
require_once("empleados/EmpleadoService.php");
require_once("productos/ProductoService.php");
require_once("procesos/ProcesoService.php");
require_once("Procesoincidencias/ProcesoIncidenciaService.php");
require_once("ProcesoPeso/ProcesoPesoService.php");

/*
 * Para obtener datos
 * {
 *      "get<Nombre Datos>" => array(
 *          "type" => "GET",
 *          "service" => "<Nombre servicio>",
 *          "endpoint" => "get_<Nombre datos>"),
 * }
 * 
 *  
 * Para insertar datos
 * {
 *      "get<Nombre Datos>" => array(
 *          "type" => "POST",
 *          "service" => "<NombreServicio>",
 *          "endpoint" => "add_<nombre datos>"),
 * }
 */
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

    "getProcesos" => array(
                    "type" => "GET",
                    "service" => "ProcesoService",
                    "endpoint" => "get_procesos"),

    "getProcesosIncidencia" => array(
                    "type" => "GET",
                    "service" => "ProcesoService",
                    "endpoint" => "get_proceso_and_proceso_incidencia"),

    "getProcesosPeso" => array(
                    "type" => "GET",
                    "service" => "ProcesoService",
                    "endpoint" => "get_proceso_and_proceso_peso"),
    
    "addProcesoIncidencia" => array(
                    "type" => "POST",
                    "service" => "ProcesoService",
                    "endpoint" => "add_proceso_and_proceso_incidencia"),

    "addProcesoPeso" => array(
                    "type" => "POST",
                    "service" => "ProcesoService",
                    "endpoint" => "add_proceso_and_proceso_peso"),
);

?>
