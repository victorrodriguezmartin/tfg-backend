
<?php

class EmpleadoService extends Service
{
    // http://localhost/backend/server.php?request=getJefes
    public function get_jefes()
    {
        $sql =  "SELECT empleado.id_empleado,
                        empleado.nombre,
                        apellido1,
                        apellido2,
                        fecha_alta,
                        fecha_baja
                 FROM empleado
                 INNER JOIN miembro_equipo
                    ON empleado.id_empleado LIKE miembro_equipo.id_empleado
                 WHERE miembro_equipo.jefe LIKE true";
        
        return $this->formatted_database_query($sql);
    }
}

?>
