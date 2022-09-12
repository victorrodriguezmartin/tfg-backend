SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `proceso_peso_unitario`;
DROP TABLE IF EXISTS `proceso_incidencia`;
DROP TABLE IF EXISTS `proceso_peso`;
DROP TABLE IF EXISTS `proceso`;
DROP TABLE IF EXISTS `producto_linea`;
DROP TABLE IF EXISTS `producto`;
DROP TABLE IF EXISTS `tolerancias`;
DROP TABLE IF EXISTS `linea`;
DROP TABLE IF EXISTS `miembro_equipo`;
DROP TABLE IF EXISTS `empleado`;
DROP TABLE IF EXISTS `equipo`;

CREATE TABLE IF NOT EXISTS `empleado` (
  `id_empleado` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(64) NOT NULL,
  `apellido1` VARCHAR(64) NOT NULL,
  `apellido2` VARCHAR(64) DEFAULT NULL,
  `fecha_alta` DATE NOT NULL,
  `fecha_baja` DATE DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `equipo` (
  `id_equipo` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(64) NOT NULL,
  `fecha_creacion` DATE NOT NULL
);

CREATE TABLE IF NOT EXISTS `miembro_equipo` (
  `id_miembro_equipo` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_empleado` INT UNIQUE NOT NULL,
  `id_equipo` INT NOT NULL,
  `jefe` BOOLEAN NOT NULL DEFAULT false
);

CREATE TABLE IF NOT EXISTS `producto` (
  `id_producto` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(8) NOT NULL,
  `nombre` VARCHAR(64) NOT NULL
);

CREATE TABLE IF NOT EXISTS `linea` (
  `id_linea` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(8) NOT NULL
);

CREATE TABLE IF NOT EXISTS `producto_linea` (
  `id_producto_linea` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_producto` INT NOT NULL,
  `id_linea` INT NOT NULL
);

CREATE TABLE IF NOT EXISTS `proceso` (
  `id_proceso` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_jefe` INT NOT NULL,
  `id_producto_linea` INT NOT NULL,
  `id_personalizado` VARCHAR(64) UNIQUE DEFAULT NULL,
  `kilos_teoricos` DOUBLE NOT NULL,
  `kilos_reales` DOUBLE NOT NULL,
  `hora_inicio` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `hora_fin` TIMESTAMP DEFAULT '0000-00-00 00:00:00'
);

CREATE TABLE IF NOT EXISTS `proceso_incidencia` (
  `id_proceso_incidencia` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_proceso` INT NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `hora_parada` TIMESTAMP DEFAULT '0000-00-00 00:00:00',
  `hora_reinicio` TIMESTAMP DEFAULT '0000-00-00 00:00:00'
);

CREATE TABLE IF NOT EXISTS `proceso_peso` (
  `id_proceso_peso` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_proceso` INT NOT NULL,
  `id_tolerancias` INT NOT NULL,
  `numero_unidades` INT NOT NULL,
  `peso_bobinas` DOUBLE NOT NULL,
  `peso_total_bobina` DOUBLE NOT NULL,
  `numero_cubetas` INT NOT NULL,
  `peso_cubetas` DOUBLE NOT NULL,
  `peso_bobina_cubetas` DOUBLE NOT NULL,
  `peso_objetivo` DOUBLE NOT NULL,
  `margen_sobrepeso` DOUBLE NOT NULL,
  `margen_subpeso` DOUBLE NOT NULL
);

CREATE TABLE IF NOT EXISTS `tolerancias` (
  `id_tolerancias` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `tolerancia_1` DOUBLE NOT NULL,
  `tolerancia_2` DOUBLE NOT NULL,
  `tolerancia_3` DOUBLE NOT NULL,
  `tolerancia_4` DOUBLE NOT NULL,
  `tolerancia_5` DOUBLE NOT NULL,
  `tolerancia_6` DOUBLE NOT NULL,
  `tolerancia_7` DOUBLE NOT NULL
);

CREATE TABLE IF NOT EXISTS `proceso_peso_unitario` (
  `id_proceso_peso_unitario` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `id_proceso_peso` INT NOT NULL,
  `peso` DOUBLE NOT NULL,
  `hora` TIMESTAMP DEFAULT '0000-00-00 00:00:00'
);

ALTER TABLE `miembro_equipo`
  ADD CONSTRAINT `miembro_equipo_fk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id_empleado`),
  ADD CONSTRAINT `miembro_equipo_fk_2` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`);

ALTER TABLE `producto_linea`
  ADD CONSTRAINT `producto_linea_fk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  ADD CONSTRAINT `producto_linea_fk_2` FOREIGN KEY (`id_linea`) REFERENCES `linea` (`id_linea`);

ALTER TABLE `proceso`
  ADD CONSTRAINT `proceso_fk_1` FOREIGN KEY (`id_jefe`) REFERENCES `miembro_equipo` (`id_miembro_equipo`),
  ADD CONSTRAINT `proceso_fk_2` FOREIGN KEY (`id_producto_linea`) REFERENCES `producto_linea` (`id_producto_linea`);

ALTER TABLE `proceso_incidencia`
  ADD CONSTRAINT `proceso_incidencia_fk_1` FOREIGN KEY (`id_proceso`) REFERENCES `proceso` (`id_proceso`);

ALTER TABLE `proceso_peso`
  ADD CONSTRAINT `proceso_peso_fk_1` FOREIGN KEY (`id_proceso`) REFERENCES `proceso` (`id_proceso`),
  ADD CONSTRAINT `proceso_peso_fk_2` FOREIGN KEY (`id_tolerancias`) REFERENCES `tolerancias` (`id_tolerancias`);

ALTER TABLE `proceso_peso_unitario`
  ADD CONSTRAINT `proceso_peso_unitario_fk_1` FOREIGN KEY (`id_proceso_peso`) REFERENCES `proceso_peso` (`id_proceso_peso`);

INSERT INTO `empleado` (`id_empleado`, `nombre`, `apellido1`, `apellido2`, `fecha_alta`, `fecha_baja`) VALUES
    (1, 'Alberto', 'Alvarez', 'Alvarez', '2022-04-05', NULL),
    (2, 'Alicia', 'Garcia', 'Garcia', '2022-04-05', NULL),
    (3, 'Juan', 'Fernandez', 'Fernandez', '2022-04-05', NULL),
    (4, 'Julia', 'Montoya', 'Montoya', '2022-04-05', NULL),
    (5, 'Sebastian', 'Sanchez', 'Sanchez', '2022-04-05', NULL),
    (6, 'Sofia', 'Rodriguez', 'Rodriguez', '2022-04-05', NULL);

INSERT INTO `equipo` (`id_equipo`, `nombre`, `fecha_creacion`) VALUES
    (1, 'ProduccionBase', '2022-04-05'),
    (2, 'ProduccionRelleno', '2022-04-05'),
    (3, 'ProduccionEnvoltorio', '2022-04-05');

INSERT INTO `miembro_equipo` (`id_miembro_equipo`, `id_empleado`, `id_equipo`, `jefe`) VALUES
    (1, 1, 1, 1),
    (2, 3, 1, 0),
    (3, 5, 2, 1),
    (4, 2, 2, 0),
    (5, 4, 3, 1),
    (6, 6, 3, 0);

INSERT INTO `producto` (`id_producto`, `codigo`, `nombre`) VALUES
    (1, 'C430', 'Conchas Chocolate'),
    (2, 'C431', 'Conchas Crema'),
    (3, 'C432', 'Conchas Nata'),
    (4, 'C433', 'Conchas Vainilla');

INSERT INTO `linea` (`id_linea`, `codigo`) VALUES
    (1, 'L801'),
    (2, 'L802'),
    (3, 'L803'),
    (4, 'L804');

