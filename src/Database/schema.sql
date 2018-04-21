CREATE DATABASE docentedb DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
use docentedb;
create table docente(
  id integer not null auto_increment,
  nombres varchar(127),
  apellidos varchar(127),
  grado varchar(63),
  urlimg varchar(255),
  primary key(id)
);
create table auxiliar(
  id integer not null auto_increment,
  nombres varchar(127),
  apellidos varchar(127),
  urlimg varchar(255),
  primary key(id)
);
create table materia(
  id integer not null auto_increment,
  sigla varchar(7),
  nombre varchar(255),
  primary key(id)
);
create table docente_materia(
  docente_id integer not null,
  materia_id integer not null,
  estado boolean,
  primary key(docente_id, materia_id),
  foreign key(docente_id)
  references docente(id)
  on delete cascade,
  foreign key(materia_id)
  references materia(id)
  on delete cascade
);
create table auxiliar_materia(
  auxiliar_id integer not null,
  materia_id integer not null,
  estado boolean,
  primary key(auxiliar_id, materia_id),
  foreign key(auxiliar_id)
  references auxiliar(id)
  on delete cascade,
  foreign key(materia_id)
  references materia(id)
  on delete cascade
);
create table comentario(
  id integer not null auto_increment,
  cont text,
  comentario_id integer default null,
  primary key(id)
);
create table reaccion(
  id integer not null auto_increment,
  nombre varchar(63),
  urlimg varchar(255),
  primary key(id)
);
create table administrador(
  id integer not null auto_increment,
  nombres varchar(127),
  apellidos varchar(127),
  correo varchar(128),
  passw varchar(255),
  primary key(id)
);
create table comentario_docente(
  comentario_id integer not null,
  docente_id integer not null,
  val tinyint,
  fecha date,
  hora time,
  primary key(comentario_id, docente_id),
  foreign key(comentario_id)
  references comentario(id)
  on delete cascade,
  foreign key(docente_id)
  references docente(id)
  on delete cascade
);
create table auxiliar_comentario(
  comentario_id integer not null,
  auxiliar_id integer not null,
  val tinyint,
  fecha date,
  hora time,
  primary key(comentario_id, auxiliar_id),
  foreign key(comentario_id)
  references comentario(id)
  on delete cascade,
  foreign key(auxiliar_id)
  references auxiliar(id)
  on delete cascade
);
create table comentario_reaccion(
  comentario_id integer not null,
  reaccion_id integer not null,
  primary key(comentario_id, reaccion_id),
  foreign key(comentario_id)
  references comentario(id)
  on delete cascade,
  foreign key(reaccion_id)
  references reaccion(id)
  on delete cascade
);

