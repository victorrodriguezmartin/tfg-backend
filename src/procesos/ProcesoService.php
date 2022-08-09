
<?php

class ProcesoService extends Service
{
    public function get_proceso_by_id($params)
    {
        $sql = "SELECT pl.id_proceso, pl.id_personalizado, pl.hora_inicio,
                    pl.hora_fin, pl.kilos_teoricos, pl.kilos_reales,
                    e.nombre as jefe, l.codigo as linea, p.nombre as producto
                FROM proceso_linea as pl
                INNER JOIN miembro_equipo as me
                    ON me.id_miembro LIKE pl.id_jefe
                INNER JOIN empleado as e
                    ON e.id_empleado LIKE me.id_empleado
                INNER JOIN linea as l
                    ON l.id_linea LIKE pl.id_linea
                INNER JOIN producto as p
                    ON p.id_producto LIKE pl.id_producto
                WHERE pl.id_personalizado LIKE '" . $params["id"] . "'";

            return $this->formatted_database_query($sql); 
    }

    public function get_all_procesos()
    {
        $sql = "SELECT pl.id_proceso, pl.id_personalizado, pl.hora_inicio,
                pl.hora_fin, pl.kilos_teoricos, pl.kilos_reales,
                e.nombre as jefe, l.codigo as linea, p.nombre as producto
            FROM proceso_linea as pl
            INNER JOIN miembro_equipo as me
                ON me.id_miembro LIKE pl.id_jefe
            INNER JOIN empleado as e
                ON e.id_empleado LIKE me.id_empleado
            INNER JOIN linea as l
                ON l.id_linea LIKE pl.id_linea
            INNER JOIN producto as p
                ON p.id_producto LIKE pl.id_producto";
        
        return $this->formatted_database_query($sql); 
    }

    public function add_proceso($params)
    {
        $sql = "INSERT INTO proceso_linea (`id_personalizado`, `id_linea`,
                    `id_producto`, `id_jefe`, `kilos_teoricos`, `kilos_reales`,
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
                $params["kilosReales"] . "', " .
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

    private function add_incidencia($params)
    {
        $sql = "INSERT INTO incidencia_proceso (`id_proceso`, `descripcion`,
                    `hora_parada`, `hora_reinicio`) VALUES(" .
                    "(SELECT MAX(id_proceso) FROM proceso_linea), '" .
                    $params["descripcion"] . "', " .
                    "(SELECT CONVERT('" . $params['horaParada'] . "', time)), " .
                    "(SELECT CONVERT('" . $params['horaReinicio'] . "', time)))";

        return $this->formatted_database_query($sql);
    }
}

?>
