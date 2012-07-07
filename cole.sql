/*
Source Server         : localhost_3306
Source Server Version : 50516
Source Host           : localhost:3306
Target Server Type    : MYSQL
Target Server Version : 50516

Date: 2012-07-06 12:29:48
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `gmagrupos`
-- ----------------------------
DROP TABLE IF EXISTS `gmagrupos`;
CREATE TABLE `gmagrupos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gmamodulos_id` int(11) unsigned NOT NULL,
  `gmbdescripciones_id` int(11) unsigned NOT NULL,
  `fecha_at` date NOT NULL,
  `fecha_in` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gmamodulos_id` (`gmamodulos_id`),
  KEY `gmbdescripciones_id` (`gmbdescripciones_id`),
  CONSTRAINT `gmagrupos_ibfk_1` FOREIGN KEY (`gmamodulos_id`) REFERENCES `gmamodulos` (`id`),
  CONSTRAINT `gmagrupos_ibfk_2` FOREIGN KEY (`gmbdescripciones_id`) REFERENCES `gmbdescripciones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of gmagrupos
-- ----------------------------
INSERT INTO `gmagrupos` VALUES ('1', '3', '27', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('2', '3', '6', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('3', '3', '36', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('4', '5', '6', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('5', '5', '17', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('6', '5', '18', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('7', '5', '19', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('8', '5', '20', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('9', '5', '21', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('10', '5', '22', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('11', '5', '23', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('12', '5', '29', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('13', '5', '36', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('14', '6', '6', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('15', '6', '11', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('16', '6', '28', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('17', '7', '1', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('18', '7', '2', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('19', '7', '3', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('20', '7', '4', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('21', '7', '6', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('22', '7', '36', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('23', '9', '6', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('24', '9', '12', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('25', '9', '15', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('26', '9', '16', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('27', '9', '36', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('28', '10', '5', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('29', '10', '35', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('30', '11', '7', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('31', '11', '8', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('32', '11', '9', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('33', '11', '10', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('34', '11', '24', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('35', '11', '25', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('36', '11', '26', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('37', '12', '11', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('38', '12', '12', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('39', '12', '31', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('40', '12', '32', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('41', '13', '13', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('42', '14', '13', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('43', '14', '15', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('44', '15', '14', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('45', '15', '33', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('46', '16', '16', '2012-01-05', '2012-01-05');
INSERT INTO `gmagrupos` VALUES ('47', '16', '33', '2012-01-05', '2012-01-05');

-- ----------------------------
-- Table structure for `gmamodulos`
-- ----------------------------
DROP TABLE IF EXISTS `gmamodulos`;
CREATE TABLE `gmamodulos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idtype` int(11) unsigned NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_at` date NOT NULL,
  `fecha_in` date NOT NULL,
  `image` varchar(45) DEFAULT '',
  `autores` varchar(45) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of gmamodulos
-- ----------------------------
INSERT INTO `gmamodulos` VALUES ('1', '1', 'MODULOS DE ADMINISTRACION', 'Es el conjunto que identifica a todos los modulos del usuario admnistrador.', '2012-01-05', '2012-01-05', 'modulos/mod_adm.gif', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('2', '2', 'MODULOS DE CONTENIDO', 'Es el conjunto que identifica a todos los modulos del usuario que maneja datos especificos y no asi globales.', '2012-01-05', '2012-01-05', 'modulos/mod_con.gif', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('3', '1', 'CONFIGURACION PRINCIPAL DEL SISTEMA', 'Es el modulo base que da vida al sistema, permitiendo configurar los datos.', '2012-01-05', '2012-01-05', 'modulos/3.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('4', '1', 'GESTION DE USUARIOS DEL SISTEMA', 'Es el modulo que da permisos de usuario para el uso de los diferentes modulos.', '2012-01-05', '2012-01-05', 'modulos/4.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('5', '1', 'GESTION DOCENTES - MATERIAS', 'Es el modulo que registra a los docentes y las materias que dicta por curso.', '2012-01-05', '2012-01-05', 'modulos/5.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('6', '1', 'GESTION ALUMNOS', 'Es el modulo que maneja los datos de los alumnos en general.', '2012-01-05', '2012-01-05', 'modulos/6.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('7', '1', 'GESTION DE PADRES Y/O APODERADOS', 'Es el modulo donde se maneja los datos de grupos de personas que son: Padres de Familia y/o Apoderados.', '2012-01-05', '2012-01-05', 'modulos/7.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('8', '1', 'GESTION DE LAS FAMILIAS SOS', 'Es el modulo que asigna a cada nio con sus Apoderados o Padres, estableciendo de esta manera el nucleo familiar de las familias SOS.', '2012-01-05', '2012-01-05', 'modulos/8.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('9', '1', 'GESTION DE DOCENTES - TALLERES', 'Es el modulo que majena los registros de docentes tanto como profesores del colegio como personas externas que dictaran los talleres.', '2012-01-05', '2012-01-05', 'modulos/9.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('10', '1', 'GESTION DE BECAS Y PAGOS', 'Es el modulo que se encarga de las cobranzas por el servicio que presta la unidad educativa, ademas de un control para sus becados.', '2012-01-05', '2012-01-05', 'modulos/10.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('11', '1', 'HISTORIAL FAMILIAR Y VIOLENCIA INTRA-FAMILIAR', 'Es el modulo que gestiona los datos de los historiales personales de los individuos como tambien los casos de violencia intrafamiliar.', '2012-01-05', '2012-01-05', 'modulos/11.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('12', '1', 'GESTION DE INSCRIPCION TALLERES', 'Es el modulo que inscribe al alumno en los diferentes talleres,tambien inscribe a sus Apoderados y/o Padres y diferentes interesados.', '2012-01-05', '2012-01-05', 'modulos/12.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('13', '1', 'EVALUACION CON INDICADORES ESPECIALES', 'Es el modulo que gestiona los indicadores por cada materia ademas de controlar los periodos de evaluacion.', '2012-01-05', '2012-01-05', 'modulos/13.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('14', '1', 'EVALUACION CON INDICADORES ESPECIALES PARA TALLERES', 'Es el modulo que gestiona los indicadores por cada taller segun el nivel de dificultad y curso en caso de ser talleres para alumnos, ademas de controlar los periodos de evaluacion.', '2012-01-05', '2012-01-05', 'modulos/14.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('15', '2', 'LIBRETA DE INDICADORES DEL ALUMNO', 'Es el modulo que gestiona la evaluacion efectuada con los indicadores especiales para los alumnos.', '2012-01-05', '2012-01-05', 'modulos/15.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('16', '2', 'LIBRETA DE INDICADORES DEL ALUMNO TALLERISTA', 'Es el modulo que gestiona la evaluacion efectuada a los alumnos talleristas mediante indicadores especiales de los talleres.', '2012-01-05', '2012-01-05', 'modulos/16.png', 'Desarrollo@2012<www.irraco.com>');
INSERT INTO `gmamodulos` VALUES ('17', '2', 'INSCRIPCION A TALLERES DEL CURSO', 'Es el modulo que gestiona la inscripcion a los talleres pertenecientes a un curso determinado.', '2012-01-05', '2012-01-05', 'modulos/17.png', 'Desarrollo@2012<www.irraco.com>');

-- ----------------------------
-- Table structure for `gmbdescripciones`
-- ----------------------------
DROP TABLE IF EXISTS `gmbdescripciones`;
CREATE TABLE `gmbdescripciones` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombret` varchar(100) NOT NULL,
  `campot` varchar(100) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `fecha_at` date NOT NULL,
  `fecha_in` date NOT NULL,
  `opcconst` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of gmbdescripciones
-- ----------------------------
INSERT INTO `gmbdescripciones` VALUES ('1', 'FAMILIAS', 'ESTADO_CIVIL_H', 'Conjunto de elementos con el que cuenta el estado civil del varon.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('2', 'FAMILIAS', 'ESTADO_CIVIL_M', 'Conjunto de elementos con el que cuenta el estado civil de la mujer.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('3', 'FAMILIAS', 'EST_CIV_MAT', 'Conjunto de elementos con el que cuenta el estado civil de la pareja.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('4', 'PERSONAS', 'LENGUA_MATERNA', 'Conjunto de elementos con el que cuenta la lengua materna.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('5', 'PAGOS', 'TIPO_FECHA', 'Conjunto de elementos que representan los tiempos de pago en el anio.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('6', 'PERSONAS', 'PAIS_NAC', 'Conjunto de elementos que representan los paises de origen de las personas.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('7', 'DATOS_PERSONAS', 'SABE_LEER', 'Opciones de confirmacion o negacion, de que si sabe leer la persona.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('8', 'DATOS_PERSONAS', 'HORAS_TRABAJO_DIA', 'Conjunto de elementos con la cantidad de carga horaria con la que cuenta la persona.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('9', 'DATOS_PERSONAS', 'TRABAJO_ES', 'Conjunto de opcion de dos estados que define la seguridad de su trabajo.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('10', 'VIVIENDAS', 'ESTADO_VIVIENDA', 'Conjunto de elementos que describe la calidad en que la vivienda es calificada.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('11', 'ALUMNOS_CURSOS', 'CURSO', 'Conjunto de elementos que representan los cursos de cada gestion escolar.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('12', 'ALUMNOS_TALLERES', 'GRUPO', 'Conjunto de elementos que representa los grupos que tienen todos los talleres.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('13', 'CICLOS', 'NIVEL', 'Conjunto de elementos que representan de forma numeral los niveles de referencia, agrupado por cursos.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('14', 'IND_EVALUACION', 'CALIFICACION', 'Conjunto de elementos que representan las calificaciones(notas) de la evaluacion mediante Indicadores Especiales para el colegio.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('15', 'TALLERES_G1', 'NIVEL_T', 'Conjunto de elementos que representan los de niveles de preparacion en los talleres en general.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('16', 'IND_EVTALLER_ALUMNOS', 'CALIFICACION', 'Conjunto de elementos que representan las calificaciones(notas) de la evaluacion mediante Indicadores Especiales para Talleres.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('17', 'HIST_DOCENTES', 'CATEGORIA', 'Conjunto de elementos que representan la categoria de ascenso que tienen los docentes.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('18', 'HIST_DOCENTES', 'FORM_ACAD', 'Conjunto de elementos que representan la formaci?n academica que pueden llegar a tener los docentes.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('19', 'HIST_DOCENTES', 'POSTGRADOS', 'Conjunto de elementos que representan los postgrados que pueden llegar a tener los docentes.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('20', 'HIST_DOCENTES', 'NIVEL_FORM', 'Conjunto de elementos que representan los niveles formaci?n en los que se preparo el docente.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('21', 'HIST_DOCENTES', 'FORM_ACAD', 'Conjunto de elementos que representan los niveles de formaci?n academica que pueden llegar a trabajar los docentes.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('22', 'CASOS_DOC', 'TIPO_AC', 'Conjunto de elementos que representan los niveles de acciones que va en contra o a favor de los docentes.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('23', 'CASOS_DOC', 'NIVEL_AFEC', 'Conjunto de elementos que representan las entidades que pueda llegar a afectar las acciones de un docente.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('24', 'CASOS_FAMILIA', 'FORMA_V', 'Conjunto de elementos que representan las formas generales del maltrato familiar.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('25', 'CASOS_FAMILIA', 'CAUSAL', 'Conjunto de elementos que representan los motivos por el que se presentan los hechos del maltrato familiar.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('26', 'CASOS_FAMILIA', 'ACCION', 'Conjunto de elementos que representan los hechos o acciones que se presentan en un maltrato familiar.', '2012-01-05', '2012-01-05', '0');
INSERT INTO `gmbdescripciones` VALUES ('27', 'INICIO', 'Gestion de inicio del sistema', 'Este elemento indica la Gestion en que inicia actividades el sistema.', '2012-01-05', '2012-01-05', '1');
INSERT INTO `gmbdescripciones` VALUES ('28', 'ALUMNOS', 'Gestion de nacimiento valido para alumnos.', 'Elemento inicial a partir del cual seran aceptados todas las fechas de nacimiento de los alumnos desde esta gestion inicial.', '2012-01-05', '2012-01-05', '1');
INSERT INTO `gmbdescripciones` VALUES ('29', 'MATERIAS', 'Numero maximo de materias', 'Este valor representa el maximo numero de materias que puede dictar el docente en la gestion actual.', '2012-01-05', '2012-01-05', '1');
INSERT INTO `gmbdescripciones` VALUES ('30', 'PERSONAS', 'Gestion de nacimiento valido para los padres de familia o apoderados.', 'Elemento inicial a partir del cual seran aceptados todas las fechas de nacimiento de los padres de familia o apoderados desde esta gestion inicial.', '2012-01-05', '2012-01-05', '1');
INSERT INTO `gmbdescripciones` VALUES ('31', 'TALLERES', 'Numero maximo de alumnos en un taller.', 'Elemento inicial a partir del cual seran aceptados la cantidad de alumnos por taller en cada grupo individual.', '2012-01-05', '2012-01-05', '1');
INSERT INTO `gmbdescripciones` VALUES ('32', 'ALUMNOS_TALLERES', 'Numero maximo talleres para un alumno.', 'Elemento inicial a partir del cual seran aceptados como maxima la cantidad de talleres asignados a un alumno.', '2012-01-05', '2012-01-05', '1');
INSERT INTO `gmbdescripciones` VALUES ('33', 'IND_LIBRETAS', 'Nota maxima para un alumno del colegio.', 'Elemento inicial que representa la nota maxima que es posible asignar a un alumno.', '2012-01-05', '2012-01-05', '1');
INSERT INTO `gmbdescripciones` VALUES ('34', 'DATOS_CARPETA', 'Posicion numeral de la nota minima.', 'Elemento que representa en un numero(posicion, de arrriba-abajo) la nota minima que es posible asignar a un alumno en su Carpeta de Seguimiento.', '2012-01-05', '2012-01-05', '1');
INSERT INTO `gmbdescripciones` VALUES ('35', 'BECAS', 'Letra o identificador para los alumnos retirados.', 'Elemento que se asigna a un alumno retirado en la gestion actual.', '2012-01-05', '2012-01-05', '1');
INSERT INTO `gmbdescripciones` VALUES ('36', 'PADRES', 'Gestion de nacimiento valido para padres.', 'Elemento inicial a partir del cual seran aceptados todas las fechas de nacimiento de los padres de familia u apoderados.', '2012-01-05', '2012-01-05', '1');

-- ----------------------------
-- Table structure for `gmbvalores`
-- ----------------------------
DROP TABLE IF EXISTS `gmbvalores`;
CREATE TABLE `gmbvalores` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gmbdescripciones_id` int(11) unsigned NOT NULL,
  `opciones` text NOT NULL,
  `fecha_at` date NOT NULL,
  `fecha_in` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gmbdescripciones_id` (`gmbdescripciones_id`),
  CONSTRAINT `gmbvalores_ibfk_1` FOREIGN KEY (`gmbdescripciones_id`) REFERENCES `gmbdescripciones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of gmbvalores
-- ----------------------------
INSERT INTO `gmbvalores` VALUES ('1', '1', 'SOLTERO_CASADO_CONVIVIENTE_DIVORCIADO_VIUDO', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('2', '2', 'SOLTERA_CASADA_CONVIVIENTE_DIVORCIADA_VIUDA', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('3', '3', 'CONVIVIENTES_CASADOS', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('4', '4', 'AYMARA_QUECHUA_CASTELLANO_GUARANI_OTRO NATIVO', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('5', '5', 'ENERO_FEBRERO_MARZO_ABRIL_MAYO_JUNIO_JULIO_AGOSTO_SEPTIEMBRE_OCTUBRE_NOVIEMBRE_DICIEMBRE', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('6', '6', 'BOLIVIA_PERU_VENEZUELA_ECUADOR_COLOMBIA_BRAZIL_PARAGUAY_URUGUAY_ARGENTINA_CHILE', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('7', '7', 'SI_NO_POCO', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('8', '8', '4 hrs._8 a 6 hrs._mas de 12 hrs.', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('9', '9', 'EVENTUAL_PERMANENTE', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('10', '10', 'PROPIA_ALQUILADA_ANTICRETICO_CASERA', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('11', '11', '1A_1B_1C_1D_1E_2A_2B_2C_2D_2E_3A_3B_3C_3D_3E_4A_4B_4C_4D_4E_5A_5B_5C_5D_5E_6A_6B_6C_6D_6E_7A_7B_7C_7D_7E_8A_8B_8C_8D_8E', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('12', '12', 'A_B_C_D', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('13', '13', '1_2_3_4_5_6_7_8', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('14', '14', 'NA_EP_S', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('15', '15', 'BASICO_INTERMEDIO_TECNICO MEDIO_TECNICO SUPERIOR', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('16', '16', 'NA_EP_S', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('17', '17', '1_2_3_4_5', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('18', '18', 'NORMALISTA EGRESADO_NORMALISTA TITULADO_INTERINO', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('19', '19', 'LICENCIATURA_DIPLOMADO_MAESTRIA_MASTERADO', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('20', '20', 'INICIAL_PRIMARIA_SECUNDARIA', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('21', '21', 'INICIAL_PRIMARIA_SECUNDARIA', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('22', '22', 'BENEFICIOSO_PERJUDICIAL', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('23', '23', 'PERSONAL_INSTITUCIONAL', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('24', '24', 'VIOLENCIA FISICA_VIOLENCIA PSICOLOGICA O VERVAL', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('25', '25', 'DINERO_DROGAS_ALCOHOL_TENSION Y STRESS', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('26', '26', 'AMENAZA_INTIMIDACION_VIOLACION_MALTRATO FISICO', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('27', '27', '2012', '2012-01-05', null);
INSERT INTO `gmbvalores` VALUES ('28', '28', '1985', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('29', '29', '4', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('30', '30', '1930', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('31', '31', '55', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('32', '32', '3', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('33', '33', '70', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('34', '34', 'S', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('35', '35', 'R', '2012-01-05', '2012-01-05');
INSERT INTO `gmbvalores` VALUES ('36', '36', '1930', '2012-01-05', '2012-01-05');

-- ----------------------------
-- Table structure for `gmuadministradores`
-- ----------------------------
DROP TABLE IF EXISTS `gmuadministradores`;
CREATE TABLE `gmuadministradores` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gmusuarios_id` int(11) unsigned NOT NULL,
  `ci` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `appaterno` varchar(50) DEFAULT '',
  `apmaterno` varchar(50) DEFAULT '',
  `genero` char(1) DEFAULT NULL,
  `paisnac` varchar(30) DEFAULT '',
  `lugarnac` varchar(150) DEFAULT '',
  `fechanac` date DEFAULT NULL,
  `image` varchar(45) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `gmusuarios_id` (`gmusuarios_id`),
  CONSTRAINT `gmuadministradores_ibfk_1` FOREIGN KEY (`gmusuarios_id`) REFERENCES `gmusuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of gmuadministradores
-- ----------------------------

-- ----------------------------
-- Table structure for `gmusuarios`
-- ----------------------------
DROP TABLE IF EXISTS `gmusuarios`;
CREATE TABLE `gmusuarios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cargo` varchar(125) NOT NULL,
  `email` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `passow` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of gmusuarios
-- ----------------------------

-- ----------------------------
-- Table structure for `gmusuariosasignar`
-- ----------------------------
DROP TABLE IF EXISTS `gmusuariosasignar`;
CREATE TABLE `gmusuariosasignar` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gmusuarios_id` int(11) unsigned NOT NULL,
  `gmamodulos_id` int(11) unsigned NOT NULL,
  `fecha_at` date NOT NULL,
  `fecha_in` date NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `gmusuarios_id` (`gmusuarios_id`),
  KEY `gmamodulos_id` (`gmamodulos_id`),
  CONSTRAINT `gmusuariosasignar_ibfk_1` FOREIGN KEY (`gmusuarios_id`) REFERENCES `gmusuarios` (`id`),
  CONSTRAINT `gmusuariosasignar_ibfk_2` FOREIGN KEY (`gmamodulos_id`) REFERENCES `gmamodulos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of gmusuariosasignar
-- ----------------------------