insert into materia values(null, 'INF-111',	'Introducción a la Informática');
insert into materia values(null, 'LAB-111',	'Laboratorio de Inf-111');
insert into materia values(null, 'INF-112',	'Organización de Computadoras');
insert into materia values(null, 'INF-113',	'Laboratorio de Computación');
insert into materia values(null, 'MAT-114',	'Matemática Discreta I');
insert into materia values(null, 'MAT-115',	'Análisis Matemático I - Calculo 1');
insert into materia values(null, 'LIN-116',	'Gramática Española');
insert into materia values(null, 'INF-121',	'Algoritmos y Programación');
insert into materia values(null, 'LAB-121',	'Laboratorio de Inf-121');
insert into materia values(null, 'FIS-122',	'Física I');
insert into materia values(null, 'LAB-122',	'Laboratorio de Física I');
insert into materia values(null, 'MAT-123',	'Matemática Discreta II');
insert into materia values(null, 'MAT-124',	'Álgebra Lineal');
insert into materia values(null, 'MAT-125',	'Análisis Matemático II Calculo 2');
insert into materia values(null, 'INF-131',	'Estructura de Datos y Algoritmos');
insert into materia values(null, 'LAB-131',	'Laboratorio de Inf-131');
insert into materia (sigla, nombre) values('FIS-132',	'Física II'),
('LAB-132',	'Laboratorio de Física II'),
('EST-133',	'Estadística I'),
('MAT-134',	'Análisis Matemático III'),
('LIN-135',	'Idioma I'),
('INF-141',	'Sistemas de Gestión'),
('INF-142',	'Fundamentos Digitales'),
('INF-143',	'Taller de Programación'),
('INF-144',	'Lógica para la Ciencia de la Computación'),
('EST-145',	'Estadística II'),
('INF-151',	'Sistemas Operativos'),
('INF-152',	'Sistemas de Información Gerencial'),
('INF-153',	'Assembler'),
('INF-154',	'Lenguajes Formales y Autómatas'),
('EST-155',	'Investigación de Operaciones I'),
('MAT-156',	'Análisis Numérico'),
('INF-161',	'Diseño y Administración de Base de Datos'),
('INF-162',	'Análisis y Diseño de Sistemas de Información'),
('INF-163',	'Ingeniería de Software'),
('INF-164',	'Teoría de la Información y Codificación'),
('EST-165',	'Investigación de Operaciones II'),
('INF-166',	'Informática y Sociedad'),
('INF-271',	'Teoría de Sistemas y Modelos'),
('INF-272',	'Taller de Base de Datos'),
('INF-273',	'Telemática'),
('LAB-273',	'Laboratorio de Inf-273'),
('INF-281',	'Taller de Sistemas de Información'),
('INF-282',	'Especificaciones Formales y Verificación'),
('INF-391',	'Simulación de Sistemas'),
('INF-398',	'Taller de Licenciatura I'),
('INF-399',	'Taller de Licenciatura II'),
('CPA-201',	'Sistemas Contables'),
('INF-312',	'Administración de Unidades de Procesamiento de Datos'),
('INF-314',	'Auditoría Informática'),
('INF-315',	'Planificación y Seguridad de los Sistemas Informáticos'),
('INF-316',	'Sistemas de Información Gerencial II'),
('INF-320',	'Programación Lógica'),
('INF-323',	'Programación Gráfica'),
('INF-324',	'Programación Multimedial'),
('INF-325',	'Programación Virtual'),
('INF-327',	'Sistemas de Control Automático'),
('INF-328',	'Comparación de Lenguajes'),
('INF-329',	'Idiomas II'),
('INF-330',	'Hardware I'),
('INF-331',	'Organización y Métodos de Información'),
('INF-332',	'Economía'),
('INF-333',	'Preparación y Evaluación de Proyectos I'),
('INF-335',	'Muestreo'),
('INF-336',	'Teoría General de Sistemas'),
('INF-338',	'Microprocesadores'),
('INF-351',	'Sistemas Expertos'),
('INF-354',	'Inteligencia Artificial'),
('INF-359',	'Compiladores y Traductores'),
('INF-360',	'Computabilidad y Complejidad Algorítmica'),
('INF-363',	'Modelos Lineales'),
('INF-364',	'Diseño Experimental'),
('INF-365',	'Simulación y Modelaje'),
('INF-367',	'Investigación Operativa III'),
('INF-369',	'Procesos Estocásticos'),
('INF-317',	'Sistemas en Tiempo Real y Distribuido'),
('INF-340',	'Arquitectura de Computadoras y Procesamientos en Paralelo'),
('INF-319',	'Programación Funcional y Software'),
('MAT-391',	'Seminario II'),
('INF-355',	'Lenguajes Naturales y Formales'),
('INF-361',	'Diseño y Análisis de Algoritmos I'),
('INF-334',	'Preparación y Evaluación de Proyectos II'),
('INF-311',	'Protocolos de Comunicación'),
('MAT-398',	'Diseño de Compiladores'),
('INF-357',	'Robótica'),
('INF-322',	'Programación Distribuida');
