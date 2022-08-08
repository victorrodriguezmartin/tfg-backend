
<?php

class ProcesoService extends Service
{
    public function add_proceso($params)
    {
        $sql = "INSERT INTO proceso_linea (`id_personalizado`, `id_linea`, `id_producto`,
                    `id_jefe`, `kilos_teoricos`, `kilos_reales`, `eficiencia`,
                    `hora_inicio`, `hora_fin`)
                VALUES ('" .
                $params['idPersonalizado'] . "', " .
                "(SELECT id_linea
                        FROM linea
                        WHERE codigo LIKE '" . $params["linea"] . "'),
                    (SELECT id_producto
                        FROM producto
                        WHERE codigo LIKE '" . $params["producto"] . "'),
                    (SELECT me.id_miembro
                        FROM miembro_equipo as me
                        INNER JOIN empleado as e
                            ON me.id_empleado LIKE e.id_empleado
                        WHERE e.nombre LIKE '" . $params["jefe"] . "'), '" .
                $params["kilosTeoricos"] . "', '" .
                $params["kilosReales"] . "', '" .
                $this->calcularEficiencia($params["kilosTeoricos"],
                                          $params["kilosReales"]) . "', '" .
                $params["horaInicio"] . "', " .
                "(SELECT CONVERT(NOW(), time)))";

        return $this->formatted_database_query($sql);
    }

    private function calcularEficiencia($kilosTeoricos, $kilosReales)
    {
        return $kilosReales / $kilosTeoricos;
    }
}

?>
