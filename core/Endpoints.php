
<?php

require_once("utils/DateTimeService.php");
require_once("lineas/LineaService.php");
require_once("empleados/EmpleadoService.php");

$endpoints = array(
    "getDate" => array(
                    "service" => "DateTimeService",
                    "endpoint" => "get_date"),
                    
    "getTime" => array(
                    "service" => "DateTimeService",
                    "endpoint" => "get_time"),
                    
    "getJefes" => array(
                    "service" => "EmpleadoService",
                    "endpoint" => "get_jefes"),

    "getLineas" => array(
                    "service" => "LineaService",
                    "endpoint" => "get_lineas")
);

?>
